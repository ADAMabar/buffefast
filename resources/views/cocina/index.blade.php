<x-layouts.app>
    <div class="row g-4">

        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center mb-3 px-2">
                <div class="rounded-circle me-2"
                    style="width: 10px; height: 10px; background-color: var(--primary-orange);"></div>
                <h2 class="h6 mb-0 fw-semibold">Nuevos Pedidos ({{ $pendientes->count() }})</h2>
            </div>

            <div class="kanban-col d-flex flex-column gap-3 pb-4">
                @forelse($pendientes as $pedido)
                    <div class="bento-card border-start border-4"
                        style="border-left-color: var(--primary-orange) !important;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-light text-dark border mb-1">Mesa
                                    {{ $pedido->sesion->mesa->numero ?? '?' }}</span>
                                <span class="badge"
                                    style="background-color: rgba(255,122,0,0.1); color: var(--primary-orange);">Ronda
                                    {{ $pedido->ronda }}</span>
                            </div>
                            <span class="small text-muted">{{ $pedido->created_at->diffForHumans() }}</span>
                        </div>

                        <ul class="list-unstyled mb-4">
                            @foreach($pedido->detalles as $detalle)
                                <li class="mb-2 d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">{{ $detalle->cantidad }}x {{ $detalle->plato->nombre }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <form action="{{ route('cocina.pedido.estado', $pedido->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="preparando">
                            <button type="submit" class="btn w-100 rounded-pill fw-medium"
                                style="background-color: var(--primary-orange); color: white;">
                                Empezar a Preparar
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">No hay pedidos nuevos.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center mb-3 px-2">
                <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #F59E0B;"></div>
                <h2 class="h6 mb-0 fw-semibold">En Preparación ({{ $preparando->count() }})</h2>
            </div>

            <div class="kanban-col d-flex flex-column gap-3 pb-4">
                @forelse($preparando as $pedido)
                    <div class="bento-card border-start border-4" style="border-left-color: #F59E0B !important;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-light text-dark border mb-1">Mesa
                                    {{ $pedido->sesion->mesa->numero ?? '?' }}</span>
                                <span class="badge text-dark border bg-white">Ronda {{ $pedido->ronda }}</span>
                            </div>
                            <span class="small text-muted">{{ $pedido->updated_at->diffForHumans() }}</span>
                        </div>

                        <ul class="list-unstyled mb-4 text-muted">
                            @foreach($pedido->detalles as $detalle)
                                <li class="mb-2"><span class="fw-medium text-dark">{{ $detalle->cantidad }}x
                                        {{ $detalle->plato->nombre }}</span></li>
                            @endforeach
                        </ul>

                        <form action="{{ route('cocina.pedido.estado', $pedido->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="servido">
                            <button type="submit" class="btn btn-light w-100 rounded-pill border fw-medium shadow-sm">
                                Marcar como Listo ✔️
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">No hay pedidos en preparación.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center mb-3 px-2">
                <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #10B981;"></div>
                <h2 class="h6 mb-0 fw-semibold">Listos / Servidos</h2>
            </div>

            <div class="kanban-col d-flex flex-column gap-3 pb-4 opacity-75">
                @forelse($servidos as $pedido)
                    <div class="bento-card" style="background-color: #F9FAFB;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-white text-dark border mb-1">Mesa
                                    {{ $pedido->sesion->mesa->numero ?? '?' }}</span>
                            </div>
                            <span class="small text-success fw-medium">Completado</span>
                        </div>
                        <p class="small text-muted mb-0">{{ $pedido->detalles->sum('cantidad') }} platos entregados (Ronda
                            {{ $pedido->ronda }})</p>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">Historial vacío.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-layouts.app>