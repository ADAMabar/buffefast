<div class="modal fade" id="modalCobrar" tabindex="-1" aria-labelledby="modalCobrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCobrarLabel">
                    <i class="bi bi-cash-coin me-2"></i>Cobrar Mesa #{{ $mesa->numero }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.mesa.cobrar', $mesa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    {{-- SECCIÓN 1: MÉTODOS DE PAGO DINÁMICOS --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">MÉTODO DE PAGO</label>
                        <div class="d-flex gap-3">
                            @php
                                $metodosDisponibles = [
                                    'efectivo' => ['icono' => 'bi-cash text-success', 'label' => 'Efectivo', 'default' => 'true'],
                                    'tarjeta'  => ['icono' => 'bi-credit-card text-info', 'label' => 'Tarjeta', 'default' => 'true'],
                                    'bizum'    => ['icono' => 'bi-phone text-primary', 'label' => 'Bizum', 'default' => 'false']
                                ];
                                $primerMetodo = true;
                            @endphp

                            @foreach($metodosDisponibles as $key => $datos)
                                @if(configuracion('pago_'.$key, $datos['default']) === 'true')
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metodo_pago" id="pago{{ ucfirst($key) }}" value="{{ $key }}" {{ $primerMetodo ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="pago{{ ucfirst($key) }}">
                                            <i class="bi {{ $datos['icono'] }}"></i> {{ $datos['label'] }}
                                        </label>
                                    </div>
                                    @php $primerMetodo = false; @endphp
                                @endif
                            @endforeach
                        </div>
                        
                        @if($primerMetodo)
                            <div class="text-danger small mt-1">Activa algún método de pago en Configuración.</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="caja_id" class="form-label fw-bold text-muted">CAJA DE COBRO</label>
                        <select class="form-select" id="caja_id" name="caja_id" required>
                            <option value="" disabled selected>Selecciona una caja...</option>
                            @foreach($cajas as $caja)
                                <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-warning mb-0 border-warning border-opacity-25 bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Total a cobrar:</strong> {{ number_format($totalMesa, 2, ',', '.') }} €
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">
                            (IVA incl. {{ configuracion('porcentaje_impuestos', '10') }}%)
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>Confirmar Cobro
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>