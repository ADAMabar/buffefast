<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuffeFast - TPV & Administración</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
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

        /* Área Principal */
        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 2rem;
        }

        /* Tarjetas Bento para Mesas */
        .mesa-card {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .mesa-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background-color: #994c04;
        }

        .btn-primary {
            background-color: #FF7A00;
            border: none;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .btn-eliminar {
            background-color: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.25);
            font-size: 0.85rem;
            margin-top: 4px;
            transition: all 0.2s ease;
        }

        .btn-eliminar:hover {
            background-color: rgba(239, 68, 68, 0.2);
            color: #b91c1c;
        }

        /* =================================================================
        RETOQUES ESPECIALES PARA EL HISTORIAL DE VENTAS
        ================================================================= */
        :root {
            --bf-orange: #FF7A00;
            --bf-orange-soft: rgba(255, 122, 0, .1);
            --bf-dark: #111827;
            --bf-card-radius: 16px;
        }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: var(--bf-card-radius);
            border: 1px solid #E5E7EB;
            background: #fff;
            transition: transform .15s, box-shadow .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .07) !important;
        }

        .stat-card__accent {
            width: 4px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .stat-card__label {
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: .25rem;
        }

        .stat-card__value {
            font-size: 1.65rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-bottom: .2rem;
        }

        .stat-card__sub {
            font-size: .76rem;
            color: #6B7280;
        }

        /* ── Pills de período ── */
        .pill-filter {
            cursor: pointer;
            border-radius: 50rem;
            border: 1.5px solid #E5E7EB;
            padding: .35rem 1rem;
            font-size: .8rem;
            font-weight: 700;
            background: #fff;
            color: #374151;
            transition: all .15s;
            white-space: nowrap;
        }

        .pill-filter:hover {
            border-color: #374151;
            background: #F9FAFB;
        }

        .pill-filter.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        /* ── Gráfica barras ── */
        .chart-wrap {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 100px;
        }

        .chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .chart-bar {
            width: 100%;
            border-radius: 4px 4px 0 0;
            min-height: 3px;
            background: linear-gradient(to top, #111827 0%, #374151 100%);
            transition: height .35s cubic-bezier(.34, 1.4, .64, 1);
        }

        .chart-bar.active {
            background: linear-gradient(to top, var(--bf-orange) 0%, #ffaa55 100%);
        }

        .chart-bar:hover {
            opacity: .8;
        }

        .chart-label {
            font-size: .58rem;
            color: #9CA3AF;
            white-space: nowrap;
        }

        /* ── Barra método de pago ── */
        .metodo-strip {
            height: 5px;
            background: #F3F4F6;
            border-radius: 3px;
            overflow: hidden;
            margin-top: .2rem;
        }

        .metodo-strip__fill {
            height: 100%;
            border-radius: 3px;
            transition: width .4s;
        }

        /* ── Tabla ── */
        .h-table thead th {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9CA3AF;
            background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
            padding: .75rem 1rem;
            white-space: nowrap;
        }

        .h-table tbody td {
            padding: .8rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F3F4F6;
            font-size: .88rem;
        }

        .h-table tbody tr:last-child td {
            border-bottom: none;
        }

        .ticket-row {
            cursor: pointer;
        }

        .ticket-row:hover td {
            background: #FAFAFA;
        }

        /* ── Badges de método ── */
        .badge-efectivo {
            background: #d1fae5;
            color: #065f46;
            border-radius: 50rem;
            padding: .28rem .75rem;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-tarjeta {
            background: #dbeafe;
            color: #1e40af;
            border-radius: 50rem;
            padding: .28rem .75rem;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-bizum {
            background: #ede9fe;
            color: #5b21b6;
            border-radius: 50rem;
            padding: .28rem .75rem;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-transferencia {
            background: #fef3c7;
            color: #92400e;
            border-radius: 50rem;
            padding: .28rem .75rem;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-anulada {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 50rem;
            padding: .2rem .6rem;
            font-size: .66rem;
            font-weight: 800;
        }

        /* ── Variación ── */
        .var-up {
            background: rgba(16, 185, 129, .1);
            color: #065f46;
            border-radius: 50rem;
            padding: .15rem .6rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .var-down {
            background: rgba(239, 68, 68, .1);
            color: #991b1b;
            border-radius: 50rem;
            padding: .15rem .6rem;
            font-size: .75rem;
            font-weight: 700;
        }

        /* ── Animaciones ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .anim {
            animation: fadeUp .3s ease both;
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
            <a href="{{ route('admin.historial') }}" class="nav-link-admin">
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