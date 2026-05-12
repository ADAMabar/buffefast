<x-layouts.cliente>

    <div class="position-relative w-100" style="height: 150px; background-color: #111827;">
        
        <div class="position-absolute top-0 start-0 w-100 h-100" 
             style="background-image: url('{{ configuracion('logo_url') }}'); 
                    background-size: cover; 
                    background-position: center; 
                    filter: blur(2px); 
                    transform: scale(1.2); 
                    opacity: 0.6;">
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0,0,0,0.25);"></div>

        <div class="position-absolute bottom-0 start-0 w-100" style="height: 50px; background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, #FFFFFF 100%); z-index: 1;"></div>
    </div>

    <div class="d-flex flex-column px-4 pb-4" style="margin-top: -45px; position: relative; z-index: 2; background-color: transparent;">
        
        <div class="text-center mb-4">
            <img src="{{ configuracion('logo_url') }}" alt="Logo" style="max-width: 70%; max-height: 70%; object-fit: contain;">
            <h1 class="h5 fw-bold mb-1 text-dark">Bienvenido a {{ configuracion('nombre_restaurante') }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Ingresa el código de tu mesa.</p>
        </div>

        <form action="{{ route('cliente.acceder') }}" method="POST" class="w-100">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold text-muted ms-1 mb-1" style="font-size: 0.8rem;">Código de la Mesa</label>
                <input type="text" name="codigo" class="form-control text-center fw-bold mb-3 shadow-sm"
                    placeholder="Ej: A1B2C3" maxlength="6"
                    style="font-size: 1.25rem; letter-spacing: 0.2rem; border-radius: 12px; border: 1px solid #E5E7EB; text-transform: uppercase; background-color: #F9FAFB; padding: 0.75rem;"
                    value="{{ old('codigo') }}" required>

                @error('codigo')
                    <div class="text-danger mt-1 fw-medium mb-3 ms-1" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror

                {{-- Input Nombre --}}
                <label class="form-label fw-bold text-muted ms-1 mb-1" style="font-size: 0.8rem;">Tu Nombre</label>
                <input type="text" name="nombre" class="form-control fw-medium shadow-sm"
                    placeholder="Ej: Juan" 
                    style="border-radius: 12px; border: 1px solid #E5E7EB; background-color: #F9FAFB; padding: 0.75rem 1rem;"
                    value="{{ old('nombre') }}" required>

                @error('nombre')
                    <div class="text-danger mt-1 fw-medium ms-1" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn w-100 py-3 fw-bold shadow-sm"
                style="background-color: var(--primary-orange); color: white; border-radius: 12px; font-size: 1rem; transition: transform 0.2s;">
                Entrar a la Mesa
            </button>
        </form>

      
        <div class="mt-4 text-center w-100">
            <p class="mb-0" style="font-size: 0.75rem; color: #9CA3AF;">
                Sistema de <span class="fw-bold" style="color: var(--primary-orange);">BuffeFast</span>
            </p>
        </div>

    </div>
</x-layouts.cliente>