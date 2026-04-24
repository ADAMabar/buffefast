 <div class="tab-pane fade" id="tab-operativa" role="tabpanel" aria-labelledby="tab-operativa-btn">
                <div class="row g-0">

                    {{-- Tarjeta: Control de Rondas --}}
                    <div class="col-12 col-xl-6">
                        <div class="cfg-card">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(255,122,0,0.1); color: var(--primary-orange);">
                                    <i class="bi bi-stopwatch-fill"></i>
                                </div>
                                <div>
                                    <h5>Control de Rondas</h5>
                                    <p>Tiempos y límites de pedidos por mesa</p>
                                </div>
                            </div>
                            
                            {{-- Slider de tiempo --}}
                            <div class="mb-4 p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="cfg-label mb-0">Tiempo de espera entre rondas</label>
                                    @php $tiempoRonda = configuracion('tiempo_ronda_minutos', '10'); @endphp
                                    <span class="badge {{ $tiempoRonda == '0' ? 'bg-danger' : 'bg-dark' }} fs-6" id="valRondaBadge">
                                        {{ $tiempoRonda == '0' ? 'Sin espera' : $tiempoRonda . ' min' }}
                                    </span>
                                </div>
                                <input type="range" class="form-range" name="tiempo_ronda_minutos" id="rondaSlider"
                                    min="0" max="60" step="5" 
                                    value="{{ $tiempoRonda }}">
                                <p class="cfg-hint mt-1 text-center">0 = Los clientes pueden pedir sin esperar.</p>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="cfg-label">Límite de platos (persona/ronda) *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="limite_platos_ronda" 
                                            value="{{ configuracion('limite_platos_ronda', '4') }}" min="1" max="50" required>
                                        <span class="input-group-text bg-light text-muted">platos</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="cfg-label">Rondas máximas (por sesión)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="rondas_maximas_sesion" 
                                            value="{{ configuracion('rondas_maximas_sesion', '0') }}" min="0" max="20">
                                        <span class="input-group-text bg-light text-muted">rondas</span>
                                    </div>
                                    <p class="cfg-hint">0 = Ilimitadas.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta: Penalización por desperdicio --}}
                    <div class="col-12 col-xl-6">
                        <div class="cfg-card h-100">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(239,68,68,0.1); color: #EF4444;">
                                    <i class="bi bi-exclamation-octagon-fill"></i>
                                </div>
                                <div>
                                    <h5>Penalización por Sobras</h5>
                                    <p>Cargo automático por platos no consumidos</p>
                                </div>
                            </div>

                            {{-- Switch de Activación --}}
                            <div class="d-flex justify-content-between align-items-center p-3 mb-3 border rounded-3" style="background: #FAFAFA;">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">Activar penalización</h6>
                                    <p class="cfg-hint mb-0">El cliente verá un aviso en la app.</p>
                                </div>
                                <div class="form-check form-switch fs-4 mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="penalizacion_activa" value="true" id="chkPen" 
                                        {{ configuracion('penalizacion_activa', 'false') === 'true' ? 'checked' : '' }}>
                                </div>
                            </div>

                            {{-- Campos que se ocultan/muestran --}}
                            <div id="penFields" class="row g-3" style="transition: opacity 0.3s;">
                                <div class="col-sm-5">
                                    <label class="cfg-label">Precio por plato</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" step="0.10" name="precio_penalizacion" 
                                            value="{{ configuracion('precio_penalizacion', '2.00') }}" min="0">
                                        <span class="input-group-text bg-light text-muted">€</span>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <label class="cfg-label">Mensaje de advertencia</label>
                                    <input type="text" class="form-control" name="mensaje_penalizacion" 
                                        value="{{ configuracion('mensaje_penalizacion', 'Cargo de {precio}€ por plato sobrante.') }}" 
                                        placeholder="Usa {precio} para el importe">
                                    <p class="cfg-hint">La palabra <code>{precio}</code> se cambia sola por el importe.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
</div>
     