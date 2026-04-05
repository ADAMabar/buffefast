<div class="modal fade" id="modalGestionMesas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-gear-fill me-2"></i>Gestión de Mesas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Número</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mesas as $m)
                            <tr>
                                <td class="fw-bold fs-5">#{{ $m->numero }}</td>
                                <td>
                                    @if($m->sesiones->first())
                                        <span class="badge bg-warning text-dark rounded-pill">En uso</span>
                                    @else
                                        <span class="badge bg-success rounded-pill">Libre</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.mesa.eliminar', $m->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Seguro que quieres eliminar la Mesa {{ $m->numero }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"
                                            @if($m->sesiones->first()) disabled title="No puedes borrar una mesa en uso"
                                            @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>