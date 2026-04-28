<!DOCTYPE html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BuffeFast - App</title>

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
        margin: 0;
        min-height: 100vh;
    }

    .mobile-container {
        width: 100%;
        background-color: #FFFFFF;
        min-height: 100vh;
        position: relative;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
        padding-bottom: 80px;
        padding-top: 70px;
        max-width: 100%;
    }

    /* Top Nav */
    .top-nav {
        position: fixed;
        top: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 1030;
        border-bottom: 1px solid #E5E7EB;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Bottom Nav */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #FFFFFF;
        border-top: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-around;
        padding: 0.75rem 0;
        z-index: 1030;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.02);
        left: 50%;
        transform: translateX(-50%);
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

    .text-orange {
        color: var(--primary-orange) !important;
    }

    .bg-orange {
        background-color: var(--primary-orange) !important;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .card-sushi {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-sushi:active {
        transform: scale(0.98);
    }




@media (pointer: fine) {
    .hide-scrollbar {
        -ms-overflow-style: auto;
        scrollbar-width: thin; /* Firefox: barra delgada */
        scrollbar-color: var(--primary-orange) #E5E7EB; /* Color Firefox */
    }
    
    .hide-scrollbar::-webkit-scrollbar {
        display: block;
        height: 6px; /* Altura de la barra horizontal */
    }
    
    .hide-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .hide-scrollbar::-webkit-scrollbar-thumb {
        background-color: var(--primary-orange);
        border-radius: 10px;
    }
}

    /* ===== TABLET (576px - 991px) ===== */
    @media (min-width: 576px) {
        .mobile-container {
            padding-bottom: 90px;
            padding-top: 80px;
        }

        .nav-item {
            font-size: 0.85rem;
        }

        .nav-item i {
            font-size: 1.4rem;
        }
    }

    /* ===== ESCRITORIO (> 992px) ===== */
    @media (min-width: 992px) {
        .mobile-container {
            /* Ocupa TODO el ancho, sin límite */
            max-width: 100%;
            padding-bottom: 100px;
            padding-top: 90px;
        }

        /* Las categorías se centran y hacen wrap */
        .categorias-wrapper {
            justify-content: center;
            flex-wrap: wrap;
            overflow: visible !important;
            gap: 0.75rem !important;
        }

        .categorias-wrapper .btn {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            font-size: 1rem;
        }

        .nav-item {
            font-size: 0.95rem;
            padding: 0.25rem 2rem;
        }

        .nav-item i {
            font-size: 1.6rem;
            margin-bottom: 4px;
        }

        /* Tarjetas más grandes en escritorio */
        .card-sushi {
            border-radius: 1rem !important;
        }

        .card-sushi .card-body {
            padding: 1rem !important;
        }

        .card-sushi h2 {
            font-size: 1.1rem !important;
        }

        .card-sushi p {
            font-size: 0.9rem !important;
        }

        .card-sushi .badge {
            font-size: 0.95rem;
            padding: 0.5em 0.75em;
        }

        /* Botón + más grande y accesible */
        .add-to-cart-btn {
            width: 44px !important;
            height: 44px !important;
            bottom: 12px !important;
            right: 12px !important;
        }

        .add-to-cart-btn i {
            font-size: 1.3rem;
        }
    }

    /* ===== PANTALLAS MUY GRANDES (> 1400px) ===== */
    @media (min-width: 1400px) {
        .mobile-container {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }
</style>
</head>

<body>
    <div class="mobile-container">

       
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1055; margin-top: 60px;">
            
            @if ($message = Session::get('success'))
                <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                    <div class="d-flex">
                        <div class="toast-body fw-medium"><i class="bi bi-check-circle me-2"></i>{{ $message }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body fw-medium">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            @endif
            <div id="toastPlantilla" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000" style="display: none;">
                <div class="d-flex">
                    <div class="toast-body fw-medium" id="toastMensaje"><i class="bi bi-check-circle me-2"></i>Añadido al carrito</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            
        </div>
        {{ $slot }}

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            
            
            const toasts = document.querySelectorAll('.toast.show');
            toasts.forEach(toastNode => {
                const toast = new bootstrap.Toast(toastNode);
                setTimeout(() => toast.hide(), 3000);
            });

            const formulariosCarrito = document.querySelectorAll('form[action*="carrito/add"]');
            
            formulariosCarrito.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); 
                    // Efecto visual: animar el botón un poquito
                    const btn = this.querySelector('button');
                    const iconoOriginal = btn.innerHTML;
                    btn.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';

                    // Enviar los datos por detrás
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(() => {
                        const formData = new FormData(this);
                        const platoId = formData.get('plato_id');

                            // Buscamos el badge por el ID que acabamos de crear arriba
                        const badge = document.getElementById(`badge-cantidad-${platoId}`);
                            
                        if (badge) {
                            const numSpan = badge.querySelector('.cantidad-num');
                            let cantidadActual = parseInt(numSpan.innerText) || 0;
                                
                            numSpan.innerText = cantidadActual + 1;  
                            badge.style.display = 'inline-block';
                            }
            
                        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
                        setTimeout(() => btn.innerHTML = iconoOriginal, 1500);

                        // Crear y mostrar una notificación flotante nueva
                        const toastEl = document.getElementById('toastPlantilla').cloneNode(true);
                        toastEl.style.display = 'block';
                        document.querySelector('.toast-container').appendChild(toastEl);
                        
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();

                        // Destruir el toast del HTML cuando termine para no acumular basura
                        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());

                        const badgeCarrito = document.querySelector('a[href*="carrito"] .bg-orange');
                        if (badgeCarrito) {
                            //si existe el badgeCarrito pues no hago nada
                        } else {
                            // Crear el badge si no existe
                            const enlaceCarrito = document.querySelector('a[href*="carrito"]');
                            if (enlaceCarrito) {
                                const nuevoBadge = document.createElement('span');
                                nuevoBadge.className = 'position-absolute translate-middle p-1 rounded-circle bg-orange border border-2 border-white';
                                nuevoBadge.style.cssText = 'top: 8px; right: 20%;';
                                nuevoBadge.innerHTML = '<span class="visually-hidden">Nuevos items</span>';
                                enlaceCarrito.appendChild(nuevoBadge);
                            }
                        }
                    })
                    .catch(() => {
                        btn.innerHTML = iconoOriginal;
                        alert('Error al añadir al carrito. Revisa tu conexión.');
                    });
                });
            });
            

        });
    </script>
</body>

</html>