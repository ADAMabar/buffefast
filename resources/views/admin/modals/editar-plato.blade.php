<div class="modal fade" id="modalEditarPlato{{ $plato->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Editar Plato
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.plato.update', $plato->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4">

                    @if ($errors->has('edit_plato_'.$plato->id))
                        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor, corrige estos errores:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->get('edit_plato_'.$plato->id) as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMBRE DEL PLATO <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control bg-light border-0 py-2"
                                placeholder="Ej: Hamburguesa Trufada" required value="{{ old('nombre', $plato->nombre) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">CATEGORÍA <span class="text-danger">*</span></label>
                            <select name="categoria_id" class="form-select bg-light border-0 py-2" required>
                                <option value="" disabled>Selecciona una categoría...</option>
                                @foreach($categorias as $c)
                                    {{-- Marcamos como 'selected' la categoría que ya tiene el plato --}}
                                    <option value="{{ $c->id }}" {{ old('categoria_id', $plato->categoria_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PRECIO <span class="text-danger">*</span></label>
                       
                            <input type="number" name="precio" class="form-control bg-light border-0 py-2"
                                placeholder="Ej: 12.99" step="0.01" required value="{{ old('precio', $plato->precio) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">DESCRIPCIÓN / INGREDIENTES</label>
                          
                            <textarea name="descripcion" class="form-control bg-light border-0" rows="3"
                                placeholder="Ej: Carne de vaca madurada, queso cheddar...">{{ old('descripcion', $plato->descripcion) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">FOTO DEL PLATO (Opcional)</label>
                            
                          
                            @if($plato->imagen)
                                <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-light rounded-3 border">
                                    <img src="{{ asset('storage/' . $plato->imagen) }}" alt="Foto actual" 
                                         class="rounded-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="small text-muted">Imagen actual. Sube una nueva solo si quieres cambiarla.</div>
                                </div>
                            @endif

                            <input type="file" name="imagen" class="form-control bg-light border-0" accept="image/*">
                            <div class="form-text small">Sube una imagen en formato JPG, PNG o WEBP (Máx: 2MB) si deseas sustituir la actual.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>