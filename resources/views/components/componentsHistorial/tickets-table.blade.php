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
