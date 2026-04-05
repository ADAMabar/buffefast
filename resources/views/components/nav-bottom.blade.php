@props(['active' => 'carta'])

<div class="bottom-nav bg-white border-top fixed-bottom d-flex justify-content-around py-2 pb-3 shadow-lg"
    style="z-index: 1030;">
    <a href="{{ route('cliente.carta') }}"
        class="nav-item text-decoration-none {{ $active === 'carta' ? 'text-orange' : 'text-muted' }} d-flex flex-column align-items-center">
        <i class="bi {{ $active === 'carta' ? 'bi-grid-fill' : 'bi-grid' }} fs-4"></i>
        <span class="small {{ $active === 'carta' ? 'fw-bold' : 'fw-medium' }}" style="font-size: 0.75rem;">Menú</span>
    </a>

    <a href="{{ route('cliente.carrito') }}"
        class="nav-item text-decoration-none {{ $active === 'carrito' ? 'text-orange' : 'text-muted' }} d-flex flex-column align-items-center position-relative">
        <i class="bi {{ $active === 'carrito' ? 'bi-basket-fill' : 'bi-basket' }} fs-4"></i>
        <span class="small {{ $active === 'carrito' ? 'fw-bold' : 'fw-medium' }}"
            style="font-size: 0.75rem;">Pedido</span>
        @if(session('carrito_count', 0) > 0)
            <span class="position-absolute translate-middle p-1 rounded-circle bg-orange border border-2 border-white"
                style="top: 8px; right: 20%;">
                <span class="visually-hidden">Nuevos items</span>
            </span>
        @endif
    </a>

    <a href="{{ route('cliente.cuenta') }}"
        class="nav-item text-decoration-none {{ $active === 'cuenta' ? 'text-orange' : 'text-muted' }} d-flex flex-column align-items-center">
        <i class="bi {{ $active === 'cuenta' ? 'bi-receipt-cutoff' : 'bi-receipt' }} fs-4"></i>
        <span class="small {{ $active === 'cuenta' ? 'fw-bold' : 'fw-medium' }}"
            style="font-size: 0.75rem;">Cuenta</span>
    </a>
</div>