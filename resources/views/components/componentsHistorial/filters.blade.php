{{-- ── FILTROS ───────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin.historial') }}" method="GET" id="formFiltros">

            {{-- Pills de período --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(['hoy'=>'Hoy','semana'=>'Esta semana','mes'=>'Este mes',
                          'anio'=>'Este año','mes_especifico'=>'Mes…',
                          'rango_custom'=>'Rango…','todo'=>'Todo'] as $val => $label)
                    <button type="button"
                        class="pill-filter {{ $filtroTiempo === $val ? 'active' : '' }}"
                        onclick="setPeriodo('{{ $val }}', this)">
                        {{ $label }}
                    </button>
                @endforeach
                <input type="hidden" name="tiempo" id="inputTiempo" value="{{ $filtroTiempo }}">
            </div>

            {{-- Campos extra por tipo de período --}}
            <div id="extraMesEspecifico"
                 class="{{ $filtroTiempo === 'mes_especifico' ? '' : 'd-none' }} mb-3">
                <input type="month" name="mes_exacto"
                    class="form-control form-control-sm w-auto d-inline-block border-0 bg-light rounded-3"
                    value="{{ request('mes_exacto', now()->format('Y-m')) }}">
            </div>
            <div id="extraRangoCustom"
                 class="{{ $filtroTiempo === 'rango_custom' ? '' : 'd-none' }} mb-3">
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="date" name="fecha_desde"
                        class="form-control form-control-sm w-auto border-0 bg-light rounded-3"
                        value="{{ request('fecha_desde', now()->format('Y-m-d')) }}">
                    <span class="text-muted">—</span>
                    <input type="date" name="fecha_hasta"
                        class="form-control form-control-sm w-auto border-0 bg-light rounded-3"
                        value="{{ request('fecha_hasta', now()->format('Y-m-d')) }}">
                </div>
            </div>

            {{-- Selects secundarios --}}
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <select name="caja_id" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="todas">Todas las cajas</option>
                    @foreach($cajas as $caja)
                        <option value="{{ $caja->id }}" {{ request('caja_id') == $caja->id ? 'selected':'' }}>
                            {{ $caja->nombre }}
                        </option>
                    @endforeach
                </select>

                <select name="metodo_pago" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="todos">Todos los métodos</option>
                    <option value="efectivo"      {{
                     request('metodo_pago')=='efectivo'      ?'selected':'' }}>💵 Efectivo</option>
                    <option value="tarjeta"       {{ request('metodo_pago')=='tarjeta'       ?'selected':'' }}>💳 Tarjeta</option>
                    <option value="bizum"         {{ request('metodo_pago')=='bizum'         ?'selected':'' }}>📱 Bizum</option>
                    <option value="transferencia" {{ request('metodo_pago')=='transferencia' ?'selected':'' }}>🏦 Transferencia</option>
                </select>

                <select name="estado" class="form-select form-select-sm border-0 bg-light fw-bold w-auto rounded-3">
                    <option value="activas"  {{ request('estado','activas')=='activas'  ?'selected':'' }}>Solo activas</option>
                    <option value="anuladas" {{ request('estado')=='anuladas' ?'selected':'' }}>Solo anuladas</option>
                    <option value="todas"    {{ request('estado')=='todas'    ?'selected':'' }}>Todas</option>
                </select>

                <button type="submit"
                    class="btn btn-sm fw-bold px-3 rounded-pill"
                    style="background:#111827;color:#fff;border:none">
                    <i class="bi bi-funnel-fill me-1"></i>Filtrar
                </button>

                @if(request()->hasAny(['caja_id','metodo_pago','estado','mes_exacto','fecha_desde']))
                    <a href="{{ route('admin.historial') }}"
                       class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-x me-1"></i>Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
