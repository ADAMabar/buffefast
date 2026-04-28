<script>
    // 💡 AYUDANTE: Transforma cualquier número a formato "10.50€" automáticamente
    const eur = (val) => parseFloat(val || 0).toFixed(2) + '€';

    // ==========================================
    // 1. FILTROS DE PERIODO
    // ==========================================
    function setPeriodo(valor, el) {
        document.getElementById('inputTiempo').value = valor;
        
        // Quita el active al anterior y se lo pone al nuevo
        document.querySelector('.pill-filter.active')?.classList.remove('active');
        el.classList.add('active');

        // Muestra/Oculta campos extra
        const esEspecial = ['mes_especifico', 'rango_custom'].includes(valor);
        document.getElementById('extraMesEspecifico').classList.toggle('d-none', valor !== 'mes_especifico');
        document.getElementById('extraRangoCustom').classList.toggle('d-none', valor !== 'rango_custom');

        // Si no es un filtro de fecha manual, envía el formulario
        if (!esEspecial) document.getElementById('formFiltros').submit();
    }

    // ==========================================
    // 2. MODAL DETALLE DE VENTA
    // ==========================================
    function abrirDetalleTicket(id) {
        new bootstrap.Modal(document.getElementById('modalDetalle')).show();
        document.getElementById('modalBody').innerHTML = '<div class="text-center py-5">Cargando…</div>';

        fetch(`/admin/ventas/${id}`, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => renderDetalle(data, id))
            .catch(() => document.getElementById('modalBody').innerHTML = '<div class="text-danger text-center py-4">Error al cargar el ticket.</div>');
    }

    function renderDetalle(v, id) {
        // Cabecera
        document.getElementById('modalTitulo').textContent = v.numero_ticket;
        document.getElementById('modalSub').textContent = `Mesa ${v.sesion?.mesa?.numero ?? '?'} · ${v.caja?.nombre ?? '?'} · ${v.camarero?.nombre ?? '?'} · ${v.created_at}`;

        // Generar filas de platos
        const lineasHTML = (v.lineas?.length) ? v.lineas.map(l => `
            <tr>
                <td class="text-muted small ps-2">${l.nombre_plato}</td>
                <td class="text-center text-muted small">×${l.cantidad}</td>
                <td class="text-end text-muted small">${eur(l.precio_unitario)}</td>
                <td class="text-end fw-bold pe-2">${eur(l.subtotal_linea)}</td>
            </tr>`).join('') : '<tr><td colspan="4" class="text-muted text-center py-3">Sin líneas registradas</td></tr>';

        // Generar recuadros de estadísticas
        const statsHTML = [
            ['COMENSALES', v.num_comensales],
            ['DURACIÓN', v.duracion_sesion_minutos ? `${v.duracion_sesion_minutos} min` : '—'],
            ['MÉTODO', v.metodo_pago],
            ['PROPINA', eur(v.propina)]
        ].map(([lbl, val]) => `
            <div class="col-6 col-md-3">
                <div class="text-center p-2 bg-light rounded-3">
                    <div class="text-muted text-uppercase" style="font-size:.68rem; font-weight:700;">${lbl}</div>
                    <div class="fw-bold">${val}</div>
                </div>
            </div>`).join('');

        // Montar el cuerpo del Modal
        document.getElementById('modalBody').innerHTML = `
            ${v.anulada ? `<div class="alert alert-danger mb-3"><i class="bi bi-slash-circle me-2"></i><strong>ANULADA</strong> ${v.motivo_anulacion ? '· ' + v.motivo_anulacion : ''}</div>` : ''}
            
            <div class="row g-2 mb-3">${statsHTML}</div>
            
         <table class="table table-sm mb-0">
                <thead class="table-light small text-muted text-uppercase" style="font-size:.68rem">
                    <tr><th class="ps-2">Plato</th><th class="text-center">Cant.</th><th class="text-end">P.Unit.</th><th class="text-end pe-2">Subtotal</th></tr>
                </thead>
                <tbody>${lineasHTML}</tbody>
             <tfoot class="border-top small">
                    <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td class="text-end pe-2">${eur(v.subtotal)}</td></tr>
                    ${v.descuento_monto > 0 ? `<tr><td colspan="3" class="text-end text-muted">Descuento</td><td class="text-end pe-2 text-danger">-${eur(v.descuento_monto)}</td></tr>` : ''}
                    
                    <tr><td colspan="3" class="text-end text-muted">IVA Aplicado</td><td class="text-end pe-2">{{ configuracion('porcentaje_impuestos', '10') }}%</td></tr>
                    
                    <tr><td colspan="3" class="text-end text-muted">Precio base del buffet</td><td class="text-end pe-2">{{ configuracion('precio_buffet_adulto', '15.90') }}€</td></tr>
                    
                    <tr><td colspan="3" class="text-end fw-bold">TOTAL</td><td class="text-end pe-2 fw-bold fs-5">${eur(v.total)}</td></tr>
                </tfoot>
            </table>
            
            ${v.observaciones ? `<div class="mt-3 p-2 bg-light rounded-3 small text-muted"><i class="bi bi-chat-left-text me-1"></i>${v.observaciones}</div>` : ''}
        `;

        // Footer (Botón de anular)
        document.getElementById('modalFooter').innerHTML = v.anulada ? '' : `
            <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-3 me-auto" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-outline-danger fw-bold rounded-pill px-3" onclick="prepararAnulacion(${id}, '${v.numero_ticket}')">
                <i class="bi bi-slash-circle me-1"></i> Anular
            </button>`;
    }

    // ==========================================
    // 3. MODAL ANULAR TICKET
    // ==========================================
    function prepararAnulacion(id, ticket) {
        bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
        document.getElementById('ticketAnular').textContent = ticket;
        document.getElementById('formAnular').action = `/admin/ventas/${id}/anular`;
        setTimeout(() => new bootstrap.Modal(document.getElementById('modalAnular')).show(), 350);
    }
</script>