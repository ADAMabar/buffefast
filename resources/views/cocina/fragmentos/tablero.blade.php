<style>
    /* Estilos Bento para los tickets de cocina */
    .kanban-col {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .ticket-card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid var(--border-light);
        padding: 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }
    
    .ticket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .ticket-card.empty-state {
        background: transparent;
        border: 2px dashed #D1D5DB;
        box-shadow: none;
    }
    
    .ticket-card.empty-state:hover {
        transform: none;
    }

    .platos-list {
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 0.9rem;
    }
    
    .platos-list li {
        padding: 0.4rem 0;
        border-bottom: 1px dashed #E5E7EB;
        display: flex;
        align-items: start;
        gap: 8px;
    }
    
    .platos-list li:last-child {
        border-bottom: none;
    }

    .btn-orange {
        background-color: var(--primary-orange);
        color: white;
        border: none;
        transition: 0.2s;
    }
    
    .btn-orange:hover {
        background-color: #e66d00;
        color: white;
    }
</style>

<div class="kanban-col">
    <div class="d-flex align-items-center mb-2 px-1">
        <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: var(--primary-orange);"></div>
        <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">Nuevos pedidos</h2>
        <span class="badge rounded-pill bg-light text-dark border px-2">{{ $pendientes->count() }}</span>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($pendientes as $pedido)
            <div class="ticket-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex gap-2">
                        <span class="badge rounded-pill bg-light text-dark border px-2 py-1 d-flex align-items-center gap-1">
                            <i class="bi bi-grid-3x3-gap-fill text-muted"></i> Mesa {{ $pedido->sesion->mesa->numero ?? '?' }}
                        </span>
                        <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(255,122,0,0.1); color: var(--primary-orange);">
                            R{{ $pedido->ronda }}
                        </span>
                    </div>
                    <span class="text-muted fw-medium" style="font-size: 0.75rem;">
                        <i class="bi bi-clock me-1"></i>{{ $pedido->created_at->diffForHumans() }}
                    </span>
                </div>
                
                <ul class="platos-list mb-4">
                    @foreach($pedido->platos as $plato)
                        <li>
                            <span class="fw-bold" style="color: var(--primary-orange); min-width: 20px;">{{ $plato->pivot->cantidad }}×</span> 
                            <span class="text-dark fw-medium">{{ $plato->nombre }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <button class="btn btn-orange w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2" onclick="actualizarEstado({{ $pedido->id }}, 'preparando', this)">
                    Empezar a preparar <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        @empty
            <div class="ticket-card empty-state text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted opacity-50 mb-2 d-block"></i>
                <div class="text-muted fw-medium">Sin pedidos nuevos</div>
            </div>
        @endforelse
    </div>
</div>

<div class="kanban-col">
    <div class="d-flex align-items-center mb-2 px-1">
        <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #F59E0B;"></div>
        <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">En preparación</h2>
        <span class="badge rounded-pill bg-light text-dark border px-2">{{ $preparando->count() }}</span>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($preparando as $pedido)
            <div class="ticket-card" style="border-left: 4px solid #F59E0B;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex gap-2">
                        <span class="badge rounded-pill bg-light text-dark border px-2 py-1 d-flex align-items-center gap-1">
                            <i class="bi bi-grid-3x3-gap-fill text-muted"></i> Mesa {{ $pedido->sesion->mesa->numero ?? '?' }}
                        </span>
                        <span class="badge rounded-pill px-2 py-1" style="background-color: rgba(245,158,11,0.1); color: #B45309;">
                            R{{ $pedido->ronda }}
                        </span>
                    </div>
                    <span class="text-muted fw-medium" style="font-size: 0.75rem;">
                        <i class="bi bi-fire text-warning me-1"></i>{{ $pedido->updated_at->diffForHumans() }}
                    </span>
                </div>
                
                <ul class="platos-list mb-4">
                    @foreach($pedido->platos as $plato)
                        <li>
                            <span class="fw-bold" style="color: #F59E0B; min-width: 20px;">{{ $plato->pivot->cantidad }}×</span> 
                            <span class="text-dark fw-medium">{{ $plato->nombre }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <button class="btn btn-success w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2" onclick="actualizarEstado({{ $pedido->id }}, 'servido', this)">
                    <i class="bi bi-check2-circle fs-5"></i> Marcar como listo
                </button>
            </div>
        @empty
            <div class="ticket-card empty-state text-center py-5">
                <i class="bi bi-fire fs-1 text-muted opacity-50 mb-2 d-block"></i>
                <div class="text-muted fw-medium">Nada en preparación</div>
            </div>
        @endforelse
    </div>
</div>

{{-- COLUMNA: Listos --}}
<div class="kanban-col">
    <div class="d-flex align-items-center mb-2 px-1">
        <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #10B981;"></div>
        <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">Listos / servidos</h2>
        <span class="badge rounded-pill bg-light text-dark border px-2 me-2" id="badge-listos">{{ $servidos->count() }}</span>
        
        {{-- BOTÓN DE LIMPIAR --}}
        <button onclick="limpiarListos()" class="btn btn-sm btn-light border rounded-pill d-flex align-items-center gap-1 text-muted transition" style="font-size: 0.75rem;">
            <i class="bi bi-eraser"></i> Limpiar
        </button>
    </div>

    <div class="d-flex flex-column gap-3" id="contenedor-listos">
        @forelse($servidos as $pedido)
            {{-- Añadimos la clase 'listo-card' y el 'data-id' --}}
            <div class="ticket-card listo-card" data-id="{{ $pedido->id }}" style="opacity: 0.85;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge rounded-pill border border-success-subtle px-2 py-1 d-flex align-items-center gap-1" style="background-color: #F0FDF4; color: #166534;">
                        <i class="bi bi-grid-3x3-gap-fill opacity-75"></i> Mesa {{ $pedido->sesion->mesa->numero ?? '?' }}
                    </span>
                    <span class="fw-bold text-success" style="font-size: 0.8rem;">
                        <i class="bi bi-check-lg"></i> Listo
                    </span>
                </div>
                
                <div class="d-flex align-items-center text-muted fw-medium" style="font-size: 0.85rem;">
                    <i class="bi bi-list-ul me-2"></i> {{ $pedido->platos->sum('pivot.cantidad') }} platos
                    <span class="mx-2">•</span>
                    Ronda {{ $pedido->ronda }}
                </div>
            </div>
        @empty
            <div class="ticket-card empty-state text-center py-5">
                <i class="bi bi-clipboard-check fs-1 text-muted opacity-50 mb-2 d-block"></i>
                <div class="text-muted fw-medium">Historial vacío</div>
            </div>
        @endforelse
    </div>
</div>