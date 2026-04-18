<x-layouts.admin>
{{-- ── CABECERA ─────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Historial de Ventas</h1>
        <p class="text-muted mb-0 small">
            {{ $fechaDesde->format('d/m/Y') }} — {{ $fechaHasta->format('d/m/Y') }}
            · Snapshots inmutables del momento del cobro
        </p>
    </div>
    <a href="{{ route('admin.historial', array_merge(request()->all(), ['export'=>'csv'])) }}"
       class="btn btn-outline-secondary btn-sm fw-bold rounded-pill px-3">
        <i class="bi bi-download me-1"></i>CSV
    </a>
</div>

{{-- ── FILTROS ───────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.historial') }}" method="GET" id="formFiltros">

            {{-- Pills de período --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(['hoy'=>'Hoy','semana'=>'Esta semana','mes'=>'Este mes',
                          'anio'=>'Este año','mes_especifico'=>'Mes…',
                          'rango_custom'=>'Rango…','todo'=>'Todo'] as $val => $label)
                    <button type="button"
                        class="pill-filter {{ $filtroTiempo === $val ? 'active' : '' }}"
                        onclick="setPeriodo('{{ $val }}', this)">
                        {{ $label }}
                    </button>
                @endforeach
                <input type="hidden" name="tiempo" id="inputTiempo" value="{{ $filtroTiempo }}">
            </div>

            {{-- Campos extra por tipo de período --}}
            <div id="extraMesEspecifico"
                 class="{{ $filtroTiempo === 'mes_especifico' ? '' : 'd-none' }} mb-3">
                <input type="month" name="mes_exacto"
                    class="form-control form-control-sm w-auto d-inline-block border-0 bg-light rounded-3"
                    value="{{ request('mes_exacto', now()->format('Y-m')) }}">
            </div>
            <div id="extraRangoCustom"
                 class="{{ $filtroTiempo === 'rango_custom' ? '' : 'd-none' }} mb-3">
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="date" name="fecha_desde"
                        class="form-control form-control-sm w-auto border-0 bg-light rounded-3"
                        value="{{ request('fecha_desde', now()->format('Y-m-d')) }}">
                    <span class="text-muted">—</span>
                    <input type="date" name="fecha_hasta"
                        class="form-control form-control-sm w-auto border-0 bg-light rounded-3"
                        value="{{ request('fecha_hasta', now()->format('Y-m-d')) }}">
                </div>
            </div>

            {{-- Selects secundarios --}}
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <select name="caja_id" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="todas">Todas las cajas</option>
                    @foreach($cajas as $caja)
                        <option value="{{ $caja->id }}" {{ request('caja_id') == $caja->id ? 'selected':'' }}>
                            {{ $caja->nombre }}
                        </option>
                    @endforeach
                </select>

                <select name="metodo_pago" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="todos">Todos los métodos</option>
                    <option value="efectivo"      {{ request('metodo_pago')=='efectivo'      ?'selected':'' }}>💵 Efectivo</option>
                    <option value="tarjeta"       {{ request('metodo_pago')=='tarjeta'       ?'selected':'' }}>💳 Tarjeta</option>
                    <option value="bizum"         {{ request('metodo_pago')=='bizum'         ?'selected':'' }}>📱 Bizum</option>
                    <option value="transferencia" {{ request('metodo_pago')=='transferencia' ?'selected':'' }}>🏦 Transferencia</option>
                </select>

                <select name="estado" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="activas"  {{ request('estado','activas')=='activas'  ?'selected':'' }}>Solo activas</option>
                    <option value="anuladas" {{ request('estado')=='anuladas' ?'selected':'' }}>Solo anuladas</option>
                    <option value="todas"    {{ request('estado')=='todas'    ?'selected':'' }}>Todas</option>
                </select>

                <button type="submit"
                    class="btn btn-sm fw-bold px-3 rounded-pill"
                    style="background:#111827;color:#fff;border:none">
                    <i class="bi bi-funnel-fill me-1"></i>Filtrar
                </button>

                @if(request()->hasAny(['caja_id','metodo_pago','estado','mes_exacto','fecha_desde']))
                    <a href="{{ route('admin.historial') }}"
                       class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-x me-1"></i>Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- ── TARJETAS DE MÉTRICAS ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3 anim" style="animation-delay:.05s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#111827"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Total facturado</div>
                <div class="stat-card__value">{{ number_format($metricas['total_facturado'],2,',','.') }}€</div>
                <div class="stat-card__sub d-flex align-items-center gap-1">
                    {{ $metricas['num_tickets'] }} tickets
                    @if(!is_null($variacion))
                        <span class="{{ $variacion >= 0 ? 'var-up' : 'var-down' }}">
                            {{ $variacion >= 0 ? '↑' : '↓' }}{{ abs($variacion) }}%
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.1s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#059669"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Ticket medio</div>
                <div class="stat-card__value">{{ number_format($metricas['ticket_medio'],2,',','.') }}€</div>
                <div class="stat-card__sub">Máx: {{ number_format($metricas['ticket_maximo'],2,',','.') }}€</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.15s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#2563eb"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Gasto / comensal</div>
                <div class="stat-card__value">{{ number_format($metricas['gasto_por_comensal'],2,',','.') }}€</div>
                <div class="stat-card__sub">{{ $metricas['total_comensales'] }} comensales</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.2s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#d97706"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Propinas</div>
                <div class="stat-card__value">{{ number_format($metricas['total_propinas'],2,',','.') }}€</div>
                <div class="stat-card__sub">
                    @if($metricas['num_anuladas'] > 0)
                        <span style="color:#EF4444">{{ $metricas['num_anuladas'] }} anuladas</span>
                    @else
                        IVA: {{ number_format($metricas['total_iva'],2,',','.') }}€
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── GRÁFICA + MÉTODOS + TOP PLATOS ──────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Gráfica por hora / día semana --}}
    <div class="col-12 col-lg-7 anim" style="animation-delay:.25s">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
                    {{ $diasRango <= 31 ? 'Ventas por franja horaria' : 'Ventas por día de semana' }}
                </h6>
            </div>
            <div class="card-body px-4 pb-4 pt-3">
                @php
                    $esPorHora = $diasRango <= 31;
                    $franjas   = $esPorHora
                        ? range(10, 23)
                        : [2=>'Lun',3=>'Mar',4=>'Mié',5=>'Jue',6=>'Vie',7=>'Sáb',1=>'Dom'];
                    $maxValor  = $ventasPorHora->max() ?: 1;
                    // Hora pico para pintarla de naranja
                    $horaMax   = $ventasPorHora->keys()->first();
                    foreach ($ventasPorHora as $k => $v) { if ($v >= $maxValor) $horaMax = $k; }
                @endphp

                @if($ventasPorHora->isEmpty())
                    <div class="text-center text-muted py-4 small">
                        <i class="bi bi-bar-chart opacity-25 d-block fs-1 mb-2"></i>
                        Sin datos para este período
                    </div>
                @else
                    <div class="chart-wrap">
                        @foreach($franjas as $key => $label)
                            @php
                                $k     = $esPorHora ? $key : $key;
                                $label = $esPorHora ? str_pad($key,2,'0',STR_PAD_LEFT).'h' : $label;
                                $valor = $ventasPorHora[$k] ?? 0;
                                $pct   = $maxValor > 0 ? max(2, round(($valor/$maxValor)*100)) : 2;
                                $isPeak = $k == $horaMax && $valor > 0;
                            @endphp
                            <div class="chart-col"
                                 title="{{ $label }}: {{ number_format($valor,2,',','.') }}€">
                                <div class="chart-bar {{ $isPeak ? 'active':'' }}"
                                     style="height:{{ $pct }}%"></div>
                                <span class="chart-label">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Métodos de pago + Top platos --}}
    <div class="col-12 col-lg-5 anim" style="animation-delay:.3s">
        <div class="row g-3 h-100">

            {{-- Desglose métodos --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold mb-3" style="letter-spacing:.05em">DESGLOSE DE MÉTODOS</div>
                        @php
                            $totalG = max(1, $metricas['total_facturado']);
                            $metodos = [
                                'efectivo'      => ['💵', $metricas['total_efectivo'],     '#059669'],
                                'tarjeta'       => ['💳', $metricas['total_tarjeta'],      '#2563eb'],
                                'bizum'         => ['📱', $metricas['total_bizum'],        '#7c3aed'],
                                'transferencia' => ['🏦', $metricas['total_transferencia'],'#d97706'],
                            ];
                        @endphp
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($metodos as $key => [$icon, $valor, $color])
                                @if($valor > 0)
                                    <div class="flex-fill text-center p-2 rounded-3 bg-light" style="min-width:70px">
                                        <span style="font-size:1.1rem">{{ $icon }}</span>
                                        <div class="fw-bold mt-1" style="font-size:.82rem">
                                            {{ number_format($valor,2,',','.') }}€
                                        </div>
                                        <div class="metodo-strip">
                                            <div class="metodo-strip__fill"
                                                 style="width:{{ round(($valor/$totalG)*100) }}%;background:{{ $color }}">
                                            </div>
                                        </div>
                                        <div class="text-muted mt-1" style="font-size:.67rem;text-transform:capitalize">
                                            {{ $key }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top 5 platos --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <div class="text-muted small fw-bold mb-3" style="letter-spacing:.05em">TOP 5 PLATOS MÁS PEDIDOS</div>
                        @forelse($topPlatos as $i => $plato)
                            @php $maxP = $topPlatos->max('total_vendidos') ?: 1; @endphp
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="rounded-2 d-flex align-items-center justify-content-center fw-bold"
                                      style="width:20px;height:20px;font-size:.7rem;background:#F3F4F6;color:#374151;flex-shrink:0">
                                    {{ $i+1 }}
                                </span>
                                <div style="flex:1;min-width:0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold text-truncate" style="font-size:.83rem">
                                            {{ $plato->nombre_plato }}
                                        </span>
                                        <span class="fw-bold ms-2" style="font-size:.83rem;white-space:nowrap">
                                            ×{{ $plato->total_vendidos }}
                                        </span>
                                    </div>
                                    <div style="height:3px;background:#F3F4F6;border-radius:2px;margin-top:.2rem;overflow:hidden">
                                        <div style="height:100%;background:#111827;border-radius:2px;
                                             width:{{ round(($plato->total_vendidos/$maxP)*100) }}%"></div>
                                    </div>
                                </div>
                                <span class="text-muted" style="font-size:.76rem;white-space:nowrap">
                                    {{ number_format($plato->total_facturado,2,',','.') }}€
                                </span>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Sin datos de platos aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── TABLA DE TICKETS ─────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-4 anim" style="animation-delay:.35s">
    <div class="card-header bg-white border-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">
            Tickets
            <span class="text-muted fw-normal small ms-1">({{ $ventas->total() }})</span>
        </h6>
        <span class="text-muted small d-none d-md-inline">Clic en una fila para ver el detalle</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table h-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Ticket</th>
                        <th>Fecha / Hora</th>
                        <th>Mesa</th>
                        <th>Caja</th>
                        <th>Comensales</th>
                        <th>Método</th>
                        <th>Duración</th>
                        <th class="text-end pe-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                        @php
                            $duracion = null;
                            if ($venta->sesion?->created_at && $venta->created_at) {
                                $duracion = (int) $venta->sesion->created_at->diffInMinutes($venta->created_at);
                            }
                        @endphp
                        <tr class="ticket-row {{ $venta->anulada ? 'opacity-50':'' }}"
                            onclick="abrirDetalleTicket({{ $venta->id }})">
                            <td class="ps-4">
                                <code class="fw-bold" style="font-size:.8rem;color:#111827">
                                    {{ $venta->numero_ticket ?? '#'.str_pad($venta->id,5,'0',STR_PAD_LEFT) }}
                                </code>
                                @if($venta->anulada)
                                    <span class="badge-anulada ms-1">ANULADA</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $venta->created_at->format('d/m/Y') }}<br>
                                <span class="fw-semibold text-dark">{{ $venta->created_at->format('H:i') }}</span>
                            </td>
                            <td class="fw-bold">
                                Mesa {{ $venta->numero_mesa ?? $venta->sesion?->mesa?->numero ?? '?' }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border" style="font-size:.74rem">
                                    {{ $venta->caja?->nombre ?? '—' }}
                                </span>
                            </td>
                            <td class="text-center text-muted small">
                                <i class="bi bi-people me-1"></i>{{ $venta->num_comensales }}
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($venta->metodo_pago) {
                                        'efectivo'      => 'badge-efectivo',
                                        'tarjeta'       => 'badge-tarjeta',
                                        'bizum'         => 'badge-bizum',
                                        'transferencia' => 'badge-transferencia',
                                        default         => 'badge bg-secondary text-white',
                                    };
                                    $iconClass = match($venta->metodo_pago) {
                                        'efectivo'      => 'bi-cash',
                                        'tarjeta'       => 'bi-credit-card',
                                        'bizum'         => 'bi-phone',
                                        'transferencia' => 'bi-bank',
                                        default         => 'bi-question',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}">
                                    <i class="bi {{ $iconClass }} me-1"></i>{{ ucfirst($venta->metodo_pago) }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $duracion ? $duracion.'min' : '—' }}
                            </td>
                            <td class="text-end pe-4">
                                <span class="fw-bold {{ $venta->anulada ? 'text-decoration-line-through text-muted':'fs-6' }}">
                                    {{ number_format($venta->total,2,',','.') }}€
                                </span>
                                @if(($venta->propina ?? 0) > 0)
                                    <br>
                                    <span class="text-muted" style="font-size:.7rem">
                                        +{{ number_format($venta->propina,2,',','.') }}€ propina
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2 opacity-25"></i>
                                No hay ventas para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ventas->hasPages())
        <div class="px-4 py-3 border-top">{{ $ventas->links() }}</div>
    @endif
</div>

{{-- ── MODAL DETALLE TICKET ─────────────────────────────────────────── --}}
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-3"
                 style="background:#111827;border-radius:16px 16px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="modalTitulo">Detalle del ticket</h5>
                    <p class="mb-0 text-white opacity-50 small" id="modalSub"></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalBody">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm me-2"></div>Cargando…
                </div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 pb-4 gap-2"
                 style="border-radius:0 0 16px 16px" id="modalFooter">
                <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4"
                        data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL ANULAR ─────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-slash-circle me-2"></i>Anular venta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAnular" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body pt-0">
                    <p class="text-muted small">
                        El ticket <strong id="ticketAnular"></strong> quedará marcado como anulado.
                        Esta acción queda registrada y no se puede revertir.
                    </p>
                    <label class="form-label fw-bold small">Motivo *</label>
                    <textarea name="motivo_anulacion" class="form-control rounded-3" rows="2"
                        required placeholder="Ej: Error en el pedido…"></textarea>
                </div>
                <div class="modal-footer border-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-3">
                        Confirmar anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ── Pills de período ──────────────────────────────────────────────────
function setPeriodo(valor, el) {
    document.getElementById('inputTiempo').value = valor;
    document.querySelectorAll('.pill-filter').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('extraMesEspecifico').classList.add('d-none');
    document.getElementById('extraRangoCustom').classList.add('d-none');
    if (valor === 'mes_especifico') document.getElementById('extraMesEspecifico').classList.remove('d-none');
    if (valor === 'rango_custom')   document.getElementById('extraRangoCustom').classList.remove('d-none');

    // Auto-submit al cambiar período (excepto los que necesitan input)
    if (!['mes_especifico','rango_custom'].includes(valor)) {
        document.getElementById('formFiltros').submit();
    }
}

// ── Modal detalle ─────────────────────────────────────────────────────
function abrirDetalleTicket(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
    modal.show();
    document.getElementById('modalBody').innerHTML =
        '<div class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Cargando…</div>';
    document.getElementById('modalFooter').innerHTML =
        '<button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>';

    fetch(`/admin/ventas/${id}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(v => renderDetalle(v, id))
    .catch(() => {
        document.getElementById('modalBody').innerHTML =
            '<div class="text-center py-4 text-danger">Error al cargar el ticket.</div>';
    });
}

function renderDetalle(v, id) {
    document.getElementById('modalTitulo').textContent = v.numero_ticket;
    document.getElementById('modalSub').textContent =
        `Mesa ${v.sesion?.mesa?.numero ?? '?'} · ${v.caja?.nombre ?? '?'} · ${v.camarero?.nombre ?? '?'} · ${v.created_at}`;

    const lineas = (v.lineas || []).map(l => `
        <tr>
            <td class="text-muted small ps-2">${l.nombre_plato}</td>
            <td class="text-center text-muted small">×${l.cantidad}</td>
            <td class="text-end text-muted small">${parseFloat(l.precio_unitario||0).toFixed(2)}€</td>
            <td class="text-end fw-bold pe-2">${parseFloat(l.subtotal_linea||0).toFixed(2)}€</td>
        </tr>`).join('') || '<tr><td colspan="4" class="text-muted text-center small py-3">Sin líneas registradas</td></tr>';

    const alertaAnulada = v.anulada
        ? `<div class="alert py-2 px-3 small mb-3" style="background:#fee2e2;color:#991b1b;border:none;border-radius:10px">
            <i class="bi bi-slash-circle me-1"></i><strong>ANULADA</strong>${v.motivo_anulacion ? ' · ' + v.motivo_anulacion : ''}
           </div>` : '';

    document.getElementById('modalBody').innerHTML = `
        ${alertaAnulada}
        <div class="row g-2 mb-3">
            ${[
                ['COMENSALES', v.num_comensales],
                ['DURACIÓN',   v.duracion_sesion_minutos ? v.duracion_sesion_minutos+'min' : '—'],
                ['MÉTODO',     v.metodo_pago],
                ['PROPINA',    parseFloat(v.propina||0).toFixed(2)+'€'],
            ].map(([l,val]) => `
                <div class="col-6 col-md-3">
                    <div class="text-center p-2 bg-light rounded-3">
                        <div class="text-muted" style="font-size:.68rem;font-weight:700;text-transform:uppercase">${l}</div>
                        <div class="fw-bold" style="font-size:.95rem">${val}</div>
                    </div>
                </div>`).join('')}
        </div>
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-2 small text-muted text-uppercase" style="font-size:.68rem">Plato</th>
                    <th class="text-center small text-muted text-uppercase" style="font-size:.68rem">Cant.</th>
                    <th class="text-end small text-muted text-uppercase" style="font-size:.68rem">P.Unit.</th>
                    <th class="text-end pe-2 small text-muted text-uppercase" style="font-size:.68rem">Subtotal</th>
                </tr>
            </thead>
            <tbody>${lineas}</tbody>
            <tfoot class="border-top">
                <tr>
                    <td colspan="3" class="text-end text-muted small">Subtotal</td>
                    <td class="text-end pe-2 small">${parseFloat(v.subtotal||0).toFixed(2)}€</td>
                </tr>
                ${parseFloat(v.descuento_monto||0) > 0 ? `<tr>
                    <td colspan="3" class="text-end text-muted small">Descuento</td>
                    <td class="text-end pe-2 small text-danger">-${parseFloat(v.descuento_monto).toFixed(2)}€</td>
                </tr>` : ''}
                ${parseFloat(v.iva||0) > 0 ? `<tr>
                    <td colspan="3" class="text-end text-muted small">IVA</td>
                    <td class="text-end pe-2 small">${parseFloat(v.iva).toFixed(2)}€</td>
                </tr>` : ''}
                <tr>
                    <td colspan="3" class="text-end fw-bold">TOTAL</td>
                    <td class="text-end pe-2 fw-bold fs-5">${parseFloat(v.total||0).toFixed(2)}€</td>
                </tr>
            </tfoot>
        </table>
        ${v.observaciones ? `<div class="mt-3 p-2 bg-light rounded-3 small text-muted">
            <i class="bi bi-chat-left-text me-1"></i>${v.observaciones}</div>` : ''}
    `;

    // Botón anular (solo si no está anulada)
    if (!v.anulada) {
        document.getElementById('modalFooter').innerHTML = `
            <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-3 me-auto"
                    data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-outline-danger fw-bold rounded-pill px-3"
                    onclick="prepararAnulacion(${id}, '${v.numero_ticket}')">
                <i class="bi bi-slash-circle me-1"></i>Anular
            </button>`;
    }
}

function prepararAnulacion(id, ticket) {
    bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
    document.getElementById('ticketAnular').textContent = ticket;
    document.getElementById('formAnular').action = `/admin/ventas/${id}/anular`;
    setTimeout(() => new bootstrap.Modal(document.getElementById('modalAnular')).show(), 350);
}
</script>

</x-layouts.admin>