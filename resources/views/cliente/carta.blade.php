<x-layouts.cliente-app>

    <x-header-carta :sesion="$sesion" :rondaActual="$rondaActual" />

    <div class="p-3 px-sm-4 px-lg-5">
        <!-- Categorías: scroll en móvil, wrap en desktop -->
        <div class="d-flex overflow-auto hide-scrollbar gap-2 mb-4 pb-1 categorias-wrapper">
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

        <!-- Grid responsive: 2 cols móvil, 3 tablet, 4 desktop, 5 XL -->
        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-3 g-lg-4 mb-5 pb-5">
            @forelse($platos as $plato)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-sushi">
                        
                        <div class="ratio ratio-1x1 bg-light rounded-top-4 overflow-hidden">
                            <img src="{{ $plato->imagen ? asset('storage/' . $plato->imagen) : 'https://placehold.co/400x400/eeeeee/A3A8B8?text=🍣' }}"
                                 class="object-fit-cover w-100 h-100" 
                                 alt="{{ $plato->nombre }}"
                                 loading="lazy">
                        </div>

                        <div class="card-body p-2 px-3 pb-3 position-relative">
                            <h2 class="h6 fw-bold mb-1 text-truncate" style="font-size: 0.9rem;">{{ $plato->nombre }}</h2>
                            <p class="small text-muted mb-0 text-truncate" style="font-size: 0.75rem;">
                                {{ $plato->descripcion ?? 'Delicioso sushi' }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-success rounded-pill">{{ $plato->precio }} €</span>
                            </div>
                        
                            @php
                                $cantidadEnCarrito = $carritoActual[$plato->id]['cantidad'] ?? 0;
                            @endphp

                            <span class="badge bg-success mt-2" 
                                id="badge-cantidad-{{ $plato->id }}" 
                                style="{{ $cantidadEnCarrito > 0 ? '' : 'display: none;' }}">
                                En el carrito: <span class="cantidad-num">{{ $cantidadEnCarrito }}</span>
                            </span>

                            <form action="{{ route('cliente.carrito.add', $plato->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="plato_id" value="{{ $plato->id }}">
                                <button
                                    class="btn position-absolute d-flex align-items-center justify-content-center p-0 shadow-sm add-to-cart-btn"
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

    <x-nav-bottom active="carta" />

</x-layouts.cliente-app>