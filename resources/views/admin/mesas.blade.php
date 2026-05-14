<x-layouts.admin>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Buenos dias {{ Auth::user()->nombre }}</h1>
            <p class="text-muted">Vista general del salón y estado de las cuentas.</p>
        </div>

      <div class="d-flex align-items-center gap-3">
    
   
   <button type="button" 
    class="btn rounded-pill fw-bold px-4 py-2 shadow-sm d-flex align-items-center"
    style="background-color: #FFF7ED; color: #C2410C; border: 1px solid #C2410C;"
    data-bs-toggle="modal" 
    data-bs-target="#modalMesasLibres">
    <i class="bi bi-circle-fill me-2" style="font-size: 0.7rem;"></i> Mesas Libres
</button>

    <button type="button" 
        class="btn btn-success rounded-pill px-3 py-2 fw-medium d-flex align-items-center position-relative shadow-sm" 
        data-bs-toggle="modal"
        data-bs-target="#modalPidiendoCuenta">
        <i class="bi bi-cash-stack me-2"></i>
        Pidiendo Cuenta

        @if($mesasPidiendoCuenta->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                {{ $mesasPidiendoCuenta->count() }}
                <span class="visually-hidden">mesas esperando</span>
            </span>
        @endif
    </button>

</div>
    </div>
   <div class="d-flex align-items-center gap-3 mb-4">
    
    <button type="button" 
        class="btn btn-white border rounded-pill px-4 py-2 fw-semibold d-flex align-items-center shadow-sm" 
        data-bs-toggle="modal"
        data-bs-target="#modalGestionMesas"
        style="background: var(--primary-orange); color: #ffffff; border-color: #ffffff;">
        <i class="bi bi-gear-fill me-2 opacity-75"></i>
        Gestionar Mesas
    </button>

  
    <button type="button" 
        class="btn rounded-pill px-4 py-2 fw-semibold d-flex align-items-center text-white shadow-sm" 
        data-bs-toggle="modal" 
        data-bs-target="#modalAgregarMesa"
        style="background-color: var(--primary-orange); border: none;">
        <i class="bi bi-plus-lg me-2"></i>
        Agregar Mesa
    </button>

</div>

    <div class="row g-4">

        <div class="row g-4">
            @foreach($mesas as $mesa)
                @php
                    // Determinar el estado actual de la mesa
                    $sesionActiva = $mesa->sesiones()
                        ->whereIn('estado', ['activa', 'solicitando_cuenta'])
                        ->latest()
                        ->first();
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
                                    {{ $sesionActiva->created_at->locale('es')->diffForHumans() }}
                                </p>
                            </div>

                            <div class="mt-auto">
                                @if($estado == 'cuenta')
                                    <a href="{{ route('admin.mesa.show', $mesa->id) }}" class="btn w-100 fw-bold rounded-3 py-2 text-white"
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
        @include('admin.modals.listaMesasPidenCuenta')
</x-layouts.admin>