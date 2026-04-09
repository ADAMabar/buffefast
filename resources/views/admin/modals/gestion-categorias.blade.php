<div class="modal fade" id="modalCategorias" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-tags-fill me-2 text-dark"></i>Gestión de Categorías</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">

                <form action="{{ route('admin.categoria.store') }}" method="POST" class="mb-4">
                    @csrf
                    <label class="form-label fw-bold small text-muted">AÑADIR NUEVA CATEGORÍA</label>

                    <div class="mb-3">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría"
                            required>
                        @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="number" name="orden" class="form-control"
                            placeholder="En que orden quieres que parezca esta categoria?" required>
                        @error('orden')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-dark fw-bold px-4" type="submit">Guardar</button>
                </form>

                <label class="form-label fw-bold small text-muted mb-2">CATEGORÍAS EXISTENTES</label>

                @if($categorias->isEmpty())
                    <div class="p-3 bg-light text-center rounded-3 border">
                        <p class="text-muted small mb-0">Aún no hay categorías creadas.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush border rounded-3 overflow-hidden shadow-sm">
                        @foreach($categorias as $c)
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center bg-white py-3 border-bottom">
                                <div>
                                    <span class="fw-bold">{{ $c->nombre }}</span>
                                    <span class="badge bg-secondary ms-2 rounded-pill">{{ $c->platos->count() }} platos</span>
                                </div>

                                <form action="{{ route('admin.categoria.eliminar', $c->id) }}" method="POST" class="m-0"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar la categoría {{ $c->nombre }}?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"
                                        @if($c->platos->isNotEmpty()) disabled title="No puedes borrar una categoría con platos"
                                        @endif>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Si hay errores de validación para nombre u orden, abrir el modal
        @if($errors->has('nombre') || $errors->has('orden'))
            var modalCategorias = new bootstrap.Modal(document.getElementById('modalCategorias'));
            modalCategorias.show();
        @endif
    });
</script>
</div>