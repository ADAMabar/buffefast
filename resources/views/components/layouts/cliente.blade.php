<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BuffeFast - Tu Mesa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #F8F9FA;
            --primary-orange: #FF7A00;
            --primary-orange-hover: #E06B00;
            --text-main: #1F2937;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            /* Centrar contenido como si fuera una app móvil */
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            /* Ancho máximo de un móvil grande */
            background-color: #FFFFFF;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <main class="mobile-container">
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