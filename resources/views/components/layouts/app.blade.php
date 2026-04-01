<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuffeFast - Panel de Staff</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #F4F5F7;
            /* Un gris apenas perceptible para que las tarjetas blancas destaquen */
            --primary-orange: #FF7A00;
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --border-light: #E5E7EB;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Navbar Minimalista */
        .navbar-2026 {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
        }

        /* Utilidad para Tarjetas Bento */
        .bento-card {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bento-card:hover {
            box-shadow: 0 10px 30px rgba(255, 122, 0, 0.08);
            border-color: rgba(255, 122, 0, 0.3);
        }

        /* Scrollbar invisible para columnas Kanban */
        .kanban-col {
            height: calc(100vh - 120px);
            overflow-y: auto;
            scrollbar-width: none;
            /* Firefox */
        }

        .kanban-col::-webkit-scrollbar {
            display: none;
            /* Chrome/Safari */
        }
    </style>
</head>

<body>
    <nav class="navbar-2026 d-flex justify-content-between align-items-center sticky-top">
        <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255,122,0,0.1); border: 1px solid var(--primary-orange);">
                <span style="color: var(--primary-orange); font-weight: 700;">B</span>
            </div>
            <h1 class="h5 mb-0 fw-semibold">Cocina <span class="text-muted fw-normal fs-6 ms-2">| BuffeFast</span></h1>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="small fw-medium">{{ auth()->user()->nombre ?? 'Staff' }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-light rounded-pill border fw-medium px-3"
                    style="color: var(--text-main);">
                    Salir
                </button>
            </form>
        </div>
    </nav>

    <main class="container-fluid p-4">
        <div class="row justify-content-center align-items-center mb-4">
            @if ($message = Session::get('success'))
                <div class="col-12 col-md-6">
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-success-subtle"
                        role="alert">
                        <p class="mb-0 fw-medium">{{ $message }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>