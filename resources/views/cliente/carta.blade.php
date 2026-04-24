<x-layouts.cliente-app>

    <x-header-carta :sesion="$sesion" :rondaActual="$rondaActual" />

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
                            <br>


                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success rounded-pill">{{ $plato->precio }} €</span>
                            </div>
                        
                            @php
                                $cantidadEnCarrito = $carritoActual[$plato->id]['cantidad'] ?? 0;
                            @endphp

                           
                            @if($cantidadEnCarrito > 0)
                                <span class="badge bg-success">
                                    En el carrito: {{ $cantidadEnCarrito }}
                                </span>
                            @endif
                            
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

    <x-nav-bottom active="carta" />

</x-layouts.cliente-app>