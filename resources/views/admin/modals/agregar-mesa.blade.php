<div class="modal fade" id="modalAgregarMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Agregar Nueva
                    Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.mesa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label for="numero" class="form-label fw-bold">Número o Nombre de la Mesa</label>
                        <input type="number" class="form-control form-control-lg bg-light border-0" id="numero"
                            name="numero" placeholder="Ej: 5" required min="1">
                        @error('numero')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="capacidad" class="form-label fw-bold">Capacidad (Nº máximo de personas)</label>
                        <input type="number" class="form-control form-control-lg bg-light border-0" id="capacidad"
                            name="capacidad" placeholder="Ej: 4" required min="1">
                        <div class="form-text text-muted small"><i class="bi bi-info-circle me-1"></i>Útil para saber
                            cuántos clientes pueden conectarse a esta mesa.</div>
                        @error('capacidad')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">Guardar Mesa</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('modalAgregarMesa'));
                myModal.show();
            @endif
    });
    </script>
</div>