<x-layouts.admin>

    {{-- ── Page Header ── --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1"><i class="bi bi-book-half me-2"></i>Gestión de la Carta</h1>
            <p class="text-muted mb-0">Vista general de categorías y estado de los platos.</p>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge rounded-pill bg-white text-success border px-3 py-2">
                <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> Disponibles
            </span>
            <span class="badge rounded-pill bg-white border px-3 py-2 p-0">
                <button type="button"
                    class="btn btn-link text-decoration-none text-danger p-0 m-0 d-inline-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#modalPlatosOcultos"
                    aria-label="Ver platos ocultos">
                    <i class="bi bi-circle-fill" style="font-size: 0.6rem;"></i> Ocultos
                </button>
            </span>
        </div>
    </div>

    {{-- ── Action buttons ── --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal"
            data-bs-target="#modalCategorias">
            <i class="bi bi-tags me-1"></i> Gestionar Categorías
        </button>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalPlato">
            <i class="bi bi-plus-lg me-1"></i> Agregar Plato
        </button>
    </div>

    {{-- ── Search ── --}}
    <div class="mb-4">
        <div class="input-group shadow-sm rounded-pill overflow-hidden border">
            <span class="input-group-text bg-white border-0 ps-4">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="search"
                id="buscadorPlatos"
                class="form-control border-0 py-2 shadow-none"
                placeholder="Escribe para buscar un plato o categoría..."
                autocomplete="off"
                aria-label="Buscar platos">
        </div>
    </div>

    {{-- ── Empty state ── --}}
    @if($categorias->isEmpty())
        <div class="text-center py-5 bg-light rounded-4 border border-dashed mt-4">
            <i class="bi bi-egg-fried fs-1 text-muted mb-3 d-block"></i>
            <h4 class="fw-bold">Tu carta está vacía</h4>
            <p class="text-muted mb-0">Crea tu primera categoría y empieza a añadir platos.</p>
        </div>

    @else
        @foreach($categorias as $categoria)

            {{-- ── Category block ── --}}
            <section class="mb-4 bg-white p-3 p-md-4 rounded-4 shadow-sm categoria-bloque"
                aria-label="Categoría {{ $categoria->nombre }}">

                {{-- Category header / collapse toggle --}}
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3"
                    role="button"
                    tabindex="0"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseCategoria{{ $categoria->id }}"
                    aria-expanded="true"
                    aria-controls="collapseCategoria{{ $categoria->id }}"
                    style="cursor: pointer;"
                    onkeydown="if(event.key==='Enter'||event.key===' ')this.click()">

                    <h2 class="h5 fw-bold mb-0 text-dark nombre-categoria d-flex align-items-center flex-wrap gap-2">
                        {{ $categoria->nombre }}
                        <span class="badge bg-light text-secondary border rounded-pill"
                            style="font-size: 0.8rem; font-weight: 500;">
                            {{ $categoria->platos->count() }} platos
                        </span>
                    </h2>

                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center flex-shrink-0"
                        style="width: 35px; height: 35px;" aria-hidden="true">
                        <i class="bi bi-chevron-expand text-muted"></i>
                    </div>
                </div>

                {{-- Plate grid --}}
                <div class="collapse show" id="collapseCategoria{{ $categoria->id }}">
                    @if($categoria->platos->isEmpty())
                        <p class="text-muted fst-italic text-center mb-0 pt-4">No hay platos en esta categoría.</p>
                    @else
                        {{--
                            Fluid grid: fills columns as wide as possible,
                            min 260px → grows up to 1fr.
                            On very narrow screens (< ~560px) cards become single-column.
                        --}}
                        <div class="pt-4" style="
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));
                            gap: 1.25rem;
                        ">
                            @foreach($categoria->platos as $plato)

                                <article class="col-plato" id="tarjeta-plato-{{ $plato->id }}">
                                    <div class="card h-100 d-flex flex-column shadow-sm"
                                        style="border: none;
                                               border-radius: 0.875rem;
                                               border-top: 4px solid {{ $plato->activo ? '#10B981' : '#EF4444' }};">

                                        {{-- Clickable area → offcanvas detail --}}
                                        <div class="card-body pb-0 flex-grow-1"
                                            role="button"
                                            tabindex="0"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasPlato{{ $plato->id }}"
                                            aria-label="Ver detalles de {{ $plato->nombre }}"
                                            onkeydown="if(event.key==='Enter')this.click()"
                                            style="cursor: pointer;">

                                            {{-- Name + badge --}}
                                            <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                                                <h3 class="h5 fw-bold mb-0 text-truncate nombre-plato"
                                                    title="{{ $plato->nombre }}">
                                                    {{ $plato->nombre }}
                                                </h3>
                                                <span class="badge rounded-pill text-white text-nowrap flex-shrink-0 px-2 py-1"
                                                    style="font-size: 0.75rem; background-color: {{ $plato->activo ? '#10B981' : '#EF4444' }};">
                                                    {{ $plato->activo ? 'Disponible' : 'Oculto' }}
                                                </span>
                                            </div>

                                            {{-- Image --}}
                                            @if($plato->imagen)
                                                <img src="{{ asset('storage/' . $plato->imagen) }}"
                                                    class="rounded-3 mb-2 w-100"
                                                    style="height: 140px; object-fit: cover;"
                                                    alt="{{ $plato->nombre }}"
                                                    loading="lazy">
                                            @else
                                                <div class="rounded-3 mb-2 bg-light d-flex justify-content-center
                                                            align-items-center border w-100"
                                                    style="height: 140px;" aria-hidden="true">
                                                    <i class="bi bi-image fs-1 text-muted opacity-50"></i>
                                                </div>
                                            @endif

                                            {{-- Description --}}
                                            <p class="small text-muted mb-0"
                                                style="display: -webkit-box;
                                                       -webkit-line-clamp: 2;
                                                       -webkit-box-orient: vertical;
                                                       overflow: hidden;
                                                       line-height: 1.5;">
                                                {{ $plato->descripcion ?? 'Sin descripción.' }}
                                            </p>
                                        </div>

                                        {{-- Card footer: toggle + actions --}}
                                        <div class="card-body pt-3">

                                            {{-- Visibility toggle --}}
                                            <div class="mb-3 p-2 rounded-3 bg-light border text-center">
                                                <form action="{{ route('admin.plato.toggle', $plato->id) }}"
                                                    method="POST"
                                                    class="m-0">
                                                    @csrf
                                                    <div class="form-check form-switch d-inline-block m-0">
                                                        <input class="form-check-input"
                                                            style="cursor: pointer;"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="switch-{{ $plato->id }}"
                                                            onchange="this.form.submit()"
                                                            aria-label="Visibilidad de {{ $plato->nombre }}"
                                                            {{ $plato->activo ? 'checked' : '' }}>
                                                        <label class="form-check-label small fw-bold
                                                                       {{ $plato->activo ? 'text-success' : 'text-danger' }}"
                                                            for="switch-{{ $plato->id }}">
                                                            {{ $plato->activo ? 'Visible' : 'Oculto' }}
                                                        </label>
                                                    </div>
                                                </form>
                                            </div>

                                            {{-- Edit + Delete --}}
                                            <div class="d-flex gap-2">
                                                <button class="btn flex-fill fw-bold rounded-3 py-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarPlato{{ $plato->id }}"
                                                    aria-label="Editar {{ $plato->nombre }}"
                                                    style="background-color: rgba(13,110,253,0.1);
                                                           color: #0d6efd;
                                                           border: 1px solid rgba(13,110,253,0.2);">
                                                    <i class="bi bi-pencil"></i>
                                                    <span class="d-none d-sm-inline ms-1">Editar</span>
                                                </button>

                                                <button type="button"
                                                    class="btn flex-fill fw-bold rounded-3 py-2"
                                                    onclick="borrarPlato({{ $plato->id }})"
                                                    aria-label="Borrar {{ $plato->nombre }}"
                                                    style="background-color: rgba(239,68,68,0.1);
                                                           color: #EF4444;
                                                           border: 1px solid rgba(239,68,68,0.2);">
                                                    <i class="bi bi-trash"></i>
                                                    <span class="d-none d-sm-inline ms-1">Borrar</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ── Offcanvas detail panel ── --}}
                                    <div class="offcanvas offcanvas-end"
                                        tabindex="-1"
                                        id="offcanvasPlato{{ $plato->id }}"
                                        aria-labelledby="offcanvasPlatoLabel{{ $plato->id }}">

                                        <div class="offcanvas-header border-bottom bg-light">
                                            <h5 class="offcanvas-title fw-bold"
                                                id="offcanvasPlatoLabel{{ $plato->id }}">
                                                <i class="bi bi-info-circle me-2"></i>Detalles del Plato
                                            </h5>
                                            <button type="button" class="btn-close"
                                                data-bs-dismiss="offcanvas"
                                                aria-label="Cerrar"></button>
                                        </div>

                                        <div class="offcanvas-body p-4">
                                            @if($plato->imagen)
                                                <img src="{{ asset('storage/' . $plato->imagen) }}"
                                                    class="w-100 rounded-4 mb-4"
                                                    style="object-fit: cover; max-height: 250px;"
                                                    alt="{{ $plato->nombre }}"
                                                    loading="lazy">
                                            @else
                                                <div class="w-100 rounded-4 mb-4 bg-light border
                                                            d-flex justify-content-center align-items-center"
                                                    style="height: 200px;" aria-hidden="true">
                                                    <i class="bi bi-image fs-1 text-muted opacity-50"></i>
                                                </div>
                                            @endif

                                            <h2 class="fw-bold mb-2">{{ $plato->nombre }}</h2>
                                            <span class="badge rounded-pill text-white px-3 py-2 mb-4 d-inline-block"
                                                style="background-color: {{ $plato->activo ? '#10B981' : '#EF4444' }};">
                                                {{ $plato->activo ? 'Disponible en la carta' : 'Oculto / Agotado' }}
                                            </span>

                                            <h6 class="fw-bold text-muted mb-2 text-uppercase small"
                                                style="letter-spacing: 1px;">
                                                Descripción e Ingredientes
                                            </h6>
                                            <p class="text-dark" style="line-height: 1.6;">
                                                {{ $plato->descripcion ?? 'Este plato no tiene una descripción detallada o lista de ingredientes registrada.' }}
                                            </p>
                                        </div>
                                    </div>
                                </article>

                            @endforeach
                        </div>
                    @endif
                </div>

            </section>

        @endforeach
    @endif

    @include('admin.modals.gestion-categorias')
    @include('admin.modals.agregar-plato')
    @include('admin.modals.listaPlatosOcultos')

    <script>
        /* ── Search / filter ── */
        (function () {
            const buscador = document.getElementById('buscadorPlatos');
            if (!buscador) return;

            let rafId = null;

            buscador.addEventListener('input', function () {
                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(filterPlatos);
            });

            function filterPlatos() {
                const query = buscador.value.trim().toLowerCase();

                document.querySelectorAll('.categoria-bloque').forEach(function (categoria) {
                    const nombreCategoria = categoria.querySelector('.nombre-categoria')
                        .childNodes[0].textContent.trim().toLowerCase();
                    const matchesCategory = nombreCategoria.includes(query);

                    let visibleCount = 0;

                    categoria.querySelectorAll('.col-plato').forEach(function (plato) {
                        const nombrePlato = plato.querySelector('.nombre-plato')
                            .textContent.trim().toLowerCase();
                        const visible = matchesCategory || nombrePlato.includes(query);
                        plato.style.display = visible ? '' : 'none';
                        if (visible) visibleCount++;
                    });

                    categoria.style.display = (visibleCount > 0 || matchesCategory) ? '' : 'none';
                });
            }
        })();

        /* ── Delete plate ── */
        function borrarPlato(idPlato) {
            if (!confirm('¿Estás seguro de que quieres borrar este plato para siempre?')) return;

            const tarjeta = document.getElementById('tarjeta-plato-' + idPlato);

            fetch(`/admin/carta/plato/${idPlato}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.success) {
                    tarjeta.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    tarjeta.style.opacity = '0';
                    tarjeta.style.transform = 'scale(0.95)';
                    setTimeout(function () { tarjeta.remove(); }, 300);
                } else {
                    alert('Hubo un error al borrar el plato.');
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
                alert('Fallo de conexión.');
            });
        }

        /* ── Reactivate hidden plate (called from modalPlatosOcultos) ── */
        function reactivarPlatoSilencioso(idPlato, botonReactivar) {
            const textoOriginal = botonReactivar.innerHTML;
            botonReactivar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            botonReactivar.disabled = true;

            fetch(`/admin/carta/plato/${idPlato}/reactivar`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.success) {
                    /* Remove row from modal */
                    const filaModal = document.getElementById('fila-plato-oculto-' + idPlato);
                    if (filaModal) {
                        filaModal.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        filaModal.style.opacity = '0';
                        filaModal.style.transform = 'translateX(20px)';
                        setTimeout(function () { filaModal.remove(); }, 300);
                    }

                    /* Update main card in-place */
                    const tarjetaPrincipal = document.getElementById('tarjeta-plato-' + idPlato);
                    if (tarjetaPrincipal) {
                        const innerCard = tarjetaPrincipal.querySelector('.card');
                        if (innerCard) innerCard.style.borderTopColor = '#10B981';

                        const badge = tarjetaPrincipal.querySelector('.badge');
                        if (badge) {
                            badge.textContent = 'Disponible';
                            badge.style.backgroundColor = '#10B981';
                        }

                        const switchInput = tarjetaPrincipal.querySelector('.form-check-input');
                        if (switchInput) switchInput.checked = true;

                        const switchLabel = tarjetaPrincipal.querySelector('.form-check-label');
                        if (switchLabel) {
                            switchLabel.textContent = 'Visible';
                            switchLabel.classList.replace('text-danger', 'text-success');
                        }
                    }
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
                alert('Error de conexión.');
                botonReactivar.innerHTML = textoOriginal;
                botonReactivar.disabled = false;
            });
        }
    </script>

</x-layouts.admin>