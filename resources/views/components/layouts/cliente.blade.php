<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BuffeFast - Tu Mesa</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-orange: #FF7A00;
            --text-main: #1F2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            /* Flexbox para centrar la tarjeta */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1.5rem; 
            position: relative; 
        }

        body::before {
            content: '';
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100vw; 
            height: 100vh;
            background-image: url('{{ asset('storage/logos/CocoShusi.jpeg') }}');
            background-size: cover; 
            background-position: center center; 
            background-repeat: no-repeat;
            z-index: -2;
        }

  
        body::after {
            content: '';
            position: fixed;
            top: 0; 
            left: 0; 
            width: 100vw; 
            height: 100vh;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(0.5px); 
            -webkit-backdrop-filter: blur(0.5px);
            z-index: -1; 
        }

        
        .app-card {
            width: 100%;
            max-width: 400px;
            background-color: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6); 
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            z-index: 1; 
        }
    </style>
</head>
<body>
    <main class="app-card">
        
      
        @if ($message = Session::get('success'))
            <div class="px-3 pt-3 position-absolute top-0 w-100" style="z-index: 10;">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border-success-subtle mb-0" role="alert">
                    <p class="mb-0 fw-medium" style="font-size: 0.9rem;">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        {{-- Contenido de la vista (tu formulario) se inyecta aquí --}}
        {{ $slot }}

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>