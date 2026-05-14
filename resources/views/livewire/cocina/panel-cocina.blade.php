<div class="d-flex flex-column" style="min-height: 100vh;">
    <style>
        .kanban-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            align-items: start;
        }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background-color: #10B981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .kanban-col { 
            display: flex;
            flex-direction: 
            column; gap: 1rem; }
        .ticket-card {
            background: #FFFFFF; border-radius: 16px; border: 1px solid var(--border-light);
            padding: 1.25rem; transition: transform 0.2s, box-shadow 0.2s; position: relative;
        }
        .ticket-card:hover { 
           transform: translateY(-3px); 
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); 
        }
        .ticket-card.empty-state { background: transparent; border: 2px dashed #D1D5DB; box-shadow: none; }
        .ticket-card.empty-state:hover { transform: none; }
        .platos-list { margin: 0; padding: 0; list-style: none; font-size: 0.9rem; }
        .platos-list li { padding: 0.4rem 0; border-bottom: 1px dashed #E5E7EB; display: flex; align-items: start; gap: 8px; }
        .platos-list li:last-child { border-bottom: none; }
        .btn-orange { background-color: var(--primary-orange); color: white; border: none; transition: 0.2s; }
        .btn-orange:hover { background-color: #e66d00; color: white; }
    </style>

    {{-- CABECERA DE COCINA --}}
    <header class="bg-white border-bottom shadow-sm px-4 py-3 d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,122,0,0.1); border: 1px solid var(--primary-orange);">
                <i class="bi bi-fire" style="color: var(--primary-orange); font-size: 1.5rem;"></i>
            </div>
            <div>
                <h1 class="h5 mb-1 fw-bold text-dark">Panel de Cocina</h1>
                <div class="text-muted d-flex align-items-center gap-2" style="font-size: 0.85rem;" wire:ignore>
                    <i class="bi bi-clock"></i>
                    <span id="reloj">Cargando reloj...</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
          
            <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background-color: #ECFDF5; border: 1px solid #D1FAE5;">
                <div class="status-dot"></div>
                <span class="fw-medium text-success" style="font-size: 0.85rem;">
                    <span>En vivo</span>
                </span>
            </div>

            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger fw-medium rounded-3 px-3 d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </header>

    <div class="container-fluid px-4 flex-grow-1 pb-4" wire:poll.2s>
        <div class="kanban-grid">
            
            <div class="kanban-col">
                <div class="d-flex align-items-center mb-2 px-1">
                    <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: var(--primary-orange);"></div>
                    <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">Nuevos pedidos</h2>
                    <span class="badge rounded-pill bg-light text-dark border px-2">{{ count($this->pendientes) }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($this->pendientes as $pedido)
                        <div class="ticket-card" wire:key="pendiente-{{ $pedido->id }}">
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
                            
                            <button 
                                wire:click="actualizarEstado({{ $pedido->id }}, 'preparando')" 
                                wire:loading.attr="disabled"
                                class="btn btn-orange w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                                
                                <span wire:loading.remove wire:target="actualizarEstado({{ $pedido->id }}, 'preparando')">
                                    Empezar a preparar <i class="bi bi-arrow-right"></i>
                                </span>
                                <span wire:loading wire:target="actualizarEstado({{ $pedido->id }}, 'preparando')">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Procesando...
                                </span>
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

            {{-- Columna en preparación --}}
            <div class="kanban-col">
                <div class="d-flex align-items-center mb-2 px-1">
                    <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #F59E0B;"></div>
                    <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">En preparación</h2>
                    <span class="badge rounded-pill bg-light text-dark border px-2">{{ count($this->preparando) }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($this->preparando as $pedido)
                        <div class="ticket-card" style="border-left: 4px solid #F59E0B;" wire:key="preparando-{{ $pedido->id }}">
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
                            
                            <button 
                                wire:click="actualizarEstado({{ $pedido->id }}, 'servido')" 
                                wire:loading.attr="disabled"
                                class="btn btn-success w-100 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                                
                                <span wire:loading.remove wire:target="actualizarEstado({{ $pedido->id }}, 'servido')">
                                    <i class="bi bi-check2-circle fs-5"></i> Marcar como listo
                                </span>
                                <span wire:loading wire:target="actualizarEstado({{ $pedido->id }}, 'servido')">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Guardando...
                                </span>
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

          {{-- Columna Listos --}}
            <div class="kanban-col">
                <div class="d-flex align-items-center mb-2 px-1">
                    <div class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: {{ $verOcultos ? '#6B7280' : '#10B981' }};"></div>
                    <h2 class="h6 mb-0 fw-bold flex-grow-1 text-dark">
                        {{ $verOcultos ? 'Historial (Ocultos)' : 'Listos / servidos' }}
                    </h2>
                    
                    <div class="d-flex gap-2">
                        {{-- BOTÓN HISTORIAL --}}
                        <button wire:click="toggleVerOcultos" class="btn btn-sm {{ $verOcultos ? 'btn-dark' : 'btn-light border' }} rounded-pill d-flex align-items-center gap-1 shadow-sm" style="font-size: 0.75rem;">
                            <i class="bi {{ $verOcultos ? 'bi-eye-slash' : 'bi-clock-history' }}"></i> 
                            {{ $verOcultos ? 'Cerrar' : 'Historial' }}
                        </button>

                        {{-- BOTÓN para LIMPIAR --}}
                        @if(!$verOcultos && count($this->servidos) > 0)
                            <button wire:click="limpiarListos" class="btn btn-sm btn-light border rounded-pill d-flex align-items-center gap-1 text-muted transition" style="font-size: 0.75rem;">
                                <i class="bi bi-eraser"></i> Limpiar
                            </button>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($this->servidos as $pedido)
                        <div class="ticket-card" style="opacity: {{ $verOcultos ? '1' : '0.85' }}; {{ $verOcultos ? 'border: 1px dashed #6B7280; background-color: #F9FAFB;' : '' }}" wire:key="servido-{{ $pedido->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill border px-2 py-1 d-flex align-items-center gap-1" style="background-color: #F0FDF4; color: #166534;">
                                    <i class="bi bi-grid-3x3-gap-fill opacity-75"></i> Mesa {{ $pedido->sesion->mesa->numero ?? '?' }}
                                </span>
                                
                                @if($verOcultos)
                                    <button wire:click="restaurarPedido({{ $pedido->id }})" class="btn btn-xs btn-outline-primary py-0 px-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        RESTAURAR
                                    </button>
                                @else
                                    <span class="fw-bold text-success" style="font-size: 0.8rem;">
                                        <i class="bi bi-check-lg"></i> Listo
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center text-muted fw-medium" style="font-size: 0.85rem;">
                                <i class="bi bi-list-ul me-2"></i> {{ $pedido->platos->sum('pivot.cantidad') }} platos
                                <span class="mx-2">•</span>
                                Ronda {{ $pedido->ronda }}
                            </div>
                        </div>
                    @empty
                        <div class="ticket-card empty-state text-center py-5">
                            <i class="bi {{ $verOcultos ? 'bi-archive' : 'bi-clipboard-check' }} fs-1 text-muted opacity-50 mb-2 d-block"></i>
                            <div class="text-muted fw-medium">{{ $verOcultos ? 'No hay nada archivado' : 'Historial vacío' }}</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            function actualizarReloj() {
                const el = document.getElementById('reloj');
                if (!el) return;
                const ahora = new Date();
                el.textContent = ahora.toLocaleTimeString();
            }
            setInterval(actualizarReloj, 1000);
            actualizarReloj(); 
        });
    </script>
</div>