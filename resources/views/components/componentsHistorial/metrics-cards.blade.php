{{-- ── TARJETAS DE MÉTRICAS ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3 anim" style="animation-delay:.05s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#111827"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Total facturado</div>
                <div class="stat-card__value">{{ number_format($metricas['total_facturado'],2,',','.') }}€</div>
                <div class="stat-card__sub d-flex align-items-center gap-1">
                    {{ $metricas['num_tickets'] }} tickets
                    @if(!is_null($variacion))
                        <span class="{{ $variacion >= 0 ? 'var-up' : 'var-down' }}">
                            {{ $variacion >= 0 ? '↑' : '↓' }}{{ abs($variacion) }}%
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.1s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#059669"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Ticket medio</div>
                <div class="stat-card__value">{{ number_format($metricas['ticket_medio'],2,',','.') }}€</div>
                <div class="stat-card__sub">Máx: {{ number_format($metricas['ticket_maximo'],2,',','.') }}€</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.15s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#2563eb"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Gasto / comensal</div>
                <div class="stat-card__value">{{ number_format($metricas['gasto_por_comensal'],2,',','.') }}€</div>
                <div class="stat-card__sub">{{ $metricas['total_comensales'] }} comensales</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 anim" style="animation-delay:.2s">
        <div class="stat-card p-3 shadow-sm d-flex gap-3">
            <div class="stat-card__accent" style="background:#d97706"></div>
            <div class="flex-fill">
                <div class="stat-card__label">Propinas</div>
                <div class="stat-card__value">{{ number_format($metricas['total_propinas'],2,',','.') }}€</div>
                <div class="stat-card__sub">
                    @if($metricas['num_anuladas'] > 0)
                        <span style="color:#EF4444">{{ $metricas['num_anuladas'] }} anuladas</span>
                    @else
                        IVA: {{ Configuracion('porcentaje_impuestos') }}%
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
