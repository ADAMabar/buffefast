<x-layouts.cliente-app>

    <div class="top-nav d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h6 mb-0 fw-bold">Mesa {{ $sesion->mesa->numero ?? '?' }}</h1>
            <span class="small text-muted">Buffet Libre</span>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill px-3 py-2"
                style="background-color: rgba(255,122,0,0.1); color: var(--primary-orange);">
                Ronda {{ $rondaActual }}
            </span>
            <div class="progress mt-2" style="height: 4px; width: 80px; float: right; background-color: #E5E7EB;">
                <div class="progress-bar" role="progressbar"
                    style="width: 20%; background-color: var(--primary-orange);" aria-valuenow="20" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="p-3">

        <div class="d-flex overflow-auto gap-2 mb-4 pb-1" style="scrollbar-width: none;">
            <button class="btn rounded-pill px-4 fw-medium flex-shrink-0"
                style="background-color: var(--primary-orange); color: white;">
                Todos
            </button>
            @foreach($categorias as $categoria)
                <button class="btn btn-light rounded-pill px-4 fw-medium flex-shrink-0 border">
                    {{ $categoria->nombre }}
                </button>
            @endforeach
            @if($categorias->isEmpty())
                <button class="btn btn-light rounded-pill px-4 fw-medium flex-shrink-0 border">Nigiris</button>
                <button class="btn btn-light rounded-pill px-4 fw-medium flex-shrink-0 border">Makis</button>
                <button class="btn btn-light rounded-pill px-4 fw-medium flex-shrink-0 border">Entrantes</button>
            @endif
        </div>
        <div class="row g-3">
            @forelse($platos as $plato)
                <div class="col-6">
                    <div class="card h-100 border-0 shadow-sm"
                        style="border-radius: 20px; overflow: hidden; background: #FAFAFA;">
                        <div
                            style="aspect-ratio: 1; background-color: #E5E7EB; background-image: url('{{ $plato->imagen ?? 'https://placehold.co/400x400/E5E7EB/A3A8B8?text=Sushi' }}'); background-size: cover; background-position: center;">
                        </div>

                        <div class="card-body p-2 px-3 pb-3 position-relative">
                            <h2 class="h6 fw-bold mb-1 text-truncate" style="font-size: 0.9rem;">{{ $plato->nombre }}</h2>
                            <p class="small text-muted mb-0 text-truncate" style="font-size: 0.75rem;">
                                {{ $plato->descripcion ?? 'Delicioso plato de sushi' }}
                            </p>

                            <button
                                class="btn position-absolute d-flex align-items-center justify-content-center p-0 shadow-sm"
                                style="bottom: 10px; right: 10px; width: 32px; height: 32px; border-radius: 50%; background-color: var(--primary-orange); color: white; border: none;">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                @for($i = 1; $i <= 6; $i++)
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm"
                            style="border-radius: 20px; overflow: hidden; background: #FAFAFA;">
                            <div
                                style="aspect-ratio: 1; background-color: #E5E7EB; background-image: url('https://placehold.co/400x400/eeeeee/A3A8B8?text=🍣'); background-size: cover; background-position: center;">
                            </div>
                            <div class="card-body p-2 px-3 pb-3 position-relative">
                                <h2 class="h6 fw-bold mb-1" style="font-size: 0.9rem;">Nigiri Salmón {{ $i }}</h2>
                                <p class="small text-muted mb-0" style="font-size: 0.75rem;">Arroz y salmón</p>
                                <button
                                    class="btn position-absolute d-flex align-items-center justify-content-center p-0 shadow-sm"
                                    style="bottom: 10px; right: 10px; width: 32px; height: 32px; border-radius: 50%; background-color: var(--primary-orange); color: white; border: none;">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>

    <div class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="bi bi-grid-fill"></i>
            <span>Menú</span>
        </a>
        <a href="#" class="nav-item position-relative">
            <i class="bi bi-basket"></i>
            <span>Pedido</span>
            <span class="position-absolute translate-middle p-1 rounded-circle"
                style="top: 5px; right: 15%; background-color: var(--primary-orange); border: 2px solid white;">
                <span class="visually-hidden">Nuevos items</span>
            </span>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-receipt"></i>
            <span>Cuenta</span>
        </a>
    </div>

</x-layouts.cliente-app>