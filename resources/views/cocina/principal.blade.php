<x-layouts.app>
    {{-- Estilos específicos para la disposición del tablero Kanban --}}
    <style>
        .kanban-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            align-items: start;
        }
        
        /* Animación suave para el indicador de "En vivo" */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10B981; /* Verde éxito */
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>

    <div class="d-flex flex-column" style="min-height: 100vh;">
        
        {{-- CABECERA DE COCINA --}}
        <header class="bg-white border-bottom shadow-sm px-4 py-3 d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-inline-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,122,0,0.1); border: 1px solid var(--primary-orange);">
                    <i class="bi bi-fire" style="color: var(--primary-orange); font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h1 class="h5 mb-1 fw-bold text-dark">Panel de Cocina</h1>
                    <div class="text-muted d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-clock"></i>
                        <span id="reloj">Cargando reloj...</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="d-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background-color: #ECFDF5; border: 1px solid #D1FAE5;">
                    <div class="status-dot"></div>
                    <span class="fw-medium text-success" style="font-size: 0.85rem;">Sincronizado</span>
                </div>

                {{-- Botón de salir --}}
                <a href="{{ route('logout') }}" 
                onclick="logoutSeguro(event);" 
                class="btn btn-outline-danger fw-medium rounded-3 px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </header>

        {{-- CONTENEDOR DEL TABLERO (El fragmento se inyectará aquí) --}}
        <div class="container-fluid px-4 flex-grow-1 pb-4">
            <div class="kanban-grid" id="contenedor-tablero">
                {{-- Estado de carga inicial --}}
                <div style="grid-column: 1 / -1;" class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                    <div class="spinner-border mb-3" style="color: var(--primary-orange);" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mb-0 fw-medium fs-5">Cargando comandas...</p>
                </div>
            </div>
        </div>

    </div>

    
    <script>
        // Reloj
        function actualizarReloj() {
            const el = document.getElementById('reloj');
            if (!el) return;
            const ahora = new Date();
            el.textContent = ahora.toLocaleTimeString() ;
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj(); 

    let intervaloTablero = setInterval(refrescarTablero, 1000);

    function refrescarTablero() {
        // Si no hay intervalo (porque estamos saliendo), no hacer nada
        if (!intervaloTablero) return;

        fetch('{{ route("cocina.verTablero") }}')
            .then(res => {
                // Si el servidor responde con 401 o 419 (sesión cerrada), detenemos todo
                if (res.status === 401 || res.status === 419) {
                    detenerTodo();
                    return;
                }
                return res.text();
            })
            .then(html => {
                if (html) document.getElementById('contenedor-tablero').innerHTML = html;
            })
            .catch(err => console.error('Error al actualizar el tablero:', err));
    }

    // 2. Función para limpiar todo antes de salir
    function logoutSeguro(event) {
        event.preventDefault();
        
        // Detener los intervalos de JS inmediatamente
        detenerTodo();
        
        // Detener cualquier petición fetch que esté "en el aire"
        window.stop(); 
        
        // Enviar el formulario
        document.getElementById('logout-form').submit();
    }

    function detenerTodo() {
        clearInterval(intervaloTablero);
        intervaloTablero = null;
    }

        function actualizarEstado(id, nuevoEstado, boton) {
            
            const contenidoOriginal = boton.innerHTML;
            boton.disabled = true;
            boton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            fetch(`/cocina/pedido/${id}/actualizar-estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ estado: nuevoEstado })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    refrescarTablero();
                } else {
                    boton.disabled = false;
                    boton.innerHTML = contenidoOriginal;
                    alert('Error al actualizar el estado');
                }
            })
            .catch(err => {
                console.error(err);
                boton.disabled = false;
                boton.innerHTML = contenidoOriginal;
            });
        }
    </script>
</x-layouts.app>