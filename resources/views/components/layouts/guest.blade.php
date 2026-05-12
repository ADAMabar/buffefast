<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuffeFast - Staff Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-light: #F8F9FA;
            --primary-orange: #FF7A00;
            --primary-orange-hover: #E06B00;
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
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 50% 0%, rgba(255, 122, 0, 0.05) 0%, transparent 50%);
        }

        .clean-card {
            background: var(--card-bg);
            border: 1px solid var(--primary-orange);
            padding: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(255, 122, 0, 0.15);

        }

        
        .form-control-2026 {
            background: #FFFFFF;
            border: 1px solid var(--border-light);
            color: var(--text-main);
            border-radius: 16px;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }

        .form-control-2026:focus {
            background: #FFFFFF;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 122, 0, 0.15);
            color: var(--text-main);
        }

        .form-control-2026::placeholder {
            color: var(--text-muted);
        }

        /* Botón Naranja Moderno */
        .btn-orange {
            background-color: var(--primary-orange);
            color: #FFFFFF;
            border: none;
            border-radius: 100px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-orange:hover {
            background-color: var(--primary-orange-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 122, 0, 0.3);
            color: #FFFFFF;
        }
    </style>
</head>

<body>
    <main class="container d-flex justify-content-center">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // La página viene de bfcache, el token CSRF está expirado
        // Hacemos fetch para obtener token nuevo SIN recargar la página
        fetch('/refresh-csrf')
            .then(r => r.json())
            .then(data => {
                // Actualizar el input _token del formulario
                const tokenInput = document.querySelector('input[name="_token"]');
                if (tokenInput) {
                    tokenInput.value = data.token;
                }
            })
            .catch(err => console.error('Error refrescando CSRF:', err));
    }
});
</script>

</body>

</html>