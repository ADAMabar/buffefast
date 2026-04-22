{{-- ── DESGLOSE DE MÉTODOS DE PAGO ───────────────────────────────────── --}}
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
