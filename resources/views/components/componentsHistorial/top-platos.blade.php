{{-- ── TOP 5 PLATOS MÁS PEDIDOS ──────────────────────────────────────── --}}
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
                                {{ $plato->total_vendidos }}
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
