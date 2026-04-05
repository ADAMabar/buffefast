<x-layouts.cliente-app>

    <x-header-carta :sesion="$sesion" :rondaActual="$rondaActual" />

    <div class="p-3 mb-5 pb-5">
        @if(empty($carrito))
            <div class="text-center py-5 mt-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="bi bi-basket text-muted fs-1"></i>
                </div>
                <h4 class="fw-bold">Tu pedido está vacío</h4>
                <p class="text-muted small mb-4">Aún no has añadido ningún plato de la carta.</p>
                <a href="{{ route('cliente.carta') }}"
                    class="btn bg-orange text-white rounded-pill px-4 py-2 fw-bold shadow-sm">
                    Ver la carta
                </a>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($carrito as $id => $item)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-2 d-flex align-items-center">
                            <img src="{{ $item['imagen'] ?? 'https://placehold.co/100x100/eeeeee/A3A8B8?text=🍣' }}"
                                class="rounded-3 object-fit-cover" style="width: 70px; height: 70px;"
                                alt="{{ $item['nombre'] }}">

                            <div class="ms-3 flex-grow-1">
                                <h3 class="h6 fw-bold mb-1">{{ $item['nombre'] }}</h3>
                                <span class="badge bg-light text-dark border">
                                    Cantidad: {{ $item['cantidad'] }}
                                </span>
                            </div>

                            <div class="pe-2">
                                <form action="{{ route('cliente.carrito.remove', $id) }}" method="POST">
                                    @csrf <button type="submit" class="btn btn-sm text-danger border-0">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="contenedor-confirmar" class="position-fixed w-100 p-3" style="bottom: 70px; left: 0; z-index: 1020;">
                @if($segundosRestantes > 0)
                    <button id="btn-espera" disabled
                        class="btn btn-secondary w-100 rounded-pill py-3 fw-bold shadow-lg d-flex justify-content-between align-items-center px-4">
                        <span>Espera para pedir: <span id="contador-reloj">--:--</span></span>
                        <i class="bi bi-clock-history fs-5"></i>
                    </button>
                @else
                    <form action="{{ route('cliente.carrito.confirmar') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="btn bg-orange text-white w-100 rounded-pill py-3 fw-bold shadow-lg d-flex justify-content-between align-items-center px-4">
                            <span>Confirmar a Cocina</span>
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </button>
                    </form>
                @endif
            </div>

            <script>
                let segundos = Math.round({{ $segundosRestantes }});

                if (segundos > 0) {
                    const display = document.querySelector('#contador-reloj');

                    const actualizarReloj = () => {
                        let minutos = Math.floor(segundos / 60);
                        let segs = Math.floor(segundos % 60);

                        display.textContent = `${minutos.toString().padStart(2, '0')}:${segs.toString().padStart(2, '0')}`;

                        if (segundos <= 0) {
                            clearInterval(intervalo);
                            location.reload();
                        }
                        segundos--;
                    };
                    actualizarReloj();
                    const intervalo = setInterval(actualizarReloj, 1000);
                }
            </script>
        @endif
    </div>

    <x-nav-bottom active="carrito" />

</x-layouts.cliente-app>