<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cocina - BuffeFast</title>

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
            min-height: 100vh;
        }

        .main-content {
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <main class="container-fluid p-0 main-content">
        @if ($message = Session::get('success'))
            <div class="px-4 pt-4">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0 mb-0" 
                     role="alert" 
                     style="background-color: #ECFDF5; color: #065F46;">
                    <p class="mb-0 fw-medium">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
           @if ($message = Session::get('error'))
            <div class="px-4 pt-4">
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0 mb-0" 
                     role="alert" 
                     style="background-color: #ECFDF5; color: #ff2e2e;">
                    <p class="mb-0 fw-medium">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>