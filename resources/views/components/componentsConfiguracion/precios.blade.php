
            <div class="tab-pane fade" id="tab-precios" role="tabpanel" aria-labelledby="tab-precios-btn">
                <div class="row g-3">

                    {{-- Columna Izquierda: Tarifas, IVA y Métodos de pago --}}
                    <div class="col-12 col-xl-7">
                        <div class="cfg-card">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div>
                                    <h5>Precios y Facturación</h5>
                                    <p>Tarifa única del buffet y configuración de cobro</p>
                                </div>
                            </div>
                            
                            {{-- Precio Único del Buffet --}}
                            <h6 class="fw-bold mb-3" style="font-size: 0.9rem; color: var(--text-main);">Tarifa por comensal</h6>
                            <div class="mb-4" style="max-width: 250px;">
                                <label class="cfg-label">Precio del Buffet *</label>
                                <div class="input-group">
                                    {{-- Usamos la clave que ya tienes en BD, pero le cambiamos la etiqueta --}}
                                    <input type="number" step="0.50" class="form-control precio-input" name="precio_buffet_adulto" id="inputPrecioBase"
                                        value="{{ configuracion('precio_buffet_adulto', '15.90') }}" min="0" required>
                                    <span class="input-group-text bg-light text-muted">€</span>
                                </div>
                                <p class="cfg-hint mt-1">Este será el precio base que se multiplicará por el número de personas en la mesa.</p>
                            </div>

                            <hr class="text-muted opacity-25 my-4">

                            {{-- Impuestos (IVA) --}}
                            <div class="mb-4">
                                <label class="cfg-label d-block mb-2">IVA aplicable *</label>
                                @php $iva = configuracion('porcentaje_impuestos', '10'); @endphp
                                
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach(['0'=>'0%','4'=>'4%','10'=>'10%','21'=>'21%'] as $v => $l)
                                        <input type="radio" class="btn-check iva-radio" name="porcentaje_impuestos" id="iva{{ $v }}" autocomplete="off" value="{{ $v }}" {{ $iva === (string)$v ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium" for="iva{{ $v }}">{{ $l }}</label>
                                    @endforeach
                                    
                                    <input type="radio" class="btn-check iva-radio" name="porcentaje_impuestos" id="ivaOtro" autocomplete="off" value="otro" {{ !in_array($iva, ['0','4','10','21']) ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium" for="ivaOtro">Otro</label>
                                
                                    <input type="number" class="form-control form-control-sm iva-custom-input" name="porcentaje_impuestos_custom" 
                                        value="{{ !in_array($iva, ['0','4','10','21']) ? $iva : '' }}" min="0" max="100" placeholder="Ej: 7.5">
                                    <span class="input-group-text bg-light text-muted py-0">%</span>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25 my-4">

                            {{-- Métodos de Pago --}}
                            <h6 class="fw-bold mb-3" style="font-size: 0.9rem; color: var(--text-main);">Métodos de pago permitidos</h6>
                            <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded-3 border">
                                @foreach(['efectivo'=>['bi-cash','Efectivo'], 'tarjeta'=>['bi-credit-card','Tarjeta'], 'bizum'=>['bi-phone','Bizum']] as $key => [$ico, $lbl])
                                    <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" role="switch" name="pago_{{ $key }}" value="true" id="pago_{{ $key }}"
                                            {{ configuracion('pago_'.$key, $key != 'bizum' ? 'true' : 'false') === 'true' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium mb-0 mt-1 d-flex align-items-center gap-1" for="pago_{{ $key }}">
                                            <i class="bi {{ $ico }} text-muted"></i> {{ $lbl }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    {{-- Columna Derecha: Preview del Ticket --}}
                    <div class="col-12 col-xl-5">
                        <div class="cfg-card d-flex flex-column">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                    <i class="bi bi-receipt-cutoff"></i>
                                </div>
                                <div>
                                    <h5>Preview del Ticket</h5>
                                    <p>Así lo verá tu cliente</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-3 border flex-grow-1 d-flex flex-column justify-content-center" style="background: #F9FAFB;">
                                <div class="bg-white p-4 shadow-sm mx-auto w-100" style="max-width: 300px; border-radius: 4px; font-family: 'Courier New', Courier, monospace; color: #374151;">
                                    
                                    <div class="text-center mb-3">
                                        <h6 class="fw-bold mb-1">{{ configuracion('nombre_restaurante', 'BuffeFast') }}</h6>
                                        <div style="font-size: 0.75rem; color: #6B7280;">{{ configuracion('direccion', 'Dirección del local') }}</div>
                                    </div>

                                    <div style="border-top: 1px dashed #D1D5DB; margin: 10px 0;"></div>
                                    
                                    {{-- Ticket Simplificado --}}
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem;">
                                        <span>2x Menú Buffet</span>
                                        <span><span id="tpBase">{{ number_format((float)configuracion('precio_buffet_adulto', 15.90) * 2, 2, ',', '.') }}</span>€</span>
                                    </div>

                                    <div style="border-top: 1px dashed #D1D5DB; margin: 10px 0;"></div>

                                    <div class="d-flex justify-content-between fw-bold mb-1" style="font-size: 1rem;">
                                        <span>TOTAL</span>
                                        <span><span id="tpTotal">{{ number_format((float)configuracion('precio_buffet_adulto', 15.90) * 2, 2, ',', '.') }}</span>€</span>
                                    </div>
                                    <div class="text-end mb-3" style="font-size: 0.7rem; color: #6B7280;">
                                        IVA incl. (<span id="tpIvaPct">{{ $iva }}</span>%)
                                    </div>

                                    <div class="text-center" style="font-size: 0.75rem; font-style: italic;" id="tpPie">
                                        {{ configuracion('texto_ticket_pie', '¡Gracias por su visita!') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="cfg-label">Pie de ticket personalizado</label>
                                <input type="text" class="form-control" name="texto_ticket_pie" id="inputPieTicket"
                                    value="{{ configuracion('texto_ticket_pie', '¡Gracias por su visita!') }}" 
                                    placeholder="Ej: Síguenos en @tu_instagram">
                            </div>

                        </div>
                    </div>

                </div>
            </div>