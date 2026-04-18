<div class="modal fade" id="modalCobrar" tabindex="-1" aria-labelledby="modalCobrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCobrarLabel"><i class="bi bi-cash-coin me-2"></i>Cobrar Mesa
                    #{{ $mesa->numero }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.mesa.cobrar', $mesa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">MÉTODO DE PAGO</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" id="pagoEfectivo"
                                    value="efectivo" checked required>
                                <label class="form-check-label" for="pagoEfectivo">
                                    <i class="bi bi-cash text-success"></i> Efectivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" id="pagoTarjeta"
                                    value="tarjeta" required>
                                <label class="form-check-label" for="pagoTarjeta">
                                    <i class="bi bi-credit-card text-info"></i> Tarjeta
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="caja_id" class="form-label fw-bold text-muted">CAJA DE COBRO</label>
                        <select class="form-select" id="caja_id" name="caja_id" required>
                            <option value="" disabled selected>Selecciona una caja...</option>
                            @foreach($cajas as $caja)
                                <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-warning mb-0 border-warning border-opacity-25 bg-warning bg-opacity-10">
                        <strong>Total a cobrar:</strong> {{ number_format($totalMesa, 2, ',', '.') }} €
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>Confirmar Cobro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>