<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BuffeFast - Menú</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-light: #F8F9FA;
            --primary-orange: #FF7A00;
            --primary-orange-hover: #E06B00;
            --text-main: #1F2937;
            --text-muted: #6B7280;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            display: flex;
            justify-content: center;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            background-color: #FFFFFF;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
            padding-bottom: 80px;
            /* Espacio para el bottom nav */
            padding-top: 70px;
            /* Espacio para el top nav */
        }

        /* Top Nav (Contador de Ronda) */
        .top-nav {
            position: fixed;
            top: 0;
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1030;
            border-bottom: 1px solid #E5E7EB;
            padding: 1rem;
        }

        /* Bottom Nav 2026 */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 480px;
            background: #FFFFFF;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-around;
            padding: 0.75rem 0;
            z-index: 1030;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.02);
        }

        .nav-item {
            text-decoration: none;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-item i {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        .nav-item.active {
            color: var(--primary-orange);
        }
    </style>
</head>

<body>
    <div class="mobile-container">
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>