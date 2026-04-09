<div class="modal fade" id="modalPlatosOcultos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-eye-slash-fill me-2 text-danger"></i>Platos Ocultos / Agotados
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0 bg-light">
                @php $hayOcultos = false; @endphp

                @foreach($categorias as $categoria)
                    @if($categoria->platos->where('activo', false)->count() > 0)
                        @php $hayOcultos = true; @endphp
                        
                        <div class="bg-white px-4 py-2 border-bottom sticky-top shadow-sm">
                            <h6 class="fw-bold mb-0 text-secondary text-uppercase small" style="letter-spacing: 1px;">
                                {{ $categoria->nombre }}
                            </h6>
                        </div>
                        
                        <div class="list-group list-group-flush mb-2">
                            @foreach($categoria->platos->where('activo', false) as $plato)
                                <div class="list-group-item d-flex justify-content-between align-items-center p-3 border-bottom" id="fila-plato-oculto-{{ $plato->id }}">
                                    
                                    <div class="d-flex align-items-center gap-3">
                                        @if($plato->imagen)
                                            <img src="{{ asset('storage/' . $plato->imagen) }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-light d-flex justify-content-center align-items-center border" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $plato->nombre }}</h6>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 mt-1">Agotado / Oculto</span>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-sm btn-success fw-bold px-3 rounded-pill shadow-sm" onclick="reactivarPlatoSilencioso({{ $plato->id }}, this)">
                                        <i class="bi bi-power me-1"></i> Reactivar
                                    </button>

                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @if(!$hayOcultos)
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle text-success fs-1 mb-2 d-block"></i>
                        <h5 class="fw-bold">¡Todo al día!</h5>
                        <p class="text-muted">Todos los platos de tu carta están visibles ahora mismo.</p>
                    </div>
                @endif
            </div>

            <div class="modal-footer border-top-0 bg-white rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary fw-bold px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>