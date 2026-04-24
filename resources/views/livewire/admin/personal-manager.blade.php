<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>
<div>
    {{-- ==========================================
         SECCIÓN 1: EMPLEADOS
         ========================================== --}}
    <div class="row g-3 mb-4">
        
        <div class="col-12 col-xl-5">
            <div class="cfg-card h-100">
                <div class="cfg-card__head">
                    <div class="cfg-card__icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h5>Añadir Empleado</h5>
                        <p>Crea accesos para tu equipo</p>
                    </div>
                </div>

                @if($mensajeExito)
                    <div class="alert alert-success py-2 small mb-3"><i class="bi bi-check-circle me-1"></i> {{ $mensajeExito }}</div>
                @endif
                @if($mensajeError)
                    <div class="alert alert-danger py-2 small mb-3"><i class="bi bi-exclamation-triangle me-1"></i> {{ $mensajeError }}</div>
                @endif

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="mb-3">
                        <label class="cfg-label">Nombre del empleado *</label>
                        <input type="text" class="form-control" wire:model="nombre" placeholder="Ej: Carlos Cocina">
                        @error('nombre') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="cfg-label">Correo electrónico *</label>
                        <input type="email" class="form-control" wire:model="email" placeholder="carlos@tufast.com">
                        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-sm-6">
                            <label class="cfg-label">Contraseña *</label>
                            <input type="password" class="form-control" wire:model="password" placeholder="Mínimo 6 letras">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="cfg-label">Rol del sistema *</label>
                            <select class="form-select" wire:model="rol">
                                <option value="cocina">Chef / Cocina</option>
                                <option value="admin">Administrador</option>
                            </select>
                            @error('rol') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-save w-100 justify-content-center mt-auto" wire:click="crearEmpleado" wire:loading.attr="disabled" wire:target="crearEmpleado">
                    <span wire:loading.remove wire:target="crearEmpleado">
                        <i class="bi bi-plus-circle me-1"></i> Crear Cuenta
                    </span>
                    <span wire:loading wire:target="crearEmpleado">
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Creando...
                    </span>
                </button>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="cfg-card d-flex flex-column h-100">
                <div class="cfg-card__head">
                    <div class="cfg-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h5>Equipo Actual</h5>
                        <p>Usuarios con acceso al panel</p>
                    </div>
                </div>

                <div class="flex-grow-1 overflow-auto pe-2" style="max-height: 400px;">
                    <div class="d-flex flex-column gap-2">
                        @forelse($empleados as $empleado)
                            <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center" style="background: #FAFAFA;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm" 
                                         style="width: 42px; height: 42px; background: {{ $empleado->rol == 'admin' ? '#111827' : '#FF7A00' }};">
                                        {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $empleado->nombre }}</h6>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $empleado->email }}</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge {{ $empleado->rol == 'admin' ? 'bg-dark' : 'bg-light text-dark border' }}">
                                        <i class="bi {{ $empleado->rol == 'admin' ? 'bi-shield-lock-fill' : 'bi-fire text-orange' }} me-1"></i>
                                        {{ ucfirst($empleado->rol) }}
                                    </span>
                                    
                                    @if(auth()->id() !== $empleado->id)
                                        <button class="btn btn-sm text-danger border-0 p-1" wire:click="borrarEmpleado({{ $empleado->id }})" wire:confirm="¿Seguro que deseas eliminar este acceso?">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted bg-light rounded-3 border border-dashed">
                                <i class="bi bi-person-x fs-1 text-secondary mb-2 opacity-50 d-block"></i>
                                <p class="mb-0 fw-medium">No hay empleados registrados.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Separador Visual --}}
    <hr class="my-5 text-muted opacity-25">

    {{-- ==========================================
         SECCIÓN 2: CAJAS Y COBRO
         ========================================== --}}
    <div class="row g-3">
        
        <div class="col-12 col-xl-5">
            <div class="cfg-card h-100">
                <div class="cfg-card__head">
                    <div class="cfg-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <h5>Añadir Caja</h5>
                        <p>Puntos de cobro para el TPV</p>
                    </div>
                </div>

                @if($mensajeExitoCaja)
                    <div class="alert alert-success py-2 small mb-3"><i class="bi bi-check-circle me-1"></i> {{ $mensajeExitoCaja }}</div>
                @endif
                @if($mensajeErrorCaja)
                    <div class="alert alert-danger py-2 small mb-3"><i class="bi bi-exclamation-triangle me-1"></i> {{ $mensajeErrorCaja }}</div>
                @endif

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="mb-3">
                        <label class="cfg-label">Nombre de la caja *</label>
                        <input type="text" class="form-control" wire:model="nombreCaja" placeholder="Ej: Barra Principal">
                        @error('nombreCaja') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-check form-switch fs-5 mb-0">
                        <input class="form-check-input mt-0" type="checkbox" role="switch" id="activaCajaSwitch" wire:model="activaCaja">
                        <label class="form-check-label fw-medium ms-2 mt-1 fs-6 text-dark" for="activaCajaSwitch">
                            Caja Activa
                        </label>
                    </div>
                    <p class="cfg-hint mb-0 ms-1 mt-1">Si la desactivas, los camareros no podrán seleccionarla al cobrar.</p>
                </div>

                <button type="button" class="btn-save w-100 justify-content-center mt-auto" style="background: #10b981;" wire:click="crearCaja" wire:loading.attr="disabled" wire:target="crearCaja">
                    <span wire:loading.remove wire:target="crearCaja">
                        <i class="bi bi-plus-circle me-1"></i> Añadir Caja
                    </span>
                    <span wire:loading wire:target="crearCaja">
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Creando...
                    </span>
                </button>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="cfg-card d-flex flex-column h-100">
                <div class="cfg-card__head">
                    <div class="cfg-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                        <i class="bi bi-hdd-network"></i>
                    </div>
                    <div>
                        <h5>Cajas Registradas</h5>
                        <p>Gestión de los puntos de cobro</p>
                    </div>
                </div>

                <div class="flex-grow-1 overflow-auto pe-2" style="max-height: 400px;">
                    <div class="d-flex flex-column gap-2">
                        @forelse($cajas as $caja)
                            <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center" style="background: #FAFAFA;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm" 
                                         style="width: 42px; height: 42px; background: {{ $caja->activa ? '#10b981' : '#6b7280' }}; transition: background 0.3s;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $caja->nombre }}</h6>
                                        <div class="text-muted" style="font-size: 0.75rem;">Id interno: #{{ $caja->id }}</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Botón para encender/apagar la caja rápido --}}
                                    <button class="btn btn-sm {{ $caja->activa ? 'btn-outline-success' : 'btn-outline-secondary' }}" 
                                            wire:click="toggleCaja({{ $caja->id }})" title="Clic para activar/desactivar">
                                        <i class="bi {{ $caja->activa ? 'bi-toggle-on' : 'bi-toggle-off' }} fs-5"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm text-danger border-0 p-1" wire:click="borrarCaja({{ $caja->id }})" wire:confirm="¿Seguro que deseas eliminar esta caja? (No se borrará el historial de ventas)">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted bg-light rounded-3 border border-dashed">
                                <i class="bi bi-cash-stack fs-1 text-secondary mb-2 opacity-50 d-block"></i>
                                <p class="mb-0 fw-medium">No hay cajas registradas.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>