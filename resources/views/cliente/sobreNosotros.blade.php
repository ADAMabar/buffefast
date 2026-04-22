<x-layouts.cliente-app>
    
    <header class="top-nav d-flex align-items-center justify-content-center shadow-sm" style="height: 70px;">
        <h1 class="h5 mb-0 fw-bold text-dark">Sobre Nosotros</h1>
    </header>

    <main class="px-3 pt-4 pb-5">
        
        {{-- Cabecera: Logo, Nombre y Eslogan --}}
        <div class="text-center mb-4">
            @if(configuracion('logo_url'))
                <img src="{{ configuracion('logo_url') }}" alt="Logo {{ configuracion('nombre_restaurante') }}" class="mb-3" style="max-height: 80px; object-fit: contain; border-radius: 12px;">
            @else
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-orange text-white rounded-circle shadow-sm" style="width: 72px; height: 72px; font-size: 2rem; font-weight: 800;">
                    {{ substr(configuracion('nombre_restaurante', 'B'), 0, 1) }}
                </div>
            @endif
            
            <h2 class="h3 fw-bold text-dark mb-1">{{ configuracion('nombre_restaurante', 'Nuestro Restaurante') }}</h2>
            
            @if(configuracion('eslogan'))
                <p class="text-muted small mb-0 px-3">{{ configuracion('eslogan') }}</p>
            @endif
        </div>

        {{-- Tarjeta 1: Ubicación --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-geo-alt-fill text-orange me-2"></i>Ubicación
                </h6>
                <div class="d-flex flex-column gap-3">
                    {{-- Dirección --}}
                    <div class="text-dark d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                            <i class="bi bi-signpost-2"></i>
                        </div>
                        <span class="fw-medium" style="font-size: 0.9rem;">{{ configuracion('direccion', 'Dirección no configurada') }}</span>
                    </div>

                    {{-- URL de Google Maps --}}
                    @if(configuracion('google_maps_url'))
                        <div class="text-dark d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                                <i class="bi bi-map"></i>
                            </div>
                            <span class="fw-medium text-truncate" style="font-size: 0.85rem; color: #6c757d;">
                                {{ configuracion('google_maps_url') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Contacto --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-telephone-fill text-orange me-2"></i>Contacto
                </h6>
                <div class="d-flex flex-column gap-3">
                    {{-- Teléfono --}}
                    @if(configuracion('telefono'))
                        <div class="text-dark d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <span class="fw-medium" style="font-size: 0.9rem;">{{ configuracion('telefono') }}</span>
                        </div>
                    @endif

                    {{-- Email --}}
                    @if(configuracion('email_contacto'))
                        <div class="text-dark d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                                <i class="bi bi-envelope-at"></i>
                            </div>
                            <span class="fw-medium text-truncate" style="font-size: 0.9rem;">{{ configuracion('email_contacto') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Redes y Conexión --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-share-fill text-orange me-2"></i>Redes y Conexión
                </h6>
                <div class="d-flex flex-column gap-3">
                    
                    {{-- Instagram --}}
                    @if(configuracion('instagram'))
                        <div class="text-dark d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                                <i class="bi bi-instagram" style="color: #E1306C;"></i>
                            </div>
                            <span class="fw-medium" style="font-size: 0.9rem;">{{ configuracion('instagram') }}</span>
                        </div>
                    @endif

                    {{-- WiFi --}}
                    @if(configuracion('wifi_nombre'))
                        <div class="d-flex align-items-center pt-2 mt-1 border-top">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 border" style="min-width: 40px; height: 40px;">
                                <i class="bi bi-wifi text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.85rem; color: var(--text-muted);">Red WiFi Local</div>
                                <div class="fw-medium text-dark" style="font-size: 0.9rem;">{{ configuracion('wifi_nombre') }}</div>
                                @if(configuracion('wifi_clave'))
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                        Contraseña: <span class="bg-light px-2 py-1 rounded border font-monospace text-dark">{{ configuracion('wifi_clave') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>
<x-nav-bottom active="nosotros" />
</x-layouts.cliente-app>