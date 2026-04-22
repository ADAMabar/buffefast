{{-- ── MODAL ANULAR ───────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-slash-circle me-2"></i>Anular venta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAnular" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body pt-0">
                    <p class="text-muted small">
                        El ticket <strong id="ticketAnular"></strong> quedará marcado como anulado.
                        Esta acción queda registrada y no se puede revertir.
                    </p>
                    <label class="form-label fw-bold small">Motivo *</label>
                    <textarea name="motivo_anulacion" class="form-control rounded-3" rows="2"
                        required placeholder="Ej: Error en el pedido…"></textarea>
                </div>
                <div class="modal-footer border-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3"
                            data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-3">
                        Confirmar anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
