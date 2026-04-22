@props(['sesion', 'rondaActual'])

<div class="top-nav d-flex justify-content-between align-items-center p-3 pb-0">
    <div>
        <h1 class="h5 mb-0 fw-bold">Mesa {{ $sesion->mesa->numero ?? '?' }}</h1>
        <span class="small text-muted">{{ configuracion('nombre_restaurante') }}</span>
    </div>
    <div class="text-end">
        <span class="badge rounded-pill fw-bold"
            style="background-color: var(--orange-soft, #FFF3E0); color: var(--primary-orange); padding: 0.2rem 0.8rem;">
            <span style="position: relative; top: -2px;">Ronda {{ $rondaActual }}</span>
        </span>
      
    </div>
    <a href="{{ route('cliente.logout') }}" class="btn btn-outline-danger btn-sm rounded-pill px-2"
        onclick="return confirm('¿Estás seguro de que quieres cerrar sesión?');">
        Cerrar Sesión
    </a>
</div>