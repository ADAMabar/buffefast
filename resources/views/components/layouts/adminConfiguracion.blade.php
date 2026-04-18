<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        /* ── Variables de color BuffeFast ── */
        :root {
            --bf-orange: #FF7A00;
            --bf-orange-soft: rgba(255, 122, 0, 0.08);
            --bf-orange-border: rgba(255, 122, 0, 0.25);
            --bf-red: #EF4444;
            --bf-red-soft: rgba(239, 68, 68, 0.08);
            --bf-green: #10B981;
            --bf-green-soft: rgba(16, 185, 129, 0.08);
            --bf-blue: #3B82F6;
            --bf-blue-soft: rgba(59, 130, 246, 0.08);
            --bf-purple: #8B5CF6;
            --bf-purple-soft: rgba(139, 92, 246, 0.08);
            --bf-amber: #F59E0B;
            --bf-amber-soft: rgba(245, 158, 11, 0.08);
            --bf-gray-50: #F9FAFB;
            --bf-gray-100: #F3F4F6;
            --bf-gray-200: #E5E7EB;
            --bf-gray-500: #6B7280;
            --bf-gray-700: #374151;
            --bf-gray-900: #111827;
            --bf-radius-card: 18px;
            --bf-radius-input: 10px;
            --bf-shadow-card: 0 1px 3px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.04);
            --bf-shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.08);
        }

        /* ── Layout página ── */
        .cfg-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .cfg-header__title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--bf-gray-900);
            margin-bottom: .2rem;
        }

        .cfg-header__sub {
            color: var(--bf-gray-500);
            font-size: .92rem;
        }

        /* ── Tabs ── */
        .cfg-tabs {
            display: flex;
            gap: .5rem;
            margin-bottom: 2rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid var(--bf-gray-200);
            flex-wrap: wrap;
        }

        .cfg-tab-btn {
            display: flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem 1.1rem;
            border: none;
            background: transparent;
            border-radius: 50px;
            font-size: .88rem;
            font-weight: 600;
            color: var(--bf-gray-500);
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }

        .cfg-tab-btn i {
            font-size: 1rem;
        }

        .cfg-tab-btn:hover {
            background: var(--bf-gray-100);
            color: var(--bf-gray-900);
        }

        .cfg-tab-btn.active {
            background: var(--bf-orange-soft);
            color: var(--bf-orange);
            box-shadow: inset 0 0 0 1.5px var(--bf-orange-border);
        }

        .cfg-tab-pane {
            display: none;
        }

        .cfg-tab-pane.active {
            display: block;
        }

        /* ── Tarjeta de sección ── */
        .cfg-card {
            background: #fff;
            border-radius: var(--bf-radius-card);
            border: 1px solid var(--bf-gray-200);
            box-shadow: var(--bf-shadow-card);
            overflow: hidden;
            transition: box-shadow .2s;
            height: 100%;
        }

        .cfg-card:hover {
            box-shadow: var(--bf-shadow-hover);
        }

        .cfg-card__head {
            padding: 1.25rem 1.5rem 0;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .cfg-card__icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .cfg-card__head h5 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: var(--bf-gray-900);
        }

        .cfg-card__head p {
            font-size: .8rem;
            color: var(--bf-gray-500);
            margin: .1rem 0 0;
        }

        .cfg-card__body {
            padding: 1.25rem 1.5rem 1.5rem;
        }

        /* ── Inputs ── */
        .cfg-label {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--bf-gray-500);
            margin-bottom: .4rem;
            display: block;
        }

        .cfg-input {
            width: 100%;
            padding: .6rem .85rem;
            border: 1.5px solid var(--bf-gray-200);
            border-radius: var(--bf-radius-input);
            font-size: .92rem;
            color: var(--bf-gray-900);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .cfg-input:focus {
            border-color: var(--bf-orange);
            box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.12);
        }

        .cfg-input[type="color"] {
            padding: .3rem .4rem;
            height: 42px;
            cursor: pointer;
        }

        .cfg-hint {
            font-size: .78rem;
            color: var(--bf-gray-500);
            margin-top: .3rem;
        }

        /* ── Toggle switch ── */
        .cfg-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            border: 1.5px solid var(--bf-gray-200);
            margin-bottom: .6rem;
            transition: border-color .2s, background .2s;
        }

        .cfg-toggle-row:hover {
            border-color: var(--bf-gray-500);
            background: var(--bf-gray-50);
        }

        .cfg-toggle-row__text h6 {
            font-size: .9rem;
            font-weight: 600;
            margin: 0;
            color: var(--bf-gray-900);
        }

        .cfg-toggle-row__text p {
            font-size: .78rem;
            color: var(--bf-gray-500);
            margin: .1rem 0 0;
        }

        .cfg-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .cfg-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .cfg-switch-slider {
            position: absolute;
            inset: 0;
            background: var(--bf-gray-200);
            border-radius: 99px;
            cursor: pointer;
            transition: background .25s;
        }

        .cfg-switch-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: transform .25s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .cfg-switch input:checked+.cfg-switch-slider {
            background: var(--bf-orange);
        }

        .cfg-switch input:checked+.cfg-switch-slider::before {
            transform: translateX(20px);
        }

        /* ── Separador ── */
        .cfg-divider {
            border: none;
            border-top: 1.5px solid var(--bf-gray-100);
            margin: 1.25rem 0;
        }

        /* ── Badge de color picker ── */
        .cfg-color-pair {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .cfg-color-preview {
            height: 6px;
            border-radius: 3px;
            margin-top: .4rem;
        }

        /* ── Campo con prefijo/sufijo ── */
        .cfg-input-group {
            display: flex;
            border: 1.5px solid var(--bf-gray-200);
            border-radius: var(--bf-radius-input);
            overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }

        .cfg-input-group:focus-within {
            border-color: var(--bf-orange);
            box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.12);
        }

        .cfg-input-group__addon {
            background: var(--bf-gray-100);
            padding: .6rem .85rem;
            font-size: .85rem;
            font-weight: 600;
            color: var(--bf-gray-500);
            border-right: 1.5px solid var(--bf-gray-200);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .cfg-input-group__addon.right {
            border-right: none;
            border-left: 1.5px solid var(--bf-gray-200);
        }

        .cfg-input-group input {
            flex: 1;
            border: none;
            padding: .6rem .85rem;
            font-size: .92rem;
            color: var(--bf-gray-900);
            outline: none;
            background: #fff;
            min-width: 0;
        }

        /* ── Radio cards ── */
        .cfg-radio-group {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .cfg-radio-card {
            display: none;
        }

        .cfg-radio-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem;
            padding: .75rem 1.1rem;
            border: 1.5px solid var(--bf-gray-200);
            border-radius: 12px;
            cursor: pointer;
            font-size: .82rem;
            font-weight: 600;
            color: var(--bf-gray-500);
            transition: all .2s;
            min-width: 72px;
        }

        .cfg-radio-label i {
            font-size: 1.3rem;
        }

        .cfg-radio-label:hover {
            border-color: var(--bf-orange);
            color: var(--bf-orange);
            background: var(--bf-orange-soft);
        }

        .cfg-radio-card:checked+.cfg-radio-label {
            border-color: var(--bf-orange);
            background: var(--bf-orange-soft);
            color: var(--bf-orange);
            box-shadow: 0 0 0 2px var(--bf-orange-border);
        }

        /* ── Botón Pánico ── */
        .btn-panico {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.4rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: .88rem;
            border: 2px solid var(--bf-red);
            color: var(--bf-red);
            background: transparent;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-panico:hover,
        .btn-panico.active {
            background: var(--bf-red);
            color: #fff;
        }

        .btn-panico.active {
            animation: pulso 2s infinite;
        }

        @keyframes pulso {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, .4);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }
        }

        /* ── Botón guardar flotante ── */
        .cfg-save-bar {
            position: sticky;
            bottom: 1.5rem;
            display: flex;
            justify-content: flex-end;
            pointer-events: none;
            z-index: 100;
            margin-top: 2rem;
        }

        .btn-save {
            pointer-events: all;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 2rem;
            background: var(--bf-orange);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(255, 122, 0, 0.35);
            transition: all .2s;
        }

        .btn-save:hover {
            background: #e06d00;
            box-shadow: 0 6px 28px rgba(255, 122, 0, 0.45);
            transform: translateY(-1px);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        /* ── Alert inline ── */
        .cfg-alert {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .85rem 1.1rem;
            border-radius: 12px;
            font-size: .88rem;
            margin-bottom: 1.5rem;
        }

        .cfg-alert.success {
            background: rgba(16, 185, 129, .08);
            border: 1.5px solid rgba(16, 185, 129, .25);
            color: #065F46;
        }

        .cfg-alert.danger {
            background: rgba(239, 68, 68, .08);
            border: 1.5px solid rgba(239, 68, 68, .25);
            color: #991B1B;
        }

        /* ── Preview ticket ── */
        .ticket-preview {
            background: var(--bf-gray-50);
            border: 1.5px dashed var(--bf-gray-200);
            border-radius: 12px;
            padding: 1.25rem;
            font-family: 'Courier New', monospace;
            font-size: .82rem;
            line-height: 1.8;
            color: var(--bf-gray-700);
            text-align: center;
        }

        .ticket-preview .ticket-logo {
            font-size: 1.2rem;
            font-weight: 900;
            letter-spacing: .05em;
            color: var(--bf-gray-900);
        }

        .ticket-preview .ticket-sep {
            border-top: 1.5px dashed var(--bf-gray-200);
            margin: .5rem 0;
        }

        /* ── Slider range ── */
        .cfg-range {
            width: 100%;
            accent-color: var(--bf-orange);
            cursor: pointer;
        }

        /* ── Color indicator pill ── */
        .color-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .25rem .7rem;
            border-radius: 99px;
            border: 1.5px solid var(--bf-gray-200);
            font-size: .78rem;
            font-weight: 600;
            color: var(--bf-gray-700);
            background: #fff;
        }

        .color-pill__dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        :root {
            --bg-light: #F4F5F7;
            --primary-orange: #FF7A00;
            --sidebar-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-light: #E5E7EB;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar 2026 */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            z-index: 1000;
        }

        .nav-link-admin {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.875rem 1rem;
            color: var(--text-muted);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
        }

        .nav-link-admin:hover,
        .nav-link-admin.active {
            background-color: rgba(255, 122, 0, 0.08);
            color: var(--primary-orange);
        }

        .nav-link-admin i {
            font-size: 1.25rem;
        }
    </style>
</head>

<body>

    <aside class="sidebar shadow-sm">
        <div class="d-flex align-items-center gap-3 mb-5 px-2">
            <div class="d-inline-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255,122,0,0.1); border: 1px solid var(--primary-orange);">
                <span style="color: var(--primary-orange); font-weight: 700; font-size: 1.2rem;">B</span>
            </div>
            <h2 class="h5 mb-0 fw-bold">Admin TPV</h2>
        </div>

        <nav class="d-flex flex-column flex-grow-1">
            <a href="{{ route('admin.mesas') }}" class="nav-link-admin active">
                <i class="bi bi-grid-fill"></i> Gestión de Mesas
            </a>
            <a href="{{ route('admin.platos.index') }}" class="nav-link-admin">
                <i class="bi bi-journal-richtext"></i> Menú de Platos
            </a>
            <a href="#" class="nav-link-admin">
                <i class="bi bi-graph-up-arrow"></i> Historial / Ventas
            </a>
            <a href="{{ route('admin.configuracion.index') }}" class="nav-link-admin">
                <i class="bi bi-gear-fill"></i> Configuración
            </a>
        </nav>

        <div class="mt-auto border-top pt-4">
            <div class="d-flex align-items-center gap-3 mb-3 px-2">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill text-secondary"></i>
                </div>
                <div>
                    <p class="mb-0 fw-bold small">{{ auth()->user()->nombre ?? 'Admin' }}</p>
                    <p class="mb-0 text-muted" style="font-size: 0.75rem;">Administrador</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light w-100 rounded-3 text-start text-danger fw-medium">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </button>
            </form>
        </div>

    </aside>

    <main class="main-content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0 mb-4" role="alert"
                style="background-color: #ECFDF5; color: #065F46;">
                <p class="mb-0 fw-medium">{{ $message }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>