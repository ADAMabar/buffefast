<div class="modal fade" id="modalPlato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Agregar Nuevo
                    Plato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.plato.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor, corrige estos
                                errores:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMBRE DEL PLATO <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control bg-light border-0 py-2"
                                placeholder="Ej: Hamburguesa Trufada" required value="{{ old('nombre') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">CATEGORÍA <span
                                    class="text-danger">*</span></label>
                            <select name="categoria_id" class="form-select bg-light border-0 py-2" required>
                                <option value="" disabled {{ old('categoria_id') ? '' : 'selected' }}>Selecciona una
                                    categoría...</option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PRECIO <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="precio" class="form-control bg-light border-0 py-2"
                                placeholder="Ej: 12.99" step="0.01" required value="{{ old('precio', '0.00') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">DESCRIPCIÓN / INGREDIENTES</label>
                            <textarea name="descripcion" class="form-control bg-light border-0" rows="3"
                                placeholder="Ej: Carne de vaca madurada, queso cheddar...">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">FOTO DEL PLATO</label>
                            <input type="file" name="imagen" class="form-control bg-light border-0" accept="image/*">
                            <div class="form-text small">Sube una imagen en formato JPG, PNG o WEBP (Máx: 2MB).</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary fw-bold px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-1"></i> Guardar
                        Plato</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Busca el modal por su ID ('modalPlato') y dile a Bootstrap que lo abra
            var miModalError = new bootstrap.Modal(document.getElementById('modalPlato'));
            miModalError.show();
        });
    </script>
@endif