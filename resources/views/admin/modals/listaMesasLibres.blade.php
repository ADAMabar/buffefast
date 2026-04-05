<div class="modal fade" id="modalMesasLibres" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-success">
                    <i class="bi bi-check-circle-fill me-2"></i>Mesas Disponibles
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-3">

                    @if($mesasLibres->isEmpty())
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-emoji-laughing fs-1 text-muted mb-2 d-block"></i>
                            <h5 class="fw-bold text-dark">¡Local Lleno!</h5>
                            <p class="text-muted">No hay ninguna mesa libre en este momento.</p>
                        </div>
                    @else
                        @foreach($mesasLibres as $mesa)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card border-success border-2 shadow-sm h-100 rounded-4 text-center p-3">
                                    <h3 class="fw-bold mb-1">#{{ $mesa->numero }}</h3>
                                    <p class="small text-muted mb-3">Capacidad: {{ $mesa->capacidad ?? '?' }}</p>

                                    <form action="{{ route('admin.mesa.activar', $mesa->id) }}" method="POST" class="mt-auto">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100 fw-bold rounded-pill">
                                            Activar Mesa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>