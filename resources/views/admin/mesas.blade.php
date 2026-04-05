<x-layouts.admin>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Buenos dias {{ Auth::user()->nombre }}</h1>
            <p class="text-muted">Vista general del salón y estado de las cuentas.</p>
        </div>

        <div>
            <span class="badge rounded-pill bg-white text-success border px-3 py-2 me-2">
                <button type="button" class="btn btn-link text-decoration-none text-success p-0 m-0"
                    data-bs-toggle="modal" data-bs-target="#modalMesasLibres">
                    <i class="bi bi-circle-fill me-1 small"></i> Libres
                </button>
            </span>
            <span class="badge rounded-pill bg-white text-warning border px-3 py-2 me-2">
                <i class="bi bi-circle-fill me-1 small"></i> Ocupadas
            </span>
            <span class="badge rounded-pill bg-white text-danger border px-3 py-2">
                <i class="bi bi-circle-fill me-1 small"></i> Cuenta
            </span>
        </div>
    </div>
    <div class="d-flex gap-2 mb-4">
        <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal"
            data-bs-target="#modalGestionMesas">
            <i class="bi bi-gear-fill me-1"></i> Gestionar Mesas
        </button>

        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalAgregarMesa">
            <i class="bi bi-plus-lg me-1"></i> Agregar Mesa
        </button>
    </div>

    <div class="row g-4">

        <div class="row g-4">
            @foreach($mesas as $mesa)
                @php
                    // Determinar el estado actual de la mesa
                    $sesionActiva = $mesa->sesiones->first();
                    $estado = 'libre';
                    $color = '#10B981'; // Verde

                    if ($sesionActiva) {
                        if ($sesionActiva->estado === 'solicitando_cuenta') {
                            $estado = 'cuenta';
                            $color = '#EF4444'; // Rojo
                        } else {
                            $estado = 'ocupada';
                            $color = '#FF7A00'; // Naranja
                        }
                    }
                @endphp

                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="mesa-card h-100 d-flex flex-column" style="border-top: 4px solid {{ $color }};">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="h2 fw-bold mb-0">#{{ $mesa->numero }}</h3>
                            <span class="badge rounded-pill text-white px-2 py-1" style="background-color: {{ $color }};">
                                @if($estado == 'libre') Disponible @elseif($estado == 'ocupada') Ocupada @else Pide Cuenta
                                @endif
                            </span>
                        </div>

                        @if($estado == 'libre')
                            <div class="text-center my-4 opacity-50">
                                <i class="bi bi-cup-hot fs-1 text-muted"></i>
                                <p class="small text-muted mt-2">Mesa vacía y limpia</p>
                            </div>

                            <div class="mt-auto">
                                <form action="{{ route('admin.mesa.activar', $mesa->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn w-100 fw-bold rounded-3 py-2"
                                        style="background-color: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                        Activar Mesa
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="p-2 rounded-3 bg-light border text-center mb-2">
                                    <span class="small text-muted d-block mb-1">CÓDIGO DE ACCESO</span>
                                    <span class="fs-4 fw-bold text-dark"
                                        style="letter-spacing: 0.2rem;">{{ $sesionActiva->codigo }}</span>
                                </div>
                                <p class="small text-muted mb-0"><i class="bi bi-clock"></i> Abierta
                                    {{ $sesionActiva->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="mt-auto">
                                @if($estado == 'cuenta')
                                    <a href="#" class="btn w-100 fw-bold rounded-3 py-2 text-white"
                                        style="background-color: {{ $color }}; text-decoration: none;">
                                        <i class="bi bi-receipt"></i> Cobrar y Cerrar
                                    </a>
                                @else
                                    <a href="{{ route('admin.mesa.show', $mesa->id) }}"
                                        class="btn w-100 fw-bold rounded-3 py-2 text-white"
                                        style="background-color: {{ $color }}; border: none; text-decoration: none;">
                                        <i class="bi bi-eye"></i> Ver Detalles / TPV
                                    </a>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
        @include('admin.modals.gestion-mesas')
        @include('admin.modals.agregar-mesa')
        @include('admin.modals.listaMesasLibres')
</x-layouts.admin>