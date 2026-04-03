<x-layouts.cliente>
    <x-layouts.cliente-app>

        <div class="top-nav d-flex justify-content-between align-items-center p-3 pb-2 bg-white shadow-sm sticky-top">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('cliente.carta') }}" class="text-dark text-decoration-none">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <div>
                <h1 class="h5 mb-0 fw-bold">Tu Pedido</h1>
                <span class="small text-muted">Mesa {{ $sesion->mesa->numero ?? '?' }}</span>
            </div>
        </div>
    </div>

        <div class="p-3 mb-5 pb-5">

        <div class="d-flex gap-2 mb-4">
        <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill fw-bold shadow-sm btn-oscurecer" style="transition: all 0.3s ease;">
            <i class="bi bi-bell-fill text-warning me-1"></i> Llamar Camarero
        </button>
  
        <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill fw-bold shadow-sm btn-oscurecer" style="transition: all 0.3s ease;">
            <i class="bi bi-receipt me-1"></i> Pedir la Cuenta
        </button>
   
</div>

            <h4 class="fw-bold mb-3">Historial de Pedidos</h4>

            @if($pedidos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Aún no has pedido nada.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($pedidos as $pedido)
                        <div class="card border-0 shadow-sm rounded-4">
                            <div
                                class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-orange">Ronda {{ $pedido->ronda }}</h5>

                                @if($pedido->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark rounded-pill">En cocina 🍳</span>
                                @elseif($pedido->estado == 'servido')
                                    <span class="badge bg-success rounded-pill">Servido ✅</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($pedido->estado) }}</span>
                                @endif
                            </div>

                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach($pedido->platos as $plato)
                                        <li
                                            class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-1">
                                            <span>
                                                <span class="fw-bold me-2">{{ $plato->pivot->cantidad }}x</span>
                                                {{ $plato->nombre }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-end mt-2">
                                    <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bottom-nav bg-white border-top fixed-bottom d-flex justify-content-around py-2 pb-3 shadow-lg"
            style="z-index: 1030;">
            <a href="{{ route('cliente.carta') }}"
                class="nav-item text-decoration-none text-muted d-flex flex-column align-items-center">
                <i class="bi bi-grid fs-4"></i>
                <span class="small fw-medium" style="font-size: 0.75rem;">Menú</span>
            </a>

            <a href="{{ route('cliente.carrito') }}"
                class="nav-item text-decoration-none text-muted d-flex flex-column align-items-center">
                <i class="bi bi-basket fs-4"></i>
                <span class="small fw-medium" style="font-size: 0.75rem;">Pedido</span>
            </a>

            <a href="{{ route('cliente.cuenta') }}"
                class="nav-item text-decoration-none text-orange d-flex flex-column align-items-center">
                <i class="bi bi-receipt-cutoff fs-4"></i> <span class="small fw-bold"
                    style="font-size: 0.75rem;">Cuenta</span>
            </a>
        </div>

    </x-layouts.cliente-app>
</x-layouts.cliente>