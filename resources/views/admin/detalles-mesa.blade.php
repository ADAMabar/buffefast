<x-layouts.admin>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.mesas') }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Volver al Salón
            </a>
            <h1 class="h3 fw-bold mb-0">Mesa #{{ $mesa->numero }}</h1>
            <p class="text-muted mb-0">
                Código de acceso: <span class="fw-bold text-dark"
                    style="letter-spacing: 0.1rem;">{{ $sesionActiva->codigo }}</span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <form action="{{ route('admin.mesa.desocupar', $mesa->id) }}" method="POST">
                @csrf
                <button class="btn btn-primary fw-bold shadow-sm rounded-pill px-4 py-2">
                    <i class="bi bi-printer me-1"></i> Ticket
                </button>
            </form>

            <form action="{{ route('admin.mesa.desocupar', $mesa->id) }}" method="POST">
                @csrf
                <button class="btn btn-warning fw-bold shadow-sm rounded-pill px-4 py-2">
                    <i class="bi bi-door-closed me-1"></i> Cerrar
                </button>
            </form>


            <button type="button" class="btn btn-success fw-bold px-4 fs-5" data-bs-toggle="modal"
                data-bs-target="#modalCobrar">
                <i class="bi bi-cash-coin me-2"></i>Cobrar Mesa
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 bg-primary text-white shadow-sm rounded-4 h-100 p-2">
                <div class="card-body">
                    <h6 class="text-white-50 fw-bold mb-1"><i class="bi bi-arrow-repeat me-1"></i> numero de personas
                    </h6>
                    <h2 class="fw-bold mb-0">{{ $sesionActiva->clientes()->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 bg-dark text-white shadow-sm rounded-4 h-100 p-2">
                <div class="card-body">
                    <h6 class="text-white-50 fw-bold mb-1"><i class="bi bi-basket-fill me-1"></i> Total Platos</h6>
                    <h2 class="fw-bold mb-0">
                        {{ $pedidos->sum(function ($pedido) {
    return $pedido->platos->sum('pivot.cantidad'); }) }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 bg-dark text-white shadow-sm rounded-4 h-100 p-2">
                <div class="card-body">
                    <h6 class="text-white-50 fw-bold mb-1"><i class="bi bi-currency-euro"></i>Total extras no
                        incluidos en el buffet
                    </h6>
                    <h2 class="fw-bold mb-0">{{ $totalMesa}}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 bg-info text-dark shadow-sm rounded-4 h-100 p-2">
                <div class="card-body">
                    <h6 class="text-dark-50 fw-bold mb-1 opacity-75"><i class="bi bi-stopwatch-fill me-1"></i> Tiempo en
                        mesa</h6>
                    <h2 class="fw-bold mb-0">{{ $sesionActiva->created_at->diffForHumans() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom pt-3 pb-2">
            <h5 class="fw-bold mb-0 text-orange"><i class="bi bi-list-check me-2"></i>Historial de Comandas</h5>
        </div>

        <div class="card-body p-4 bg-light rounded-bottom-4">
            @if($pedidos->isEmpty())
                <div class="text-center py-5">
                    <div class="bg-white rounded-circle d-inline-flex justify-content-center align-items-center shadow-sm mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-journal-x text-muted fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Sin pedidos todavía</h5>
                    <p class="text-muted">Esta mesa aún no ha pedido nada.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($pedidosAgrupados as $nombreCliente => $datosCliente)

                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-3 text-primary me-2"></i>
                                    <h4 class="fw-bold mb-0">{{ $nombreCliente }}</h4>
                                    <span class="badge bg-secondary ms-3 rounded-pill">
                                        {{ $datosCliente['cantidad_rondas'] }} rondas
                                    </span>
                                </div>

                                <div
                                    class="bg-success bg-opacity-10 px-3 py-2 rounded-3 border border-success border-opacity-25">
                                    <span class="text-success small fw-bold text-uppercase me-1">Total:</span>
                                    <span
                                        class="fs-4 fw-bold text-success">{{ number_format($datosCliente['total_euros'], 2, ',', '.') }}
                                        €</span>
                                </div>
                            </div>

                            <div class="row g-4 ps-3 border-start border-3 border-primary border-opacity-25">

                                @foreach($datosCliente['historial_pedidos'] as $pedido)
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div
                                            class="card h-100 border-2 shadow-sm @if($pedido->estado == 'pendiente') border-warning @else border-success opacity-75 @endif rounded-4">
                                            <div
                                                class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pt-3">
                                                <span class="fw-bold fs-5">Ronda #{{ $pedido->ronda }}</span>
                                                <span
                                                    class="badge rounded-pill @if($pedido->estado == 'pendiente') bg-warning text-dark @else bg-success @endif">
                                                    {{ strtoupper($pedido->estado) }}
                                                </span>
                                            </div>
                                            <div class="card-body p-3">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($pedido->platos as $plato)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center px-2 py-2 border-0 bg-transparent">
                                                            <span>
                                                                <span
                                                                    class="fw-bold text-primary me-2">{{ $plato->pivot->cantidad }}x</span>
                                                                {{ $plato->nombre }}
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="card-footer bg-white border-top-0 pb-3 px-3 text-end text-muted small">
                                                <i class="bi bi-clock me-1"></i> {{ $pedido->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('admin.modals.cobrar-mesa')
</x-layouts.admin>