    <form action="{{ route('admin.configuracion.ajustes') }}" method="POST" id="cfgForm">
        @csrf

        <div class="tab-content" id="configTabsContent">
            
         
            <div class="tab-pane fade show active" id="tab-identidad" role="tabpanel" aria-labelledby="tab-identidad-btn">
                <div class="row g-3">
                    
                    {{-- Tarjeta: Datos del restaurante (Más ancha en pantallas grandes) --}}
                    <div class="col-12 col-xl-7">
                        <div class="cfg-card">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(255,122,0,0.1); color: var(--primary-orange);">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <h5>Datos del restaurante</h5>
                                    <p>Información principal que verán tus clientes</p>
                                </div>
                            </div>
                            
                            {{-- Agrupamos en filas para no estirar hacia abajo --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-7">
                                    <label class="cfg-label">Nombre del restaurante *</label>
                                    <input type="text" class="form-control" name="nombre_restaurante" 
                                        value="{{ $ajustes['nombre_restaurante'] ?? '' }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="cfg-label">Eslogan</label>
                                    <input type="text" class="form-control" name="eslogan" 
                                        value="{{ $ajustes['eslogan'] ?? '' }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="cfg-label">Dirección (para tickets y recibos)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="direccion" 
                                            value="{{ $ajustes['direccion'] ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="cfg-label">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                        <input type="tel" class="form-control border-start-0 ps-0" name="telefono" 
                                            value="{{ $ajustes['telefono'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="cfg-label">Email de contacto</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" class="form-control border-start-0 ps-0" name="email_contacto" 
                                            value="{{ $ajustes['email_contacto'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Derecha: Marca y Redes --}}
                    <div class="col-12 col-xl-5 d-flex flex-column gap-3">
                        
                        {{-- Tarjeta: Marca Visual --}}
                        <div class="cfg-card">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(139,92,246,0.1); color: #8B5CF6;">
                                    <i class="bi bi-palette2"></i>
                                </div>
                                <div>
                                    <h5>Marca Visual</h5>
                                    <p>Logotipo de la app</p>
                                </div>
                            </div>
                            
                            <div class="row g-3 align-items-center">
                                <div class="col-sm-8">
                                    <label class="cfg-label">URL del logotipo (PNG/SVG)</label>
                                    <input type="url" class="form-control" name="logo_url" id="logoUrlInput"
                                        value="{{ $ajustes['logo_url'] ?? '' }}" placeholder="https://...">
                                </div>
                                <div class="col-sm-4 text-center">
                                    <div id="logoWrap" class="p-2 rounded border bg-light d-flex align-items-center justify-content-center" style="height: 55px; {{ empty($ajustes['logo_url']) ? 'display:none !important;' : '' }}">
                                        <img id="logoImg" src="{{ $ajustes['logo_url'] ?? '' }}" style="max-height: 40px; max-width: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tarjeta: Redes y WiFi --}}
                        <div class="cfg-card flex-grow-1">
                            <div class="cfg-card__head">
                                <div class="cfg-card__icon" style="background: rgba(16,185,129,0.1); color: #10B981;">
                                    <i class="bi bi-share"></i>
                                </div>
                                <div>
                                    <h5>Redes y Conexión</h5>
                                    <p>Mostrado al cliente</p>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="cfg-label">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-instagram text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="instagram" 
                                            value="{{ $ajustes['instagram'] ?? '' }}" placeholder="@usuario">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="cfg-label">Google Maps (URL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-map text-muted"></i></span>
                                        <input type="url" class="form-control border-start-0 ps-0" name="google_maps_url" 
                                            value="{{ $ajustes['google_maps_url'] ?? '' }}" placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="cfg-label">Nombre WiFi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-wifi text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" name="wifi_nombre" 
                                            value="{{ $ajustes['wifi_nombre'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="cfg-label">Clave WiFi</label>
                                    <input type="text" class="form-control" name="wifi_clave" 
                                        value="{{ $ajustes['wifi_clave'] ?? '' }}" placeholder="Opcional">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> 

            </div> 