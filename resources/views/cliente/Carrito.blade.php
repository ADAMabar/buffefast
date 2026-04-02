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
        @if(empty($carrito))
            <div class="text-center py-5 mt-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="bi bi-basket text-muted fs-1"></i>
                </div>
                <h4 class="fw-bold">Tu pedido está vacío</h4>
                <p class="text-muted small mb-4">Aún no has añadido ningún plato de la carta.</p>
                <a href="{{ route('cliente.carta') }}"
                    class="btn bg-orange text-white rounded-pill px-4 py-2 fw-bold shadow-sm">
                    Ver la carta
                </a>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($carrito as $id => $item)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-2 d-flex align-items-center">
                            <img src="{{ $item['imagen'] ?? 'https://placehold.co/100x100/eeeeee/A3A8B8?text=🍣' }}"
                                class="rounded-3 object-fit-cover" style="width: 70px; height: 70px;"
                                alt="{{ $item['nombre'] }}">

                            <div class="ms-3 flex-grow-1">
                                <h3 class="h6 fw-bold mb-1">{{ $item['nombre'] }}</h3>
                                <span class="badge bg-light text-dark border">
                                    Cantidad: {{ $item['cantidad'] }}
                                </span>
                            </div>

                            <div class="pe-2">
                                <form action="{{ route('cliente.carrito.remove', $id) }}" method="POST">
                                    @csrf <button type="submit" class="btn btn-sm text-danger border-0">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="position-fixed w-100 p-3" style="bottom: 70px; left: 0; z-index: 1020;">
                <form action="#" method="POST">
                    @csrf
                    <button type="submit"
                        class="btn bg-orange text-blank w-100 rounded-pill py-3 fw-bold shadow-lg d-flex justify-content-between align-items-center px-4">
                        <span>Confirmar a Cocina</span>
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </button>
                </form>
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
            class="nav-item text-decoration-none text-orange d-flex flex-column align-items-center">
            <i class="bi bi-basket-fill fs-4"></i> <span class="small fw-bold" style="font-size: 0.75rem;">Pedido</span>
        </a>

        <a href="{{ route('cliente.cuenta') }}"
            class="nav-item text-decoration-none text-muted d-flex flex-column align-items-center">
            <i class="bi bi-receipt fs-4"></i>
            <span class="small fw-medium" style="font-size: 0.75rem;">Cuenta</span>
        </a>
    </div>

</x-layouts.cliente-app>