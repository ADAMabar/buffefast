<x-layouts.cliente-app>

    <div class="top-nav d-flex justify-content-between align-items-center p-3 pb-0">
        <div>
            <h1 class="h5 mb-0 fw-bold">Mesa {{ $sesion->mesa->numero ?? '?' }}</h1>
            <span class="small text-muted">Buffefast</span>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill fw-bold"
                style="background-color: var(--orange-soft); color: var(--primary-orange); padding: 0.2rem 0.8rem;">

                <span style="position: relative; top: -2px;">Ronda {{ $rondaActual }}</span>
            </span>
            <div class="progress mt-2 bg-light" style="height: 5px; width: 80px; float: right;">
                <div class="progress-bar bg-orange" style="width: 20%;"></div>
            </div>
        </div>

        <a href="{{ route('cliente.logout') }}" class="btn btn-outline-danger btn-sm rounded-pill px-2">Cerrar
            Sesión</a>

    </div>



    <div class="p-3">

        <div class="d-flex overflow-auto hide-scrollbar gap-2 mb-4 pb-1">

            <a href="{{ route('cliente.carta') }}"
                class="btn {{ !request('categoria') ? 'bg-orange text-black' : 'btn-light text-dark border' }} rounded-pill px-4 fw-medium flex-shrink-0">
                Todos
            </a>

            @foreach($categorias as $categoria)
                <a href="{{ route('cliente.carta', ['categoria' => $categoria->id]) }}"
                    class="btn {{ request('categoria') == $categoria->id ? 'bg-orange text-black' : 'btn-light text-dark border' }} rounded-pill px-4 fw-medium flex-shrink-0 shadow-sm">
                    {{ $categoria->nombre }}
                </a>
            @endforeach

        </div>

        <div class="row row-cols-2 g-3 mb-5 pb-5">
            @forelse($platos as $plato)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-sushi">
                        <div class="ratio ratio-1x1 bg-light rounded-top-4 overflow-hidden">
                            <img src="{{ $plato->imagen ?? 'https://placehold.co/400x400/eeeeee/A3A8B8?text=🍣' }}"
                                class="object-fit-cover w-100 h-100" alt="{{ $plato->nombre }}">
                        </div>

                        <div class="card-body p-2 px-3 pb-3 position-relative">
                            <h2 class="h6 fw-bold mb-1 text-truncate" style="font-size: 0.9rem;">{{ $plato->nombre }}</h2>
                            <p class="small text-muted mb-0 text-truncate" style="font-size: 0.75rem;">
                                {{ $plato->descripcion ?? 'Delicioso sushi' }}
                            </p>

                            <form action="{{ route('cliente.carrito.add', $plato->id) }}" method="POST">
                                @csrf
                                <button
                                    class="btn position-absolute d-flex align-items-center justify-content-center p-0 shadow-sm"
                                    style="bottom: 10px; right: 10px; width: 32px; height: 32px; border-radius: 50%; background-color: var(--primary-orange); color: white; border: none;">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-emoji-frown fs-1"></i>
                    <p class="mt-2">No hay platos disponibles ahora mismo.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bottom-nav bg-white border-top fixed-bottom d-flex justify-content-around py-2 pb-3 shadow-lg">
        <a href="{{ route('cliente.carta') }}"
            class="nav-item text-decoration-none text-orange d-flex flex-column align-items-center">
            <i class="bi bi-grid-fill fs-4"></i>
            <span class="small fw-bold" style="font-size: 0.75rem;">Menú</span>
        </a>

        <a href="{{ route('cliente.carrito') }}"
            class="nav-item text-decoration-none text-muted d-flex flex-column align-items-center position-relative">
            <i class="bi bi-basket fs-4"></i>
            <span class="small fw-medium" style="font-size: 0.75rem;">Pedido</span>
            @if(session('carrito_count', 0) > 0)
                <span class="position-absolute translate-middle p-1 rounded-circle bg-orange border border-2 border-white"
                    style="top: 8px; right: 20%;">
                    <span class="visually-hidden">Nuevos items</span>
                </span>
            @endif
        </a>

        <a href="{{ route('cliente.cuenta') }}"
            class="nav-item text-decoration-none text-muted d-flex flex-column align-items-center">
            <i class="bi bi-receipt fs-4"></i>
            <span class="small fw-medium" style="font-size: 0.75rem;">Cuenta</span>
        </a>
    </div>
    <script>
        const btnCerrarSesion = document.querySelector('.btn-outline-danger');

        btnCerrarSesion.addEventListener('click', (event) => {
            const respuesta = confirm('¿Estás seguro de que quieres cerrar sesión?');

            if (respuesta) {
                alert('Cerrando sesión...');
            } else {
                event.preventDefault();
                console.log('Cierre de sesión cancelado');
            }
        });
    </script>
</x-layouts.cliente-app>