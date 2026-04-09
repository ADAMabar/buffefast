<x-layouts.admin>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1"><i class="bi bi-gear-fill me-2"></i>Configuración</h1>
            <p class="text-muted">Ajustes del sistema, reglas del buffet y equipo.</p>
        </div>

        <div>
            <button
                class="btn {{ ($ajustes['modo_panico'] ?? 'false') == 'true' ? 'btn-danger' : 'btn-outline-danger' }} fw-bold rounded-pill px-4"
                id="btnPanico" onclick="togglePanico()">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span id="textoPanico">
                    {{ ($ajustes['modo_panico'] ?? 'false') == 'true' ? 'MODO PÁNICO ACTIVO' : 'Activar Modo Pánico' }}
                </span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-shield-x me-2"></i> <strong>¡Ups! Revisa los datos:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <ul class="nav nav-tabs mb-4 fw-bold" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-dark" id="ajustes-tab" data-bs-toggle="tab" data-bs-target="#ajustes"
                type="button" role="tab">
                <i class="bi bi-sliders me-1"></i> Reglas y Sistema
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark" id="equipo-tab" data-bs-toggle="tab" data-bs-target="#equipo"
                type="button" role="tab">
                <i class="bi bi-people-fill me-1"></i> Equipo y Accesos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="configTabsContent">

        <div class="tab-pane fade show active" id="ajustes" role="tabpanel" tabindex="0">
            <form action="{{ route('admin.configuracion.ajustes') }}" method="POST">
                @csrf
                <div class="row g-4">

                    <div class="col-12 col-xl-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <h5 class="fw-bold"><i class="bi bi-stopwatch text-primary me-2"></i>Operativa del
                                    Buffet</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Tiempo entre rondas
                                        (Minutos)</label>
                                    <input type="number" class="form-control" name="tiempo_ronda_minutos"
                                        value="{{ $ajustes['tiempo_ronda_minutos'] ?? 10 }}" min="0" max="120">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Límite de platos por
                                        persona/ronda</label>
                                    <input type="number" class="form-control" name="limite_platos_ronda"
                                        value="{{ $ajustes['limite_platos_ronda'] ?? 4 }}" min="1" max="50">
                                </div>

                                <hr class="text-muted opacity-25 my-4">

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input fs-5" type="checkbox" role="switch"
                                        name="penalizacion_activa" value="1" id="checkPenalizacion" {{ ($ajustes['penalizacion_activa'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold mt-1 ms-2"
                                        for="checkPenalizacion">Penalización por desperdicio</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Precio por plato sobrante
                                        (€)</label>
                                    <input type="number" step="0.10" class="form-control" name="precio_penalizacion"
                                        value="{{ $ajustes['precio_penalizacion'] ?? 2.00 }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <h5 class="fw-bold"><i class="bi bi-receipt text-success me-2"></i>Facturación y Sistema
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Nombre del Restaurante
                                        (Tickets)</label>
                                    <input type="text" class="form-control" name="nombre_restaurante"
                                        value="{{ $ajustes['nombre_restaurante'] ?? 'BuffetFast' }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">Impuestos aplicables (%)</label>
                                    <input type="number" class="form-control" name="porcentaje_impuestos"
                                        value="{{ $ajustes['porcentaje_impuestos'] ?? 10 }}" min="0" max="100">
                                </div>

                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            name="aceptacion_automatica" value="1" {{ ($ajustes['aceptacion_automatica'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold small">Auto-aceptar pedidos móviles a
                                            cocina</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            name="sonido_cocina" value="1" {{ ($ajustes['sonido_cocina'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold small">Sonido de alerta en tablet de
                                            cocina</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary fw-bold px-5 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-floppy me-2"></i> Guardar Ajustes
                    </button>
                </div>
            </form>
        </div>


        <div class="tab-pane fade" id="equipo" role="tabpanel" tabindex="0">

            <div class="card border-0 shadow-sm rounded-4">
                <div
                    class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Gestión de Personal</h5>
                    <button class="btn btn-dark btn-sm fw-bold rounded-pill px-3" data-bs-toggle="modal"
                        data-bs-target="#modalNuevoEmpleado">
                        <i class="bi bi-person-plus-fill me-1"></i> Nuevo Empleado
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nombre</th>
                                    <th>Rol</th>
                                    <th>Acceso</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empleados as $empleado)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $empleado->nombre }}</td>
                                        <td>
                                            @if($empleado->rol == 'admin') <span class="badge bg-primary">Admin</span>
                                            @endif
                                            @if($empleado->rol == 'camarero') <span
                                            class="badge bg-info text-dark">Camarero</span> @endif
                                            @if($empleado->rol == 'cocinero') <span
                                            class="badge bg-warning text-dark">Cocinero</span> @endif
                                        </td>
                                        <td>
                                            @if($empleado->rol == 'camarero')
                                                <span class="text-muted small"><i class="bi bi-123 me-1"></i> PIN: ****</span>
                                            @else
                                                <span class="text-muted small"><i class="bi bi-envelope me-1"></i>
                                                    {{ $empleado->email }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if(auth()->id() !== $empleado->id)
                                                <button class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            @else
                                                <span class="badge bg-light text-muted border">Tú</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="modalNuevoEmpleado" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Registrar
                        Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.configuracion.empleado.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Puesto de Trabajo</label>
                            <select class="form-select" name="rol" id="selectRol" onchange="cambiarCamposPorRol()"
                                required>
                                <option value="admin">Administrador (Acceso Total)</option>
                                <option value="camarero" selected>Camarero / Jefe de Sala</option>
                                <option value="cocinero">Cocinero (Solo Kanban)</option>
                            </select>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">

                            <div id="bloquePin">
                                <label class="form-label fw-bold small text-muted"><i class="bi bi-123 me-1"></i> PIN de
                                    Acceso (4 dígitos)</label>
                                <input type="number" class="form-control text-center fs-4 letter-spacing-2" name="pin"
                                    id="inputPin" placeholder="Ej: 4012" min="1000" max="9999" required>
                                <div class="form-text small">Los camareros usan este PIN para acceder rápido desde la
                                    tablet.</div>
                            </div>

                            <div id="bloqueWeb" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Correo Electrónico</label>
                                    <input type="email" class="form-control" name="email" id="inputEmail">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Contraseña</label>
                                    <input type="password" class="form-control" name="password" id="inputPass">
                                </div>
                                <div>
                                    <label class="form-label fw-bold small text-muted">Repetir Contraseña</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="inputPassConfirm">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-top-0 bg-white rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary fw-bold px-4"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. Lógica para cambiar los campos del Modal según el Rol
        function cambiarCamposPorRol() {
            let rol = document.getElementById('selectRol').value;
            let bloquePin = document.getElementById('bloquePin');
            let bloqueWeb = document.getElementById('bloqueWeb');

            let inputPin = document.getElementById('inputPin');
            let inputEmail = document.getElementById('inputEmail');
            let inputPass = document.getElementById('inputPass');
            let inputPassConfirm = document.getElementById('inputPassConfirm');

            if (rol === 'camarero') {
                bloquePin.style.display = 'block';
                bloqueWeb.style.display = 'none';

                inputPin.required = true;
                inputEmail.required = false;
                inputPass.required = false;
                inputPassConfirm.required = false;
            } else {
                bloquePin.style.display = 'none';
                bloqueWeb.style.display = 'block';

                inputPin.required = false;
                inputPin.value = ''; // Limpiamos por si acaso
                inputEmail.required = true;
                inputPass.required = true;
                inputPassConfirm.required = true;
            }
        }

        // Ejecutar al abrir la página por primera vez
        document.addEventListener('DOMContentLoaded', function () {
            cambiarCamposPorRol();
        });

        // 2. Lógica AJAX del Botón del Pánico
        function togglePanico() {
            let btn = document.getElementById('btnPanico');
            let texto = document.getElementById('textoPanico');

            if (!confirm('¿Estás seguro? Esto pausará o reanudará la posibilidad de que los clientes hagan pedidos desde sus mesas.')) return;

            fetch('{{ route("admin.configuracion.panico") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.estado === 'true') {
                            btn.classList.remove('btn-outline-danger');
                            btn.classList.add('btn-danger');
                            texto.innerText = 'MODO PÁNICO ACTIVO';
                        } else {
                            btn.classList.remove('btn-danger');
                            btn.classList.add('btn-outline-danger');
                            texto.innerText = 'Activar Modo Pánico';
                        }
                        alert(data.message);
                    }
                });
        }
    </script>

</x-layouts.admin>