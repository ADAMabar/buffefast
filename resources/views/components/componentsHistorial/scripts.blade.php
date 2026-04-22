<script>
    function setPeriodo(valor, el) {
        // 1. Actualizar valor y clases visuales
        document.getElementById('inputTiempo').value = valor;
        document.querySelectorAll('.pill-filter').forEach(b => b.classList.remove('active'));
        el.classList.add('active');

        // 2. Mostrar/Ocultar campos extra si es rango o mes específico
        const esEspecial = ['mes_especifico', 'rango_custom'].includes(valor);
        document.getElementById('extraMesEspecifico').classList.toggle('d-none', valor !== 'mes_especifico');
        document.getElementById('extraRangoCustom').classList.toggle('d-none', valor !== 'rango_custom');

        // 3. Si es un filtro directo (como "Hoy"), enviar el formulario ya
        if (!esEspecial) {
            document.getElementById('formFiltros').submit();
        }
    }

    // para el Modal detalle
    function abrirDetalleTicket(id) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
        modal.show();

        // Estado de carga
        document.getElementById('modalBody').innerHTML = '<div class="text-center py-5">Cargando…</div>';

        fetch(`/admin/ventas/${id}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
            .then(r => r.json())
            .then(data => renderDetalle(data, id))
            .catch(() => {
                document.getElementById('modalBody').innerHTML = '<div class="text-danger">Error al cargar.</div>';
            });
    }
    function renderDetalle(v, id) {
        document.getElementById('modalTitulo').textContent = v.numero_ticket;
        document.getElementById('modalSub').textContent =
            `Mesa ${v.sesion?.mesa?.numero ?? '?'} · ${v.caja?.nombre ?? '?'} · ${v.camarero?.nombre ?? '?'} · ${v.created_at}`;

        const lineas = (v.lineas || []).map(l => `
        <tr>
            <td class="text-muted small ps-2">${l.nombre_plato}</td>
            <td class="text-center text-muted small">×${l.cantidad}</td>
            <td class="text-end text-muted small">${parseFloat(l.precio_unitario || 0).toFixed(2)}€</td>
            <td class="text-end fw-bold pe-2">${parseFloat(l.subtotal_linea || 0).toFixed(2)}€</td>
        </tr>`).join('') || '<tr><td colspan="4" class="text-muted text-center small py-3">Sin líneas registradas</td></tr>';

    const alertaAnulada = v.anulada
    ? `<div class="alert alert-danger d-flex align-items-center mb-3 rounded-3" role="alert">
        <i class="bi bi-slash-circle me-2"></i>
        <div>
            <strong>ANULADA</strong>${v.motivo_anulacion ? ' · ' + v.motivo_anulacion : ''}
        </div>
       </div>` 
    : '';

        document.getElementById('modalBody').innerHTML = `
        ${alertaAnulada}
        <div class="row g-2 mb-3">
            ${[
                ['COMENSALES', v.num_comensales],
                ['DURACIÓN', v.duracion_sesion_minutos ? v.duracion_sesion_minutos + 'min' : '—'],
                ['MÉTODO', v.metodo_pago],
                ['PROPINA', parseFloat(v.propina || 0).toFixed(2) + '€'],
            ].map(([l, val]) => `
                <div class="col-6 col-md-3">
                    <div class="text-center p-2 bg-light rounded-3">
                        <div class="text-muted" style="font-size:.68rem;font-weight:700;text-transform:uppercase">${l}</div>
                        <div class="fw-bold" style="font-size:.95rem">${val}</div>
                    </div>
                </div>`).join('')}
        </div>
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-2 small text-muted text-uppercase" style="font-size:.68rem">Plato</th>
                    <th class="text-center small text-muted text-uppercase" style="font-size:.68rem">Cant.</th>
                    <th class="text-end small text-muted text-uppercase" style="font-size:.68rem">P.Unit.</th>
                    <th class="text-end pe-2 small text-muted text-uppercase" style="font-size:.68rem">Subtotal</th>
                </tr>
            </thead>
            <tbody>${lineas}</tbody>
            <tfoot class="border-top">
                <tr>
                    <td colspan="3" class="text-end text-muted small">Subtotal</td>
                    <td class="text-end pe-2 small">${parseFloat(v.subtotal || 0).toFixed(2)}€</td>
                </tr>
                ${parseFloat(v.descuento_monto || 0) > 0 ? `<tr>
                    <td colspan="3" class="text-end text-muted small">Descuento</td>
                    <td class="text-end pe-2 small text-danger">-${parseFloat(v.descuento_monto).toFixed(2)}€</td>
                </tr>` : ''}
                ${parseFloat(v.iva || 0) > 0 ? `<tr>
                    <td colspan="3" class="text-end text-muted small">IVA</td>
                    <td class="text-end pe-2 small">${parseFloat(v.iva).toFixed(2)}€</td>
                </tr>` : ''}
                <tr>
                    <td colspan="3" class="text-end fw-bold">TOTAL</td>
                    <td class="text-end pe-2 fw-bold fs-5">${parseFloat(v.total || 0).toFixed(2)}€</td>
                </tr>
            </tfoot>
        </table>
        ${v.observaciones ? `<div class="mt-3 p-2 bg-light rounded-3 small text-muted">
            <i class="bi bi-chat-left-text me-1"></i>${v.observaciones}</div>` : ''}
    `;

        // Botón anular (solo si no está anulada)
        if (!v.anulada) {
            document.getElementById('modalFooter').innerHTML = `
            <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-3 me-auto"
                    data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-outline-danger fw-bold rounded-pill px-3"
                    onclick="prepararAnulacion(${id}, '${v.numero_ticket}')">
                <i class="bi bi-slash-circle me-1"></i>Anular
            </button>`;
        }
    }

    function prepararAnulacion(id, ticket) {
        bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
        document.getElementById('ticketAnular').textContent = ticket;
        document.getElementById('formAnular').action = `/admin/ventas/${id}/anular`;
        setTimeout(() => new bootstrap.Modal(document.getElementById('modalAnular')).show(), 350);
    }
</script>