<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Mesa;
use App\Models\Sesion;

class HistorialVentasController extends Controller
{
    // ================================================================
    // INDEX — Panel principal
    // ================================================================
    public function index(Request $request)
    {
        if ($request->get('export') === 'csv') {
            return $this->exportar($request);
        }

        $filtroTiempo = $request->get('tiempo', 'hoy');
        [$fechaDesde, $fechaHasta] = $this->calcularRango($request);

        // ── Closure reutilizable para los filtros comunes ─────────────
        $applyFiltros = function ($q) use ($request) {
            return $q
                ->when(
                    $request->caja_id && $request->caja_id !== 'todas',
                    fn($q) => $q->where('caja_id', $request->caja_id)
                )
                ->when(
                    $request->metodo_pago && $request->metodo_pago !== 'todos',
                    fn($q) => $q->where('metodo_pago', $request->metodo_pago)
                )
                ->when(
                    $request->get('estado', 'activas') === 'activas',
                    fn($q) => $q->where('anulada', false)
                )
                ->when(
                    $request->estado === 'anuladas',
                    fn($q) => $q->where('anulada', true)
                );
        };

        // ── Ventas paginadas ─────────────────────────────────────────
        $ventas = $applyFiltros(
            Venta::whereBetween('created_at', [$fechaDesde, $fechaHasta])
                ->with(['sesion.mesa', 'caja', 'usuario'])
                ->orderBy('created_at', 'desc')
        )->paginate(25)->withQueryString();

        // ── Métricas en una sola query ───────────────────────────────
        $raw = $applyFiltros(
            Venta::whereBetween('created_at', [$fechaDesde, $fechaHasta])
        )->selectRaw('
            COUNT(*)                                                          AS num_tickets,
            SUM(CASE WHEN anulada=0 THEN total         ELSE 0 END)           AS total_facturado,
            SUM(CASE WHEN anulada=0 THEN subtotal       ELSE 0 END)          AS total_subtotal,
            SUM(CASE WHEN anulada=0 THEN iva            ELSE 0 END)          AS total_iva,
            SUM(CASE WHEN anulada=0 THEN descuento      ELSE 0 END)          AS total_descuentos,
            SUM(CASE WHEN anulada=0 THEN propina        ELSE 0 END)          AS total_propinas,
            SUM(CASE WHEN anulada=0 THEN num_comensales ELSE 0 END)          AS total_comensales,
            AVG(CASE WHEN anulada=0 THEN total          ELSE NULL END)       AS ticket_medio,
            MAX(CASE WHEN anulada=0 THEN total          ELSE NULL END)       AS ticket_maximo,
            SUM(CASE WHEN anulada=1 THEN 1              ELSE 0 END)          AS num_anuladas,
            SUM(CASE WHEN metodo_pago="efectivo"      AND anulada=0 THEN total ELSE 0 END) AS total_efectivo,
            SUM(CASE WHEN metodo_pago="tarjeta"       AND anulada=0 THEN total ELSE 0 END) AS total_tarjeta,
            SUM(CASE WHEN metodo_pago="bizum"         AND anulada=0 THEN total ELSE 0 END) AS total_bizum,
            SUM(CASE WHEN metodo_pago="transferencia" AND anulada=0 THEN total ELSE 0 END) AS total_transferencia
        ')->first();

        $totalFacturado = (float) ($raw->total_facturado ?? 0);
        $totalComensales = max(1, (int) ($raw->total_comensales ?? 1));

        $metricas = [
            'num_tickets' => $raw->num_tickets ?? 0,
            'total_facturado' => $totalFacturado,
            'total_iva' => $raw->total_iva ?? 0,
            'total_descuentos' => $raw->total_descuentos ?? 0,
            'total_propinas' => $raw->total_propinas ?? 0,
            'total_comensales' => $raw->total_comensales ?? 0,
            'ticket_medio' => $raw->ticket_medio ?? 0,
            'ticket_maximo' => $raw->ticket_maximo ?? 0,
            'num_anuladas' => $raw->num_anuladas ?? 0,
            'gasto_por_comensal' => round($totalFacturado / $totalComensales, 2),
            'total_efectivo' => $raw->total_efectivo ?? 0,
            'total_tarjeta' => $raw->total_tarjeta ?? 0,
            'total_bizum' => $raw->total_bizum ?? 0,
            'total_transferencia' => $raw->total_transferencia ?? 0,
        ];

        // ── Variación vs periodo anterior ────────────────────────────
        $durSeg = $fechaDesde->diffInSeconds($fechaHasta);
        $totalAnterior = Venta::whereBetween('created_at', [
            $fechaDesde->copy()->subSeconds($durSeg),
            $fechaDesde->copy()->subSecond(),
        ])->where('anulada', false)->sum('total');

        $variacion = $totalAnterior > 0
            ? round((($totalFacturado - $totalAnterior) / $totalAnterior) * 100, 1)
            : null;

        // ── Gráfica: por hora (≤31 días) o por día de semana (>31) ──
        $diasRango = $fechaDesde->diffInDays($fechaHasta);

        $ventasPorHora = $applyFiltros(
            Venta::whereBetween('created_at', [$fechaDesde, $fechaHasta])->where('anulada', false)
        )->selectRaw(
                $diasRango <= 31
                ? 'HOUR(created_at) AS franja, SUM(total) AS total_franja'
                : 'DAYOFWEEK(created_at) AS franja, SUM(total) AS total_franja'
            )->groupBy('franja')->orderBy('franja')->pluck('total_franja', 'franja');

        // ── Top 5 platos más pedidos ─────────────────────────────────
        $topPlatos = DB::table('pedido_platos as pp')
            ->join('platos as pl', 'pl.id', '=', 'pp.plato_id')
            ->join('pedidos as pe', 'pe.id', '=', 'pp.pedido_id')
            ->join('sesiones as s', 's.id', '=', 'pe.sesion_id')
            ->join('ventas as v', 'v.sesion_id', '=', 's.id')
            ->whereBetween('v.created_at', [$fechaDesde, $fechaHasta])
            ->where('v.anulada', false)
            ->when(
                $request->caja_id && $request->caja_id !== 'todas',
                fn($q) => $q->where('v.caja_id', $request->caja_id)
            )
            ->selectRaw('pl.nombre AS nombre_plato, pl.precio AS precio_unitario,
                SUM(pp.cantidad) AS total_vendidos,
                SUM(pp.cantidad * pl.precio) AS total_facturado')
            ->groupBy('pl.id', 'pl.nombre', 'pl.precio')
            ->orderByDesc('total_vendidos')
            ->limit(5)
            ->get();

        // ── Top 5 mesas más rentables ────────────────────────────────
        $topMesas = Venta::whereBetween('created_at', [$fechaDesde, $fechaHasta])
            ->where('anulada', false)->whereNotNull('numero_mesa')
            ->selectRaw('numero_mesa, SUM(total) AS total_mesa, COUNT(*) AS num_servicios,
                SUM(num_comensales) AS total_comensales')
            ->groupBy('numero_mesa')->orderByDesc('total_mesa')->limit(5)->get();

        $cajas = Caja::where('activa', true)->orderBy('nombre')->get();

        return view('admin.historialVentas', compact(
            'ventas',
            'metricas',
            'ventasPorHora',
            'topPlatos',
            'topMesas',
            'cajas',
            'fechaDesde',
            'fechaHasta',
            'diasRango',
            'filtroTiempo',
            'variacion',
            'totalAnterior'
        ));
    }

    // ================================================================
    // SHOW — JSON para el modal de detalle (AJAX)
    // ================================================================
    public function show(Venta $venta)
    {
        $venta->load(['sesion.mesa', 'caja', 'usuario']);

        $lineas = DB::table('pedido_platos as pp')
            ->join('platos as pl', 'pl.id', '=', 'pp.plato_id')
            ->join('pedidos as pe', 'pe.id', '=', 'pp.pedido_id')
            ->where('pe.sesion_id', $venta->sesion_id)
            ->selectRaw('pl.nombre AS nombre_plato, pl.precio AS precio_unitario,
                SUM(pp.cantidad) AS cantidad,
                SUM(pp.cantidad * pl.precio) AS subtotal_linea')
            ->groupBy('pl.id', 'pl.nombre', 'pl.precio')
            ->get();

        $duracion = ($venta->sesion?->created_at && $venta->created_at)
            ? (int) $venta->sesion->created_at->diffInMinutes($venta->created_at)
            : null;

        return response()->json([
            'id' => $venta->id,
            'numero_ticket' => $venta->numero_ticket ?? '#' . str_pad($venta->id, 5, '0', STR_PAD_LEFT),
            'created_at' => $venta->created_at->format('d/m/Y H:i'),
            'sesion' => ['mesa' => ['numero' => $venta->sesion?->mesa?->numero ?? '?']],
            'caja' => ['nombre' => $venta->caja?->nombre ?? '—'],
            'camarero' => ['nombre' => $venta->usuario?->nombre ?? '—'],
            'num_comensales' => $venta->num_comensales,
            'metodo_pago' => ucfirst($venta->metodo_pago),
            'subtotal' => $venta->subtotal,
            'descuento_monto' => $venta->descuento ?? 0,
            'iva' => $venta->iva,
            'total' => $venta->total,
            'propina' => $venta->propina ?? 0,
            'observaciones' => $venta->observaciones,
            'anulada' => (bool) $venta->anulada,
            'motivo_anulacion' => $venta->motivo_anulacion,
            'duracion_sesion_minutos' => $duracion,
            'lineas' => $lineas,
        ]);
    }

    // ================================================================
    // ANULAR — PATCH /admin/ventas/{venta}/anular
    // ================================================================
    public function anular(Request $request, Venta $venta)
    {
        $request->validate(['motivo_anulacion' => 'required|string|max:500']);

        if ($venta->anulada) {
            return back()->with('error', 'Esta venta ya estaba anulada.');
        }

        $venta->update(['anulada' => true, 'motivo_anulacion' => $request->motivo_anulacion]);

        return back()->with('success', "Ticket #{$venta->id} anulado correctamente.");
    }

    // ================================================================
    // EXPORTAR CSV — también llamado desde index() con ?export=csv
    // ================================================================
    public function exportar(Request $request)
    {
        [$fechaDesde, $fechaHasta] = $this->calcularRango($request);

        $ventas = Venta::with(['sesion.mesa', 'caja', 'usuario'])
            ->whereBetween('created_at', [$fechaDesde, $fechaHasta])
            ->when($request->caja_id !== 'todas', fn($q) => $q->where('caja_id', $request->caja_id))
            ->when($request->metodo_pago !== 'todos', fn($q) => $q->where('metodo_pago', $request->metodo_pago))
            ->orderBy('created_at', 'desc')->get();

        $nombre = 'ventas_' . $fechaDesde->format('Ymd') . '_' . $fechaHasta->format('Ymd') . '.csv';

        return response()->stream(function () use ($ventas) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($h, [
                'ID',
                'Ticket',
                'Fecha',
                'Hora',
                'Mesa',
                'Comensales',
                'Subtotal',
                'Descuento',
                'IVA',
                'Propina',
                'Total',
                'Método',
                'Caja',
                'Cobrado por',
                'Duración(min)',
                'Anulada',
                'Observaciones'
            ], ';');
            foreach ($ventas as $v) {
                $dur = $v->sesion?->created_at
                    ? (int) $v->sesion->created_at->diffInMinutes($v->created_at) : '';
                fputcsv($h, [
                    $v->id,
                    $v->numero_ticket ?? '#' . $v->id,
                    $v->created_at->format('d/m/Y'),
                    $v->created_at->format('H:i'),
                    'Mesa ' . ($v->numero_mesa ?? $v->sesion?->mesa?->numero ?? '?'),
                    $v->num_comensales,
                    number_format($v->subtotal, 2, ',', '.'),
                    number_format($v->descuento ?? 0, 2, ',', '.'),
                    number_format($v->iva, 2, ',', '.'),
                    number_format($v->propina ?? 0, 2, ',', '.'),
                    number_format($v->total, 2, ',', '.'),
                    ucfirst($v->metodo_pago),
                    $v->caja?->nombre ?? '',
                    $v->usuario?->nombre ?? '',
                    $dur,
                    $v->anulada ? 'SÍ' : 'NO',
                    $v->observaciones ?? '',
                ], ';');
            }
            fclose($h);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$nombre}\"",
        ]);
    }

    // ================================================================
    // PRIVADO — Rango de fechas
    // ================================================================
    private function calcularRango(Request $request): array
    {
        return match ($request->get('tiempo', 'hoy')) {
            'semana' => [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::SUNDAY)],
            'mes' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'anio' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'mes_especifico' => [
                Carbon::parse(($request->mes_exacto ?? now()->format('Y-m')) . '-01')->startOfMonth(),
                Carbon::parse(($request->mes_exacto ?? now()->format('Y-m')) . '-01')->endOfMonth(),
            ],
            'rango_custom' => [
                Carbon::parse($request->fecha_desde ?? now()->format('Y-m-d'))->startOfDay(),
                Carbon::parse($request->fecha_hasta ?? now()->format('Y-m-d'))->endOfDay(),
            ],
            'todo' => [Carbon::create(2000, 1, 1)->startOfDay(), Carbon::now()->endOfDay()],
            default => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()], // 'hoy'
        };
    }

// ================================================================
    // COBRAR — El motor del TPV
    // ================================================================
    public function cobrar(Request $request, \App\Models\Mesa $mesa)
    {
        // 1. Validar la petición (Sin typos)
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,bizum',
            'caja_id'     => 'required|exists:cajas,id'
        ]);

        try {
            // 2. Iniciar la transacción de Base de Datos
            DB::beginTransaction();

            // 3. Buscar la sesión activa de esta mesa de forma segura
            $sesionActiva = $mesa->sesiones()
                ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                ->lockForUpdate()
                ->latest()
                ->first();

            // 4. Si no hay sesión activa, rebotamos al usuario
            if (!$sesionActiva) {
                DB::rollBack();
                return back()->with('error', 'No se encontró una sesión activa para esta mesa.');
            }

            // 5. Recalcular el total (El viaje al supermercado)
            $pedidos = $sesionActiva->pedidos()
                ->with(['platos' => fn($q) => $q->withPivot('cantidad')])
                ->get();

            $totalMesa = $pedidos->sum(function($pedido) {
                return $pedido->platos->sum(function($plato) {
                    return $plato->precio * $plato->pivot->cantidad;
                });
            });
            
            // 6. Contar los comensales (con el truco Senior)
            $comensales = $sesionActiva->clientes()->count() ?: 1;

            // 7. Crear el ticket en la tabla Ventas
            Venta::create([
                'sesion_id'      => $sesionActiva->id,
                'numero_mesa'    => $mesa->numero,
                'num_comensales' => $comensales,
                'subtotal'       => $totalMesa,
                'iva'            => 0.00, // De momento
                'total'          => $totalMesa,
                'metodo_pago'    => $request->metodo_pago,
                'user_id'        => auth()->id(),
                'caja_id'        => $request->caja_id,
                'numero_ticket'  => 'TK-' . time(),
                'anulada'        => false,
            ]);

            // 8. Liberar la mesa
            $sesionActiva->update(['estado' => 'cerrada']);

            // 9. Confirmar todo y guardar en base de datos
            DB::commit();

            // 10. Redirigir al usuario con el mensaje verde
            return redirect()->route('admin.mesas')
                ->with('success', '¡Caja sonando! 💸 Mesa cobrada correctamente.');

        } catch (\Exception $e) {
            // 11. Si algo explota (Catch)
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Error al cobrar mesa: " . $e->getMessage());
            
            return back()->with('error', 'Ocurrió un error crítico al cobrar: ' . $e->getMessage());
        }
    }
}