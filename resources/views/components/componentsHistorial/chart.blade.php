{{-- ── GRÁFICA POR HORA / DÍA SEMANA ─────────────────────────────────── --}}
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

<div class="card border-0 shadow-sm rounded-4 h-100">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
            {{ $diasRango <= 31 ? 'Ventas por franja horaria' : 'Ventas por día de semana' }}
        </h6>
    </div>
    <div class="card-body px-4 pb-4 pt-3">
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
