{{-- Aqui uso la tecnologia Chart.js para mostrar la grafica --}}
@php
    $esPorHora = $diasRango <= 31;
    $chartLabels = [];
    $chartData = [];
    $chartColors = [];
    
    if ($esPorHora) {
        // Franjas horarias 10h - 23h
        foreach (range(10, 23) as $hora) {
            $chartLabels[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . 'h';
            $chartData[] = round($ventasPorHora[$hora] ?? 0, 2);
        }
    } else {
        // Días de semana
        $diasSemana = [1 => 'Dom', 2 => 'Lun', 3 => 'Mar', 4 => 'Mié', 5 => 'Jue', 6 => 'Vie', 7 => 'Sáb'];
        foreach ([2, 3, 4, 5, 6, 7, 1] as $dia) {
            $chartLabels[] = $diasSemana[$dia];
            $chartData[] = round($ventasPorHora[$dia] ?? 0, 2);
        }
    }
    
    $maxValor = max($chartData) ?: 1;
    $horaMax = array_search(max($chartData), $chartData);
    
    // Generar colores (naranja para pico, gris claro para resto)
    foreach ($chartData as $i => $valor) {
        $chartColors[] = ($i == $horaMax && $valor > 0) ? '#FF7A00' : 'rgba(255, 122, 0, 0.3)';
    }
@endphp

<div class="card border-0 shadow-sm rounded-4 h-100">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
            {{ $esPorHora ? 'Ventas por franja horaria' : 'Ventas por día de semana' }}
        </h6>
    </div>
    <div class="card-body px-4 pb-4 pt-3">
        @if(empty(array_filter($chartData)))
            <div class="text-center text-muted py-4 small">
                <i class="bi bi-bar-chart opacity-25 d-block fs-1 mb-2"></i>
                Sin datos para este período
            </div>
        @else
            <canvas id="ventasChart" height="250"></canvas>
            
            <script>
                window.chartData = {
                    labels: @json($chartLabels),
                    data: @json($chartData),
                    colors: @json($chartColors),
                    esPorHora: {{ $esPorHora ? 'true' : 'false' }}
                };
            </script>
        @endif
    </div>
</div>
