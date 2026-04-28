<div class="modal fade" id="modalPidiendoCuenta" tabindex="-1" aria-labelledby="modalPidiendoCuentaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">

            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-warning" id="modalPidiendoCuentaLabel">
                    <i class="bi bi-receipt me-2"></i>Mesas Listas para Cobrar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-3">

                    @forelse($mesasPidiendoCuenta as $mesa)
                        @php
                            $sesion = $mesa->sesiones->first();
                        @endphp

                        <div class="col-12">
                            <div class="card border-warning border-2 shadow-sm rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                                    <div>
                                        <h4 class="fw-bold text-dark mb-1">Mesa #{{ $mesa->numero }}</h4>
                                        <p class="small text-muted mb-0">
                                            <i class="bi bi-clock-history me-1"></i>Esperando desde:
                                            {{ $sesion->updated_at->format('H:i') }}
                                        </p>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.mesa.show', $mesa->id) }}"
                                            class="btn btn-outline-info fw-bold rounded-pill px-4">
                                            <i class="bi bi-eye me-1"></i>Ver TPV
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-check2-circle fs-1 text-success mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark">Todo al día</h5>
                            <p class="text-muted">No hay ninguna mesa pidiendo la cuenta en este momento.</p>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</div>