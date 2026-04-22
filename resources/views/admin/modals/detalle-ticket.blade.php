{{-- ── MODAL DETALLE TICKET ─────────────────────────────────────────── --}}
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-3"
                 style="background:#111827;border-radius:16px 16px 0 0">
                <div>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="modalTitulo">Detalle del ticket</h5>
                    <p class="mb-0 text-white opacity-50 small" id="modalSub"></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalBody">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm me-2"></div>Cargando…
                </div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 pb-4 gap-2"
                 style="border-radius:0 0 16px 16px" id="modalFooter">
                <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4"
                        data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
