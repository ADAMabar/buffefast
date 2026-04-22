<x-layouts.admin>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Configuración del Sistema</h1>
            <p class="text-muted mb-0">Ajusta los parámetros globales de tu restaurante y app.</p>
        </div>
    </div>


    <style>
        /* Tarjetas más compactas */
        .cfg-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            padding: 1.25rem; /* Menos espacio muerto */
            height: 100%;
            transition: all 0.2s;
        }
        .cfg-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0,0,0,.03);
        }
        .cfg-card__head {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #f3f4f6; /* Separador sutil */
        }
        .cfg-card__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .cfg-card__head h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-main);
        }
        .cfg-card__head p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Inputs y Labels más profesionales */
        .cfg-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        .form-control, .input-group-text {
            font-size: 0.875rem;
            padding: 0.45rem 0.75rem; /* Inputs un poco más finos */
        }
        .cfg-hint {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 0.2rem;
            margin-bottom: 0;
            line-height: 1.2;
        }
        
        /* Navegación de Pestañas */
        .nav-pills .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:hover {
            background-color: #f3f4f6;
        }
        .nav-pills .nav-link.active {
            background-color: var(--primary-orange);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 122, 0, 0.25);
        }

        /* Botón Guardar Flotante */
        .cfg-save-bar {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1050;
        }
        .btn-save {
            background: #111827;
            color: #fff;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 8px 20px rgba(17,24,39,.2);
            transition: all 0.2s;
        }
        .btn-save:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(17,24,39,.3);
        }
    </style>

        <ul class="nav nav-pills mb-4 gap-2 pb-2" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-identidad-btn" data-bs-toggle="pill" data-bs-target="#tab-identidad" type="button" role="tab">
                <i class="bi bi-building me-1"></i> Identidad
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-operativa-btn" data-bs-toggle="pill" data-bs-target="#tab-operativa" type="button" role="tab">
                <i class="bi bi-stopwatch-fill me-1"></i> Operativa
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-precios-btn" data-bs-toggle="pill" data-bs-target="#tab-precios" type="button" role="tab">
                <i class="bi bi-receipt me-1"></i> Precios
            </button>
        </li>
    </ul>

<ul class="nav nav-pills mb-4 gap-2 pb-2" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-identidad-btn" data-bs-toggle="pill" data-bs-target="#tab-identidad" type="button" role="tab"><i class="bi bi-building me-1"></i> Identidad</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-operativa-btn" data-bs-toggle="pill" data-bs-target="#tab-operativa" type="button" role="tab"><i class="bi bi-stopwatch-fill me-1"></i> Operativa</button>
        </li>
        </ul>

    
    <form action="{{ route('admin.configuracion.ajustes') }}" method="POST" id="cfgForm">
        @csrf

        <div class="tab-content" id="configTabsContent">
            
          
             @include('components.componentsConfiguracion.identidad')
             @include('components.componentsConfiguracion.precio')

            
        </div>

        <div class="cfg-save-bar">
            <button type="submit" class="btn-save"><i class="bi bi-floppy-fill"></i> Guardar configuración</button>
        </div>
    </form>

    <script>
        // Preview del Logo
        const logoInput = document.getElementById('logoUrlInput');
        if(logoInput) {
            logoInput.addEventListener('input', function(e) {
                const wrap = document.getElementById('logoWrap');
                const img = document.getElementById('logoImg');
                if(e.target.value) { img.src = e.target.value; wrap.style.setProperty('display', 'flex', 'important'); }
                else { wrap.style.setProperty('display', 'none', 'important'); }
            });
        }

        // Slider de Rondas
        const rondaSlider = document.getElementById('rondaSlider');
        const rondaBadge = document.getElementById('valRondaBadge');
        if (rondaSlider && rondaBadge) {
            rondaSlider.addEventListener('input', function(e) {
                rondaBadge.textContent = e.target.value + ' min';
                if(e.target.value === '0') {
                    rondaBadge.classList.replace('bg-dark', 'bg-danger');
                    rondaBadge.textContent = 'Sin espera';
                } else {
                    rondaBadge.classList.replace('bg-danger', 'bg-dark');
                }
            });
        }

        // Switch de Penalización
        const chkPen = document.getElementById('chkPen');
        const penFields = document.getElementById('penFields');
        function togglePenalizacion() {
            if (chkPen.checked) { penFields.style.opacity = '1'; penFields.style.pointerEvents = 'auto'; }
            else { penFields.style.opacity = '0.4'; penFields.style.pointerEvents = 'none'; }
        }
        if (chkPen && penFields) {
            chkPen.addEventListener('change', togglePenalizacion);
            togglePenalizacion();
        }
    </script>
</x-layouts.admin>