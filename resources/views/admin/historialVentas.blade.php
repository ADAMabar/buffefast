<x-layouts.admin>
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Historial de Ventas</h1>
            <p class="text-muted mb-0 small">
                {{ $fechaDesde->format('d/m/Y') }} — {{ $fechaHasta->format('d/m/Y') }}
                · Snapshots inmutables del momento del cobro
            </p>
        </div>
        <a href="{{ route('admin.historial', array_merge(request()->all(), ['export' => 'csv'])) }}"
            class="btn btn-outline-secondary btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-download me-1"></i>CSV
        </a>
    </div>
    @include('components.componentsHistorial.filters')
    @include('components.componentsHistorial.metrics-cards')

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-7 anim" style="animation-delay:.25s">
            @include('components.componentsHistorial.chart')
        </div>
        <div class="col-12 col-lg-5 anim" style="animation-delay:.3s">
            <div class="row g-3 h-100">
                @include('components.componentsHistorial.payment-methods')
                @include('components.componentsHistorial.top-platos')
            </div>
        </div>
    </div>

    @include('components.componentsHistorial.tickets-table')
    @include('admin.modals.detalle-ticket')
    @include('admin.modals.anular-ticket')
    @include('components.componentsHistorial.scripts')

</x-layouts.admin>