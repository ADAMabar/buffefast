<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menú - BuffeFast</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-app: #F7F7F9;
            --primary-orange: #FF6B00;
            --orange-soft: rgba(255, 107, 0, 0.1);
            --card-bg: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #8F95A3;
        }

        body {
            background-color: var(--bg-app);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 80px;
            /* Espacio para la Bottom Nav */
        }

        /* Ocultar scrollbar horizontal pero permitir scroll */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Estilos Tarjetas Platos */
        .food-card {
            border-radius: 16px;
            border: none;
            background: var(--card-bg);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease;
        }

        .food-card:active {
            transform: scale(0.98);
        }

        /* Imagen perfecta 1:1 */
        .food-img-wrapper {
            position: relative;
            padding-bottom: 100%;
            /* Ratio 1:1 */
            overflow: hidden;
            border-radius: 16px 16px 0 0;
            background-color: #EEEEEE;
        }

        .food-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Truncar texto a 2 líneas para descripciones */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Botón Flotante "+" */
        .btn-add-cart {
            background-color: var(--primary-orange);
            color: #FFF;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(255, 107, 0, 0.3);
            position: absolute;
            bottom: -10px;
            /* Sobresale un poco hacia abajo */
            right: 12px;
            font-size: 1.2rem;
            transition: background-color 0.2s, transform 0.1s;
            z-index: 10;
        }

        .btn-add-cart:active {
            transform: scale(0.9);
            background-color: #E66000;
        }

        /* Botones Categorías */
        .btn-category {
            background: var(--card-bg);
            color: var(--text-main);
            border: 1px solid #E5E7EB;
            border-radius: 24px;
            white-space: nowrap;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .btn-category.active {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
        }

        /* Bottom Nav */
        .bottom-nav {
            background: var(--card-bg);
            border-top: 1px solid #E5E7EB;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.04);
            padding-bottom: env(safe-area-inset-bottom, 15px);
            /* Soporte para iPhone notch */
        }

        .nav-item-b {
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .nav-item-b.active {
            color: var(--primary-orange);
        }

        .badge-nav {
            background-color: var(--primary-orange);
            border: 2px solid var(--card-bg);
        }
    </style>
</head>

<body>

    <main class="container-fluid p-0">
        @if ($message = Session::get('success'))
            <div class="px-3 pt-3">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-success-subtle mb-0"
                    role="alert">
                    <p class="mb-0 fw-medium fs-6">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>