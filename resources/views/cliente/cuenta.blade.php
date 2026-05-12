<x-layouts.cliente-app>
    
<x-header-carta :sesion="$sesion" :rondaActual="$rondaActual" />

    <div class="p-3 mb-5 pb-5">
         {{-- Notificación de penalización --}}
        @if(configuracion('penalizacion_activa') === 'true')
        <div id="penalizacionAlert" class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm mb-3" 
             style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); color: #92400E; display: none;">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0"></i>
                <div>
                    <strong class="d-block mb-1">Aviso importante</strong>
                    <span class="small">
                        {{ str_replace('{precio}', configuracion('precio_penalizacion', '2.00') . '€', configuracion('mensaje_penalizacion', 'Cargo de {precio}€ por plato sobrante.')) }}
                    </span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar" onclick="dismissPenalizacion()"></button>
        </div>
        @endif

        <div class="d-flex gap-2 mb-4">
        <form action="{{ route('cliente.cuenta.pedir') }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill fw-bold shadow-sm btn-oscurecer">
                <i class="bi bi-receipt me-1"></i> Pedir la Cuenta
            </button>
        </form>
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
                        <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
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
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 py-1">
                                        <span>
                                            <span class="fw-bold me-2">{{ $plato->pivot->cantidad }}x</span>
                                            {{ $plato->nombre }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success rounded-pill">{{ $plato->precio }} €</span>
                            </div>
                            <div class="text-end mt-2">
                                <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <x-nav-bottom active="cuenta" />

        <script>
        // Mostro  o oculto alerta de penalización según localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const alertDismissed = localStorage.getItem('penalizacionAlertDismissed');
            const alertEl = document.getElementById('penalizacionAlert');
            
            // hare que solo se vea una vez en toda la sesion para que no moleste
            if (alertEl && !alertDismissed) {
                alertEl.style.display = 'block';
            }
        });

        function dismissPenalizacion() {
            localStorage.setItem('penalizacionAlertDismissed', 'true');
        }
    </script>


</x-layouts.cliente-app>