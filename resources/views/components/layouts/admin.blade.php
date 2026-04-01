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
            <a href="#" class="nav-link-admin">
                <i class="bi bi-journal-richtext"></i> Menú de Platos
            </a>
            <a href="#" class="nav-link-admin">
                <i class="bi bi-graph-up-arrow"></i> Historial / Ventas
            </a>
            <a href="#" class="nav-link-admin">
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