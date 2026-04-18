<x-layouts.admin>

{{-- ====================================================================
     CONFIGURACIÓN — Vista completa y responsiva
     Tabs: Identidad · Operativa · Precios · Cocina · App Cliente · Seguridad · Equipo
     Quitados: Localización · Comportamiento del sistema
==================================================================== --}}

<style>
:root {
    --bf-orange:        #FF7A00;
    --bf-orange-soft:   rgba(255,122,0,.09);
    --bf-orange-border: rgba(255,122,0,.3);
    --bf-red:           #EF4444;
    --bf-red-soft:      rgba(239,68,68,.09);
    --bf-green:         #10B981;
    --bf-green-soft:    rgba(16,185,129,.09);
    --bf-blue:          #3B82F6;
    --bf-blue-soft:     rgba(59,130,246,.09);
    --bf-purple:        #8B5CF6;
    --bf-purple-soft:   rgba(139,92,246,.09);
    --bf-amber:         #F59E0B;
    --bf-amber-soft:    rgba(245,158,11,.09);
    --bf-g50:  #F9FAFB;
    --bf-g100: #F3F4F6;
    --bf-g200: #E5E7EB;
    --bf-g400: #9CA3AF;
    --bf-g700: #374151;
    --bf-g900: #111827;
    --r-card:  16px;
    --r-input: 10px;
    --sh-card: 0 1px 3px rgba(0,0,0,.05), 0 4px 12px rgba(0,0,0,.04);
    --sh-hover: 0 6px 24px rgba(0,0,0,.08);
}

/* ── Cabecera ── */
.cfg-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1.75rem; flex-wrap:wrap; }
.cfg-header__title { font-size:1.5rem; font-weight:800; color:var(--bf-g900); margin-bottom:.2rem; }
.cfg-header__sub   { color:var(--bf-g400); font-size:.88rem; }

/* ── Botón pánico ── */
.btn-panico {
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.55rem 1.25rem; border-radius:50px;
    font-weight:700; font-size:.85rem;
    border:2px solid var(--bf-red); color:var(--bf-red);
    background:transparent; cursor:pointer; transition:all .2s; white-space:nowrap;
}
.btn-panico:hover,.btn-panico.active { background:var(--bf-red); color:#fff; }
.btn-panico.active { animation:pulso 2s infinite; }
@keyframes pulso {
    0%,100% { box-shadow:0 0 0 0 rgba(239,68,68,.4); }
    50%      { box-shadow:0 0 0 8px rgba(239,68,68,0); }
}

/* ── Alertas ── */
.cfg-alert { display:flex; align-items:flex-start; gap:.7rem; padding:.8rem 1rem; border-radius:12px; font-size:.87rem; margin-bottom:1.25rem; }
.cfg-alert.success { background:rgba(16,185,129,.08); border:1.5px solid rgba(16,185,129,.25); color:#065F46; }
.cfg-alert.danger  { background:rgba(239,68,68,.08);  border:1.5px solid rgba(239,68,68,.25);  color:#991B1B; }

/* ── Tabs ── */
.cfg-tabs { display:flex; gap:.35rem; margin-bottom:1.75rem; padding-bottom:.6rem; border-bottom:2px solid var(--bf-g200); flex-wrap:wrap; }
.cfg-tab-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.5rem .95rem; border:none; background:transparent;
    border-radius:50px; font-size:.83rem; font-weight:700;
    color:var(--bf-g400); cursor:pointer; transition:all .18s; white-space:nowrap;
}
.cfg-tab-btn i { font-size:.95rem; }
.cfg-tab-btn:hover { background:var(--bf-g100); color:var(--bf-g900); }
.cfg-tab-btn.active { background:var(--bf-orange-soft); color:var(--bf-orange); box-shadow:inset 0 0 0 1.5px var(--bf-orange-border); }

/* ── Paneles ── */
.cfg-tab-pane { display:none; }
.cfg-tab-pane.active { display:block; animation:fadeIn .2s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

/* ── Tarjeta ── */
.cfg-card { background:#fff; border-radius:var(--r-card); border:1px solid var(--bf-g200); box-shadow:var(--sh-card); overflow:hidden; height:100%; transition:box-shadow .2s; }
.cfg-card:hover { box-shadow:var(--sh-hover); }
.cfg-card__head { padding:1.1rem 1.4rem .2rem; display:flex; align-items:center; gap:.7rem; }
.cfg-card__icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.cfg-card__head h5 { font-size:.95rem; font-weight:700; margin:0; color:var(--bf-g900); }
.cfg-card__head p  { font-size:.76rem; color:var(--bf-g400); margin:.1rem 0 0; }
.cfg-card__body { padding:1rem 1.4rem 1.4rem; }

/* ── Inputs ── */
.cfg-label { display:block; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:var(--bf-g400); margin-bottom:.35rem; }
.cfg-input { display:block; width:100%; padding:.58rem .8rem; border:1.5px solid var(--bf-g200); border-radius:var(--r-input); font-size:.9rem; color:var(--bf-g900); background:#fff; transition:border-color .2s,box-shadow .2s; outline:none; }
.cfg-input:focus { border-color:var(--bf-orange); box-shadow:0 0 0 3px rgba(255,122,0,.1); }
.cfg-input[type="color"] { padding:.28rem .4rem; height:40px; cursor:pointer; }
textarea.cfg-input { resize:vertical; min-height:68px; }
select.cfg-input { cursor:pointer; }
.cfg-hint { font-size:.74rem; color:var(--bf-g400); margin-top:.28rem; }

/* ── Input group ── */
.cfg-input-group { display:flex; border:1.5px solid var(--bf-g200); border-radius:var(--r-input); overflow:hidden; transition:border-color .2s,box-shadow .2s; }
.cfg-input-group:focus-within { border-color:var(--bf-orange); box-shadow:0 0 0 3px rgba(255,122,0,.1); }
.cfg-input-group__addon { background:var(--bf-g100); padding:.58rem .8rem; font-size:.82rem; font-weight:700; color:var(--bf-g400); border-right:1.5px solid var(--bf-g200); display:flex; align-items:center; white-space:nowrap; }
.cfg-input-group__addon.right { border-right:none; border-left:1.5px solid var(--bf-g200); }
.cfg-input-group input { flex:1; border:none; padding:.58rem .8rem; font-size:.9rem; color:var(--bf-g900); outline:none; background:#fff; min-width:0; }

/* ── Toggle ── */
.cfg-toggle-row { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:.8rem .95rem; border-radius:12px; border:1.5px solid var(--bf-g200); margin-bottom:.55rem; transition:border-color .18s,background .18s; }
.cfg-toggle-row:hover { border-color:var(--bf-g400); background:var(--bf-g50); }
.cfg-toggle-row__text h6 { font-size:.87rem; font-weight:700; margin:0; color:var(--bf-g900); }
.cfg-toggle-row__text p  { font-size:.74rem; color:var(--bf-g400); margin:.1rem 0 0; }
.cfg-switch { position:relative; display:inline-block; width:42px; height:23px; flex-shrink:0; }
.cfg-switch input { opacity:0; width:0; height:0; }
.cfg-switch-slider { position:absolute; inset:0; background:var(--bf-g200); border-radius:99px; cursor:pointer; transition:background .22s; }
.cfg-switch-slider::before { content:''; position:absolute; width:17px; height:17px; left:3px; top:3px; background:#fff; border-radius:50%; transition:transform .22s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.cfg-switch input:checked + .cfg-switch-slider { background:var(--bf-orange); }
.cfg-switch input:checked + .cfg-switch-slider::before { transform:translateX(19px); }

/* ── Separador ── */
.cfg-divider { border:none; border-top:1.5px solid var(--bf-g100); margin:1.1rem 0; }

/* ── Radio cards ── */
.cfg-radio-group { display:flex; gap:.5rem; flex-wrap:wrap; }
.cfg-radio-card { display:none; }
.cfg-radio-label { display:flex; flex-direction:column; align-items:center; gap:.3rem; padding:.65rem .9rem; border:1.5px solid var(--bf-g200); border-radius:12px; cursor:pointer; font-size:.78rem; font-weight:700; color:var(--bf-g400); transition:all .18s; min-width:62px; }
.cfg-radio-label i { font-size:1.2rem; }
.cfg-radio-label:hover { border-color:var(--bf-orange); color:var(--bf-orange); background:var(--bf-orange-soft); }
.cfg-radio-card:checked + .cfg-radio-label { border-color:var(--bf-orange); background:var(--bf-orange-soft); color:var(--bf-orange); box-shadow:0 0 0 1.5px var(--bf-orange-border); }

/* ── Color pill ── */
.color-pair { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
.color-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.2rem .65rem; border-radius:99px; border:1.5px solid var(--bf-g200); font-size:.74rem; font-weight:700; color:var(--bf-g700); background:#fff; }
.color-pill__dot { width:11px; height:11px; border-radius:50%; }

/* ── Ticket preview ── */
.ticket-prev { background:var(--bf-g50); border:1.5px dashed var(--bf-g200); border-radius:12px; padding:1.1rem; font-family:'Courier New',monospace; font-size:.79rem; line-height:1.9; color:var(--bf-g700); text-align:center; }
.ticket-prev .t-logo { font-size:1.1rem; font-weight:900; letter-spacing:.04em; color:var(--bf-g900); }
.ticket-prev .t-sep  { border-top:1.5px dashed var(--bf-g200); margin:.45rem 0; }

/* ── Range ── */
.cfg-range { width:100%; accent-color:var(--bf-orange); cursor:pointer; }

/* ── Guardar flotante ── */
.cfg-save-bar { position:sticky; bottom:1.25rem; display:flex; justify-content:flex-end; pointer-events:none; z-index:100; margin-top:1.75rem; }
.btn-save { pointer-events:all; display:inline-flex; align-items:center; gap:.45rem; padding:.65rem 1.9rem; background:var(--bf-orange); color:#fff; border:none; border-radius:50px; font-weight:700; font-size:.92rem; cursor:pointer; box-shadow:0 4px 18px rgba(255,122,0,.35); transition:all .18s; }
.btn-save:hover { background:#e06d00; transform:translateY(-1px); box-shadow:0 6px 24px rgba(255,122,0,.45); }

/* ── Tabla empleados ── */
.emp-table thead th { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--bf-g400); background:var(--bf-g50); border-bottom:1px solid var(--bf-g200); padding:.7rem 1rem; }
.emp-table tbody td { padding:.8rem 1rem; vertical-align:middle; border-bottom:1px solid var(--bf-g100); font-size:.88rem; }
.emp-table tbody tr:last-child td { border-bottom:none; }

/* ── Responsivo ── */
@media (max-width: 576px) {
    .cfg-header__title { font-size:1.2rem; }
    .cfg-tab-btn span  { display:none; }   /* Solo icono en móvil */
    .color-pair        { grid-template-columns:1fr; }
    .btn-save          { padding:.6rem 1.4rem; font-size:.86rem; }
}
</style>


{{-- ── CABECERA ────────────────────────────────────────────────────── --}}
<div class="cfg-header">
    <div>
        <h1 class="cfg-header__title">
            <i class="bi bi-gear-fill me-2" style="color:var(--bf-orange)"></i>Configuración
        </h1>
        <p class="cfg-header__sub">Ajustes del sistema, reglas del buffet e identidad del restaurante.</p>
    </div>
    <button class="btn-panico {{ ($ajustes['modo_panico'] ?? 'false') === 'true' ? 'active':'' }}"
            id="btnPanico" type="button" onclick="togglePanico()">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span id="textoPanico">
            {{ ($ajustes['modo_panico'] ?? 'false') === 'true' ? 'PÁNICO ACTIVO' : 'Modo Pánico' }}
        </span>
    </button>
</div>

{{-- ── ALERTAS ─────────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="cfg-alert success">
        <i class="bi bi-check-circle-fill" style="font-size:1rem;flex-shrink:0"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if($errors->any())
    <div class="cfg-alert danger">
        <i class="bi bi-shield-x" style="font-size:1rem;flex-shrink:0;margin-top:.1rem"></i>
        <div>
            <strong>Revisa los datos:</strong>
            @foreach($errors->all() as $e)<div>· {{ $e }}</div>@endforeach
        </div>
    </div>
@endif

{{-- ── TABS ────────────────────────────────────────────────────────── --}}
<div class="cfg-tabs">
    @foreach([
        ['identidad', 'bi-building',       'Identidad'],
        ['operativa', 'bi-stopwatch-fill',  'Operativa'],
        ['precios',   'bi-receipt',         'Precios'],
        ['cocina',    'bi-fire',            'Cocina'],
        ['cliente',   'bi-phone-fill',      'App Cliente'],
        ['seguridad', 'bi-shield-lock-fill','Seguridad'],
        ['equipo',    'bi-people-fill',     'Equipo'],
    ] as [$tab, $icon, $label])
        <button class="cfg-tab-btn {{ $tab === 'identidad' ? 'active':'' }}" data-tab="{{ $tab }}">
            <i class="bi {{ $icon }}"></i> <span>{{ $label }}</span>
        </button>
    @endforeach
</div>

{{-- ================================================================
     FORM GLOBAL — engloba todos los tabs excepto Equipo
================================================================ --}}
<form action="{{ route('admin.configuracion.ajustes') }}" method="POST" id="cfgForm">
    @csrf

    {{-- ============================================================
         TAB 1 — IDENTIDAD
    ============================================================ --}}
    <div class="cfg-tab-pane active" id="tab-identidad">
        <div class="row g-4">

            {{-- Datos del restaurante --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-orange-soft);color:var(--bf-orange)"><i class="bi bi-building"></i></div>
                        <div><h5>Datos del restaurante</h5><p>Nombre, dirección y contacto</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="mb-3">
                            <label class="cfg-label">Nombre del restaurante *</label>
                            <input type="text" class="cfg-input" name="nombre_restaurante"
                                value="{{ $ajustes['nombre_restaurante'] ?? 'BuffeFast' }}"
                                placeholder="Ej: Coco Sushi Almería" required>
                            <p class="cfg-hint">Aparece en tickets, cabecera de la app y notificaciones.</p>
                        </div>
                        <div class="mb-3">
                            <label class="cfg-label">Eslogan</label>
                            <input type="text" class="cfg-input" name="eslogan"
                                value="{{ $ajustes['eslogan'] ?? '' }}"
                                placeholder="Ej: Automatiza a tus clientes">
                        </div>
                        <hr class="cfg-divider">
                        <div class="mb-3">
                            <label class="cfg-label">Dirección (para tickets)</label>
                            <input type="text" class="cfg-input" name="direccion"
                                value="{{ $ajustes['direccion'] ?? '' }}"
                                placeholder="Ej: C/ Gran Vía 14, Almería">
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="cfg-label">Teléfono</label>
                                <input type="tel" class="cfg-input" name="telefono"
                                    value="{{ $ajustes['telefono'] ?? '' }}" placeholder="950 000 000">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="cfg-label">Email de contacto</label>
                                <input type="email" class="cfg-input" name="email_contacto"
                                    value="{{ $ajustes['email_contacto'] ?? '' }}" placeholder="info@…">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Marca visual --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-purple-soft);color:var(--bf-purple)"><i class="bi bi-palette2"></i></div>
                        <div><h5>Marca visual</h5><p>Colores e imagen de la app cliente</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="color-pair mb-3">
                            <div>
                                <label class="cfg-label">Color primario</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="cfg-input" name="color_primario" id="colorPrimario"
                                        value="{{ $ajustes['color_primario'] ?? '#FF7A00' }}"
                                        oninput="syncColor('Primario')">
                                    <span class="color-pill">
                                        <span class="color-pill__dot" id="dotPrimario" style="background:{{ $ajustes['color_primario'] ?? '#FF7A00' }}"></span>
                                        <span id="textPrimario">{{ $ajustes['color_primario'] ?? '#FF7A00' }}</span>
                                    </span>
                                </div>
                                <p class="cfg-hint">Botones y acentos.</p>
                            </div>
                            <div>
                                <label class="cfg-label">Color secundario</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="cfg-input" name="color_secundario" id="colorSecundario"
                                        value="{{ $ajustes['color_secundario'] ?? '#1F2937' }}"
                                        oninput="syncColor('Secundario')">
                                    <span class="color-pill">
                                        <span class="color-pill__dot" id="dotSecundario" style="background:{{ $ajustes['color_secundario'] ?? '#1F2937' }}"></span>
                                        <span id="textSecundario">{{ $ajustes['color_secundario'] ?? '#1F2937' }}</span>
                                    </span>
                                </div>
                                <p class="cfg-hint">Cabeceras y texto.</p>
                            </div>
                        </div>
                        <hr class="cfg-divider">
                        <div class="mb-2">
                            <label class="cfg-label">URL del logotipo</label>
                            <input type="url" class="cfg-input" name="logo_url"
                                value="{{ $ajustes['logo_url'] ?? '' }}"
                                placeholder="https://mirestaurante.com/logo.png"
                                oninput="previewLogo(this.value)">
                            <p class="cfg-hint">PNG o SVG. Se muestra en la pantalla de acceso del cliente.</p>
                        </div>
                        <div id="logoWrap" class="text-center p-3 rounded-3"
                             style="background:var(--bf-g50);border:1.5px dashed var(--bf-g200);{{ ($ajustes['logo_url'] ?? '') ? '' : 'display:none' }}">
                            <img id="logoImg" src="{{ $ajustes['logo_url'] ?? '' }}" alt="Logo preview"
                                 style="max-height:56px;max-width:160px;object-fit:contain;border-radius:8px">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Redes y WiFi --}}
            <div class="col-12">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-green-soft);color:var(--bf-green)"><i class="bi bi-share-fill"></i></div>
                        <div><h5>Redes sociales y WiFi</h5><p>Se muestran en la pantalla de cuenta del cliente</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-xl-3">
                                <label class="cfg-label">Instagram</label>
                                <div class="cfg-input-group">
                                    <span class="cfg-input-group__addon"><i class="bi bi-instagram"></i></span>
                                    <input type="text" name="instagram" value="{{ $ajustes['instagram'] ?? '' }}" placeholder="@mirestaurante">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <label class="cfg-label">Google Maps (URL)</label>
                                <div class="cfg-input-group">
                                    <span class="cfg-input-group__addon"><i class="bi bi-geo-alt-fill"></i></span>
                                    <input type="url" name="google_maps_url" value="{{ $ajustes['google_maps_url'] ?? '' }}" placeholder="https://maps.google.com/…">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <label class="cfg-label">Nombre WiFi</label>
                                <input type="text" class="cfg-input" name="wifi_nombre" value="{{ $ajustes['wifi_nombre'] ?? '' }}" placeholder="Ej: RestauranteGuests">
                            </div>
                            <div class="col-12 col-sm-6 col-xl-3">
                                <label class="cfg-label">Clave WiFi</label>
                                <input type="text" class="cfg-input" name="wifi_clave" value="{{ $ajustes['wifi_clave'] ?? '' }}" placeholder="Ej: sushi2025">
                                <p class="cfg-hint">Vacío = no mostrar al cliente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /identidad --}}


    {{-- ============================================================
         TAB 2 — OPERATIVA
    ============================================================ --}}
    <div class="cfg-tab-pane" id="tab-operativa">
        <div class="row g-4">

            {{-- Rondas --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-orange-soft);color:var(--bf-orange)"><i class="bi bi-stopwatch-fill"></i></div>
                        <div><h5>Control de rondas</h5><p>Tiempos y límites por pedido</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="mb-4">
                            <label class="cfg-label">Minutos entre rondas</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="cfg-range flex-fill" name="tiempo_ronda_minutos"
                                    min="0" max="60" step="5"
                                    value="{{ $ajustes['tiempo_ronda_minutos'] ?? 10 }}"
                                    oninput="document.getElementById('valRonda').textContent=this.value">
                                <span id="valRonda" style="min-width:2.5rem;text-align:right;font-weight:800;font-size:1.15rem;color:var(--bf-orange)">{{ $ajustes['tiempo_ronda_minutos'] ?? 10 }}</span>
                                <span class="cfg-label mb-0">min</span>
                            </div>
                            <p class="cfg-hint">0 = sin límite de tiempo entre rondas.</p>
                        </div>
                        <div class="mb-3">
                            <label class="cfg-label">Límite de platos por persona/ronda *</label>
                            <div class="cfg-input-group">
                                <input type="number" name="limite_platos_ronda"
                                    value="{{ $ajustes['limite_platos_ronda'] ?? 4 }}" min="1" max="50" required>
                                <span class="cfg-input-group__addon right">platos</span>
                            </div>
                        </div>
                        <div>
                            <label class="cfg-label">Rondas máximas por sesión</label>
                            <div class="cfg-input-group">
                                <input type="number" name="rondas_maximas_sesion"
                                    value="{{ $ajustes['rondas_maximas_sesion'] ?? 0 }}" min="0" max="20">
                                <span class="cfg-input-group__addon right">rondas</span>
                            </div>
                            <p class="cfg-hint">0 = sin límite de rondas por sesión.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penalización --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-red-soft);color:var(--bf-red)"><i class="bi bi-exclamation-circle-fill"></i></div>
                        <div><h5>Penalización por desperdicio</h5><p>Cargo si el cliente deja platos sin consumir</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="cfg-toggle-row mb-4">
                            <div class="cfg-toggle-row__text">
                                <h6>Activar penalización</h6>
                                <p>Habilita el cargo automático por plato sobrante.</p>
                            </div>
                            <label class="cfg-switch">
                                <input type="checkbox" name="penalizacion_activa" value="true"
                                    id="chkPen" onchange="togglePenFields(this.checked)"
                                    {{ ($ajustes['penalizacion_activa'] ?? 'false') === 'true' ? 'checked':'' }}>
                                <span class="cfg-switch-slider"></span>
                            </label>
                        </div>
                        <div id="penFields" style="{{ ($ajustes['penalizacion_activa'] ?? 'false') !== 'true' ? 'opacity:.4;pointer-events:none':'' }}">
                            <div class="mb-3">
                                <label class="cfg-label">Precio por plato sobrante</label>
                                <div class="cfg-input-group">
                                    <input type="number" step="0.10" name="precio_penalizacion"
                                        value="{{ $ajustes['precio_penalizacion'] ?? '2.00' }}" min="0">
                                    <span class="cfg-input-group__addon right">€</span>
                                </div>
                            </div>
                            <div>
                                <label class="cfg-label">Aviso al cliente</label>
                                <input type="text" class="cfg-input" name="mensaje_penalizacion"
                                    value="{{ $ajustes['mensaje_penalizacion'] ?? 'Los platos no consumidos tienen un cargo de {precio}€/ud.' }}"
                                    placeholder="Cargo de {precio}€ por plato sobrante">
                                <p class="cfg-hint">Usa <code>{precio}</code> para insertar el importe automáticamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /operativa --}}


    {{-- ============================================================
         TAB 3 — PRECIOS Y COBRO
    ============================================================ --}}
    <div class="cfg-tab-pane" id="tab-precios">
        <div class="row g-4">

            {{-- Tarifas + IVA + métodos --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-amber-soft);color:var(--bf-amber)"><i class="bi bi-cash-coin"></i></div>
                        <div><h5>Precios del buffet</h5><p>Tarifas por tipo de comensal</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="row g-3 mb-3">
                            @foreach([
                                ['precio_buffet_adulto','Adulto (+12 años) *'],
                                ['precio_buffet_nino',  'Niño (4–12 años) *'],
                                ['precio_buffet_bebe',  'Bebé (0–3 años) *'],
                            ] as [$name, $label])
                                <div class="col-12 col-sm-4">
                                    <label class="cfg-label">{{ $label }}</label>
                                    <div class="cfg-input-group">
                                        <input type="number" step="0.50" name="{{ $name }}"
                                            value="{{ $ajustes[$name] ?? '0.00' }}"
                                            min="0" required oninput="updateTicketPreview()">
                                        <span class="cfg-input-group__addon right">€</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="cfg-divider">
                        <div class="mb-3">
                            <label class="cfg-label">IVA aplicable *</label>
                            @php $iva = $ajustes['porcentaje_impuestos'] ?? '10'; @endphp
                            <div class="cfg-radio-group mb-2">
                                @foreach(['0'=>'0%','4'=>'4%','10'=>'10%','21'=>'21%'] as $v=>$l)
                                    <input class="cfg-radio-card" type="radio" name="porcentaje_impuestos"
                                        id="iva{{ $v }}" value="{{ $v }}"
                                        {{ $iva === $v ? 'checked':'' }}
                                        onchange="handleIvaChange(this.value)">
                                    <label class="cfg-radio-label" for="iva{{ $v }}">
                                        <i class="bi bi-percent"></i>{{ $l }}
                                    </label>
                                @endforeach
                                <input class="cfg-radio-card" type="radio" name="porcentaje_impuestos"
                                    id="ivaOtro" value="otro"
                                    {{ !in_array($iva,['0','4','10','21']) ? 'checked':'' }}
                                    onchange="handleIvaChange('otro')">
                                <label class="cfg-radio-label" for="ivaOtro">
                                    <i class="bi bi-pencil"></i>Otro
                                </label>
                            </div>
                            <input type="number" class="cfg-input" id="ivaCustomInput"
                                name="porcentaje_impuestos_custom"
                                value="{{ !in_array($iva,['0','4','10','21']) ? $iva : '' }}"
                                style="{{ !in_array($iva,['0','4','10','21']) ? '' : 'display:none' }}"
                                min="0" max="100" placeholder="Ej: 7.5"
                                oninput="updateTicketPreview()">
                        </div>
                        <hr class="cfg-divider">
                        <div>
                            <label class="cfg-label">Métodos de pago (visibles al cliente)</label>
                            <div class="d-flex gap-3 flex-wrap mt-1">
                                @foreach(['efectivo'=>['bi-cash','Efectivo'],'tarjeta'=>['bi-credit-card','Tarjeta'],'bizum'=>['bi-phone','Bizum']] as $key=>[$ico,$lbl])
                                    <label class="d-flex align-items-center gap-2 fw-semibold" style="font-size:.87rem;cursor:pointer">
                                        <label class="cfg-switch" style="transform:scale(.85)">
                                            <input type="checkbox" name="pago_{{ $key }}" value="true"
                                                {{ ($ajustes['pago_'.$key] ?? ($key!='bizum'?'true':'false')) === 'true' ? 'checked':'' }}>
                                            <span class="cfg-switch-slider"></span>
                                        </label>
                                        <i class="bi {{ $ico }}"></i> {{ $lbl }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Preview ticket --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-green-soft);color:var(--bf-green)"><i class="bi bi-receipt-cutoff"></i></div>
                        <div><h5>Preview del ticket</h5><p>Vista previa en tiempo real</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="ticket-prev mb-3">
                            <div class="t-logo" id="tpNombre">{{ $ajustes['nombre_restaurante'] ?? 'BuffeFast' }}</div>
                            <div style="font-size:.72rem;color:#9CA3AF" id="tpDir">{{ $ajustes['direccion'] ?? 'C/ Gran Vía 14, Almería' }}</div>
                            <div class="t-sep"></div>
                            <div>2× Adulto @ <span id="tpAdulto">{{ $ajustes['precio_buffet_adulto'] ?? '15.90' }}</span>€</div>
                            <div style="font-size:.72rem;color:#9CA3AF">IVA (<span id="tpIvaPct">{{ $iva }}</span>%) incl.</div>
                            <div class="t-sep"></div>
                            <div style="font-weight:900;font-size:1rem">TOTAL <span id="tpTotal">{{ number_format(($ajustes['precio_buffet_adulto'] ?? 15.90)*2, 2, ',', '.') }}</span>€</div>
                            <div class="t-sep"></div>
                            <div style="font-size:.7rem;color:#9CA3AF" id="tpPie">{{ $ajustes['texto_ticket_pie'] ?? '¡Gracias por su visita!' }}</div>
                        </div>
                        <label class="cfg-label">Pie de ticket personalizado</label>
                        <input type="text" class="cfg-input" name="texto_ticket_pie"
                            value="{{ $ajustes['texto_ticket_pie'] ?? '¡Gracias por su visita!' }}"
                            placeholder="Ej: Síguenos en @cocosushi"
                            oninput="document.getElementById('tpPie').textContent=this.value">
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /precios --}}


    {{-- ============================================================
         TAB 4 — COCINA
    ============================================================ --}}
    <div class="cfg-tab-pane" id="tab-cocina">
        <div class="row g-4">

            {{-- Alertas de tiempo --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-amber-soft);color:var(--bf-amber)"><i class="bi bi-alarm-fill"></i></div>
                        <div><h5>Alertas de tiempo</h5><p>Cuándo cambia el color de las tarjetas del kanban</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="mb-4">
                            <label class="cfg-label d-flex align-items-center gap-2">
                                <span style="width:11px;height:11px;border-radius:3px;background:#F59E0B;display:inline-block"></span>
                                Alerta amarilla (espera moderada)
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="cfg-range flex-fill" name="alerta_amarilla_min"
                                    min="1" max="30" step="1"
                                    value="{{ $ajustes['alerta_amarilla_min'] ?? 10 }}"
                                    oninput="document.getElementById('valAm').textContent=this.value">
                                <span id="valAm" style="min-width:2.5rem;text-align:right;font-weight:800;font-size:1.1rem;color:#F59E0B">{{ $ajustes['alerta_amarilla_min'] ?? 10 }}</span>
                                <span class="cfg-label mb-0">min</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="cfg-label d-flex align-items-center gap-2">
                                <span style="width:11px;height:11px;border-radius:3px;background:#EF4444;display:inline-block"></span>
                                Alerta roja (prioridad crítica)
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="cfg-range flex-fill" name="alerta_roja_min"
                                    min="5" max="60" step="5"
                                    value="{{ $ajustes['alerta_roja_min'] ?? 20 }}"
                                    oninput="document.getElementById('valRo').textContent=this.value">
                                <span id="valRo" style="min-width:2.5rem;text-align:right;font-weight:800;font-size:1.1rem;color:#EF4444">{{ $ajustes['alerta_roja_min'] ?? 20 }}</span>
                                <span class="cfg-label mb-0">min</span>
                            </div>
                        </div>
                        <div>
                            <label class="cfg-label">Pedidos servidos visibles (historial kanban)</label>
                            <div class="cfg-input-group">
                                <input type="number" name="pedidos_servidos_visibles"
                                    value="{{ $ajustes['pedidos_servidos_visibles'] ?? 15 }}" min="0" max="100">
                                <span class="cfg-input-group__addon right">pedidos</span>
                            </div>
                            <p class="cfg-hint">0 = no mostrar historial en cocina.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel cocina --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-green-soft);color:var(--bf-green)"><i class="bi bi-bell-fill"></i></div>
                        <div><h5>Panel de cocina</h5><p>Sonidos y refresco de la tablet</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="cfg-toggle-row">
                            <div class="cfg-toggle-row__text"><h6>Sonido al recibir pedido</h6><p>La tablet emite una alerta cuando llega un pedido nuevo.</p></div>
                            <label class="cfg-switch">
                                <input type="checkbox" name="sonido_cocina" value="true"
                                    {{ ($ajustes['sonido_cocina'] ?? 'true') === 'true' ? 'checked':'' }}>
                                <span class="cfg-switch-slider"></span>
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="cfg-label">Tipo de sonido</label>
                            <div class="cfg-radio-group">
                                @php $sonido = $ajustes['tipo_sonido'] ?? 'campana'; @endphp
                                @foreach(['campana'=>['bi-bell','Campana'],'chime'=>['bi-music-note','Chime'],'beep'=>['bi-soundwave','Beep'],'silencio'=>['bi-volume-mute','Silencio']] as $v=>[$ico,$l])
                                    <input class="cfg-radio-card" type="radio" name="tipo_sonido" id="snd_{{ $v }}" value="{{ $v }}" {{ $sonido === $v ? 'checked':'' }}>
                                    <label class="cfg-radio-label" for="snd_{{ $v }}"><i class="bi {{ $ico }}"></i>{{ $l }}</label>
                                @endforeach
                            </div>
                        </div>
                        <hr class="cfg-divider">
                        <div class="mb-3">
                            <label class="cfg-label">Refresco del panel (segundos) *</label>
                            <div class="cfg-input-group">
                                <input type="number" name="refresco_cocina_seg"
                                    value="{{ $ajustes['refresco_cocina_seg'] ?? 15 }}" min="5" max="120" required>
                                <span class="cfg-input-group__addon right">seg</span>
                            </div>
                            <p class="cfg-hint">Recomendado: 15 seg.</p>
                        </div>
                        <div class="cfg-toggle-row" style="margin-bottom:0">
                            <div class="cfg-toggle-row__text"><h6>Mostrar nombre del cliente en tarjeta</h6><p>El cocinero ve a quién entregar cada plato.</p></div>
                            <label class="cfg-switch">
                                <input type="checkbox" name="cocina_mostrar_nombre_cliente" value="true"
                                    {{ ($ajustes['cocina_mostrar_nombre_cliente'] ?? 'true') === 'true' ? 'checked':'' }}>
                                <span class="cfg-switch-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /cocina --}}


    {{-- ============================================================
         TAB 5 — APP CLIENTE
    ============================================================ --}}
    <div class="cfg-tab-pane" id="tab-cliente">
        <div class="row g-4">

            {{-- Mensajes personalizados --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-orange-soft);color:var(--bf-orange)"><i class="bi bi-chat-quote-fill"></i></div>
                        <div><h5>Mensajes personalizados</h5><p>Textos que ve el cliente en cada pantalla</p></div>
                    </div>
                    <div class="cfg-card__body">
                        @foreach([
                            ['mensaje_bienvenida',         'Mensaje de bienvenida',          '¡Bienvenido! Introduce el código de tu mesa.'],
                            ['mensaje_pedido_confirmado',  'Al confirmar pedido',             '¡Pedido enviado! Tu ronda está en camino.'],
                            ['mensaje_cuenta_solicitada',  'Al solicitar la cuenta',          'Un camarero se acercará a tu mesa en breve.'],
                        ] as [$name, $lbl, $ph])
                            <div class="mb-3">
                                <label class="cfg-label">{{ $lbl }}</label>
                                <input type="text" class="cfg-input" name="{{ $name }}"
                                    value="{{ $ajustes[$name] ?? $ph }}" placeholder="{{ $ph }}">
                            </div>
                        @endforeach
                        <div class="mb-3">
                            <label class="cfg-label">Aviso de alérgenos</label>
                            <textarea class="cfg-input" name="aviso_alergenos" rows="2"
                                placeholder="Para información sobre alérgenos, consulte con el personal.">{{ $ajustes['aviso_alergenos'] ?? 'Para información sobre alérgenos, consulte con nuestro personal.' }}</textarea>
                        </div>
                        <div>
                            <label class="cfg-label">Aviso legal (pie de carta)</label>
                            <textarea class="cfg-input" name="aviso_legal_carta" rows="2"
                                placeholder="Precios con IVA incluido…">{{ $ajustes['aviso_legal_carta'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visibilidad + mantenimiento --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card mb-4">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-purple-soft);color:var(--bf-purple)"><i class="bi bi-eye-fill"></i></div>
                        <div><h5>Visibilidad</h5><p>Qué puede ver y hacer el cliente</p></div>
                    </div>
                    <div class="cfg-card__body">
                        @foreach([
                            ['mostrar_precios_carta',     'false', 'Mostrar precios en la carta',     'Desactivado = modo buffet libre sin precios visibles.'],
                            ['mostrar_historial_cliente', 'true',  'Historial de pedidos visible',     'El cliente ve todas sus rondas de la sesión.'],
                            ['permitir_solicitar_cuenta', 'true',  'Botón "Pedir la cuenta"',          'Muestra el botón para solicitar el cobro.'],
                            ['alergenos_aviso_visible',   'true',  'Aviso de alérgenos en carta',      'Muestra el banner con el texto configurado.'],
                            ['mostrar_wifi_redes',        'true',  'WiFi y redes en pantalla de cuenta','Muestra la tarjeta informativa al cliente.'],
                        ] as [$name, $default, $title, $desc])
                            <div class="cfg-toggle-row">
                                <div class="cfg-toggle-row__text"><h6>{{ $title }}</h6><p>{{ $desc }}</p></div>
                                <label class="cfg-switch">
                                    <input type="checkbox" name="{{ $name }}" value="true"
                                        {{ ($ajustes[$name] ?? $default) === 'true' ? 'checked':'' }}>
                                    <span class="cfg-switch-slider"></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-red-soft);color:var(--bf-red)"><i class="bi bi-door-closed-fill"></i></div>
                        <div><h5>Modo mantenimiento</h5><p>Bloquea el acceso de nuevos clientes</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="cfg-toggle-row mb-3">
                            <div class="cfg-toggle-row__text"><h6>Activar modo mantenimiento</h6><p>Muestra una pantalla de pausa a quien intente entrar.</p></div>
                            <label class="cfg-switch">
                                <input type="checkbox" name="modo_mantenimiento" value="true"
                                    {{ ($ajustes['modo_mantenimiento'] ?? 'false') === 'true' ? 'checked':'' }}>
                                <span class="cfg-switch-slider"></span>
                            </label>
                        </div>
                        <label class="cfg-label">Mensaje de cierre temporal</label>
                        <input type="text" class="cfg-input" name="mensaje_cierre_temporal"
                            value="{{ $ajustes['mensaje_cierre_temporal'] ?? 'Volvemos en breve. ¡Gracias por tu paciencia!' }}"
                            placeholder="Ej: Estamos preparando algo especial. Volvemos pronto.">
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /cliente --}}


    {{-- ============================================================
         TAB 6 — SEGURIDAD
    ============================================================ --}}
    <div class="cfg-tab-pane" id="tab-seguridad">
        <div class="row g-4">

            {{-- Acceso de mesas --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-blue-soft);color:var(--bf-blue)"><i class="bi bi-key-fill"></i></div>
                        <div><h5>Acceso de mesas</h5><p>Códigos y expiración de sesión</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <div class="mb-3">
                            <label class="cfg-label">Longitud del código de mesa *</label>
                            @php $longCod = $ajustes['longitud_codigo_mesa'] ?? '6'; @endphp
                            <div class="cfg-radio-group">
                                @foreach(['4'=>'4 car.','5'=>'5 car.','6'=>'6 car.','8'=>'8 car.'] as $v=>$l)
                                    <input class="cfg-radio-card" type="radio" name="longitud_codigo_mesa"
                                        id="cod{{ $v }}" value="{{ $v }}" {{ $longCod === $v ? 'checked':'' }}>
                                    <label class="cfg-radio-label" for="cod{{ $v }}">
                                        <i class="bi bi-hash"></i>{{ $l }}
                                    </label>
                                @endforeach
                            </div>
                            <p class="cfg-hint">Más caracteres = más seguro pero más difícil de teclear.</p>
                        </div>
                        <div class="mb-3">
                            <label class="cfg-label">Expiración de sesión cliente *</label>
                            <div class="cfg-input-group">
                                <input type="number" name="expiracion_sesion_min"
                                    value="{{ $ajustes['expiracion_sesion_min'] ?? 240 }}" min="30" max="1440" required>
                                <span class="cfg-input-group__addon right">min</span>
                            </div>
                            <p class="cfg-hint">240 min = 4 horas. Recomendado para cenas largas.</p>
                        </div>
                        <div>
                            <label class="cfg-label">Intentos fallidos antes de bloqueo *</label>
                            <div class="cfg-input-group">
                                <input type="number" name="intentos_codigo_erroneo"
                                    value="{{ $ajustes['intentos_codigo_erroneo'] ?? 5 }}" min="1" max="20" required>
                                <span class="cfg-input-group__addon right">intentos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logs y protecciones --}}
            <div class="col-12 col-lg-6">
                <div class="cfg-card mb-4">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-purple-soft);color:var(--bf-purple)"><i class="bi bi-shield-check"></i></div>
                        <div><h5>Protecciones y logs</h5><p>Bloqueos automáticos y auditoría</p></div>
                    </div>
                    <div class="cfg-card__body">
                        @foreach([
                            ['bloqueo_ip_activo',        'true',  'Bloqueo automático de IP',     'Bloquea IPs que superen el límite de intentos fallidos.'],
                            ['registro_log_pedidos',     'true',  'Log de pedidos en Laravel',    'Registra cada pedido para auditoría interna.'],
                            ['notificacion_email_admin', 'false', 'Alertas por email al admin',   'Notifica errores críticos o múltiples intentos fallidos.'],
                        ] as [$name, $default, $title, $desc])
                            <div class="cfg-toggle-row">
                                <div class="cfg-toggle-row__text"><h6>{{ $title }}</h6><p>{{ $desc }}</p></div>
                                <label class="cfg-switch">
                                    <input type="checkbox" name="{{ $name }}" value="true"
                                        {{ ($ajustes[$name] ?? $default) === 'true' ? 'checked':'' }}>
                                    <span class="cfg-switch-slider"></span>
                                </label>
                            </div>
                        @endforeach
                        <hr class="cfg-divider">
                        <label class="cfg-label">Email de alertas del administrador</label>
                        <input type="email" class="cfg-input" name="email_notificaciones_admin"
                            value="{{ $ajustes['email_notificaciones_admin'] ?? '' }}"
                            placeholder="admin@mirestaurante.com">
                    </div>
                </div>

                {{-- Zona peligrosa --}}
                <div class="cfg-card" style="border-color:rgba(239,68,68,.25)">
                    <div class="cfg-card__head">
                        <div class="cfg-card__icon" style="background:var(--bf-red-soft);color:var(--bf-red)"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div><h5 style="color:var(--bf-red)">Zona peligrosa</h5><p>Acciones irreversibles del sistema</p></div>
                    </div>
                    <div class="cfg-card__body">
                        <form action="{{ route('admin.configuracion.resetear') }}" method="POST"
                              onsubmit="return confirm('¿Seguro? Esto sobreescribirá TODOS los ajustes con los valores de fábrica.')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger fw-bold rounded-pill px-4 btn-sm">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Restaurar valores de fábrica
                            </button>
                            <p class="cfg-hint mt-2">No afecta a mesas, pedidos ni empleados existentes.</p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /seguridad --}}

    {{-- ── Botón guardar flotante (solo afecta al form global) ── --}}
    <div class="cfg-save-bar">
        <button type="submit" class="btn-save">
            <i class="bi bi-floppy-fill"></i> Guardar configuración
        </button>
    </div>

</form>{{-- /cfgForm --}}


{{-- ============================================================
     TAB 7 — EQUIPO (form propio, separado del form global)
============================================================ --}}
<div class="cfg-tab-pane" id="tab-equipo">
    <div class="row g-4">

        {{-- Lista + formulario de nuevo empleado --}}
        <div class="col-12 col-xl-8">
            <div class="cfg-card">
                <div class="cfg-card__head" style="padding-bottom:.8rem">
                    <div class="cfg-card__icon" style="background:var(--bf-blue-soft);color:var(--bf-blue)"><i class="bi bi-people-fill"></i></div>
                    <div class="flex-fill"><h5>Personal del sistema</h5><p>Administradores y personal de cocina</p></div>
                    <button type="button" class="btn btn-dark btn-sm fw-bold rounded-pill px-3"
                            onclick="document.getElementById('formNuevo').style.display = document.getElementById('formNuevo').style.display === 'none' ? '' : 'none'">
                        <i class="bi bi-person-plus-fill me-1"></i>Nuevo
                    </button>
                </div>

                {{-- Form colapsable para crear empleado --}}
                <div id="formNuevo" style="display:none;padding:0 1.4rem 1.1rem;border-bottom:1.5px solid var(--bf-g100)">
                    <form action="{{ route('admin.configuracion.empleado.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="cfg-label">Nombre *</label>
                                <input type="text" class="cfg-input" name="nombre" placeholder="Ej: María García" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="cfg-label">Rol *</label>
                                <select class="cfg-input" name="rol" required>
                                    <option value="admin">Admin</option>
                                    <option value="cocina">Cocina</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="cfg-label">Email *</label>
                                <input type="email" class="cfg-input" name="email" placeholder="empleado@…" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="cfg-label">Contraseña *</label>
                                <input type="password" class="cfg-input" name="password" placeholder="Mín. 6 car." required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="cfg-label">Repetir *</label>
                                <input type="password" class="cfg-input" name="password_confirmation" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark fw-bold rounded-pill px-4 btn-sm">
                                    <i class="bi bi-person-check-fill me-1"></i>Crear empleado
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabla de empleados --}}
                <div class="table-responsive">
                    <table class="table emp-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th class="text-end pe-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empleados as $emp)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $emp->nombre }}</td>
                                    <td>
                                        @if($emp->rol === 'admin')
                                            <span class="badge rounded-pill" style="background:#111827;color:#fff;font-size:.73rem">Admin</span>
                                        @else
                                            <span class="badge rounded-pill" style="background:var(--bf-amber);color:#fff;font-size:.73rem">Cocina</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $emp->email }}</td>
                                    <td class="text-end pe-4">
                                        @if(auth()->id() !== $emp->id)
                                            <form action="{{ route('admin.configuracion.empleado.destroy', $emp) }}"
                                                  method="POST" style="display:inline"
                                                  onsubmit="return confirm('¿Eliminar a {{ addslashes($emp->nombre) }}? Esta acción no se puede deshacer.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="font-size:.78rem">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-light text-muted border" style="font-size:.72rem">Tú</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        <i class="bi bi-people opacity-25 fs-3 d-block mb-1"></i>
                                        No hay empleados registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Resumen equipo --}}
        <div class="col-12 col-xl-4">
            <div class="cfg-card">
                <div class="cfg-card__head">
                    <div class="cfg-card__icon" style="background:var(--bf-green-soft);color:var(--bf-green)"><i class="bi bi-bar-chart-steps"></i></div>
                    <div><h5>Resumen del equipo</h5><p>Distribución por rol</p></div>
                </div>
                <div class="cfg-card__body">
                    @php
                        $nAdmin  = $empleados->where('rol','admin')->count();
                        $nCocina = $empleados->where('rol','cocina')->count();
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background:var(--bf-g50)">
                        <div>
                            <div class="cfg-label mb-0">Administradores</div>
                            <div style="font-size:1.5rem;font-weight:800;color:var(--bf-g900)">{{ $nAdmin }}</div>
                        </div>
                        <div class="cfg-card__icon" style="background:#111827;color:#fff;width:40px;height:40px">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:var(--bf-g50)">
                        <div>
                            <div class="cfg-label mb-0">Personal de cocina</div>
                            <div style="font-size:1.5rem;font-weight:800;color:var(--bf-g900)">{{ $nCocina }}</div>
                        </div>
                        <div class="cfg-card__icon" style="background:var(--bf-amber-soft);color:var(--bf-amber);width:40px;height:40px">
                            <i class="bi bi-fire"></i>
                        </div>
                    </div>
                    <hr class="cfg-divider">
                    <p class="cfg-hint text-center mb-0">{{ $empleados->count() }} {{ $empleados->count() === 1 ? 'usuario':'usuarios' }} en el sistema</p>
                </div>
            </div>
        </div>

    </div>
</div>{{-- /equipo --}}


{{-- ── SCRIPTS ──────────────────────────────────────────────────────── --}}
<script>
// ── 1. Navegación de tabs ─────────────────────────────────────────────
document.querySelectorAll('.cfg-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cfg-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.cfg-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});

// ── 2. Sync color picker con la pill de preview ────────────────────────
function syncColor(name) {
    const val = document.getElementById('color' + name).value;
    document.getElementById('dot'  + name).style.background = val;
    document.getElementById('text' + name).textContent      = val;
}

// ── 3. Preview del logo al escribir la URL ─────────────────────────────
function previewLogo(url) {
    const wrap = document.getElementById('logoWrap');
    const img  = document.getElementById('logoImg');
    if (url) { img.src = url; wrap.style.display = ''; }
    else      { wrap.style.display = 'none'; }
}

// ── 4. IVA: mostrar/ocultar input de valor personalizado ──────────────
function handleIvaChange(val) {
    document.getElementById('ivaCustomInput').style.display = val === 'otro' ? '' : 'none';
    if (val !== 'otro') updateTicketPreview();
}

// ── 5. Preview del ticket en tiempo real ──────────────────────────────
function updateTicketPreview() {
    const adulto = parseFloat(document.querySelector('[name=precio_buffet_adulto]')?.value) || 0;
    const ivaEl  = document.querySelector('input[name="porcentaje_impuestos"]:checked');
    const iva    = ivaEl?.value === 'otro'
        ? (parseFloat(document.getElementById('ivaCustomInput')?.value) || 0)
        : (parseFloat(ivaEl?.value) || 0);

    document.getElementById('tpAdulto').textContent = adulto.toFixed(2);
    document.getElementById('tpIvaPct').textContent = iva;
    document.getElementById('tpTotal').textContent  = (adulto * 2).toFixed(2).replace('.',',');
}

// ── 6. Toggle campos de penalización (opacidad + pointer-events) ──────
function togglePenFields(checked) {
    const el = document.getElementById('penFields');
    el.style.opacity       = checked ? '1'   : '.4';
    el.style.pointerEvents = checked ? ''    : 'none';
}

// ── 7. Modo pánico AJAX ───────────────────────────────────────────────
function togglePanico() {
    if (!confirm('¿Seguro? Esto pausará o reanudará los pedidos de todos los clientes.')) return;

    fetch('{{ route("admin.configuracion.panico") }}', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { alert('Error: ' + data.mensaje); return; }

        const activo = data.estado === 'true';
        document.getElementById('btnPanico').classList.toggle('active', activo);
        document.getElementById('textoPanico').textContent = activo ? 'PÁNICO ACTIVO' : 'Modo Pánico';

        // Toast de confirmación (se elimina solo a los 4s)
        const toast = document.createElement('div');
        toast.className = 'cfg-alert ' + (activo ? 'danger' : 'success');
        toast.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;max-width:360px;animation:fadeIn .3s ease;box-shadow:0 4px 16px rgba(0,0,0,.12)';
        toast.innerHTML = `<i class="bi bi-${activo ? 'exclamation-triangle-fill' : 'check-circle-fill'}" style="font-size:1rem;flex-shrink:0"></i><span>${data.mensaje}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    })
    .catch(() => alert('Error de conexión. Inténtalo de nuevo.'));
}

// ── Init: estado inicial de penalización al cargar la página ─────────
document.addEventListener('DOMContentLoaded', () => {
    const chk = document.getElementById('chkPen');
    if (chk) togglePenFields(chk.checked);
});
</script>

</x-layouts.admin>