<x-layouts.cliente>
    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-4" style="flex: 1;">

        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center mb-4"
                style="width: 72px; height: 72px; border-radius: 20px; background: rgba(255,122,0,0.1); border: 2px solid var(--primary-orange);">
                <span style="color: var(--primary-orange); font-size: 2.5rem; font-weight: 700;">B</span>
            </div>
            <h1 class="h3 fw-bold mb-2">Bienvenido a BuffeFast</h1>
            <p class="text-muted">Ingresa el código de tu mesa para empezar a pedir.</p>
        </div>

        <form action="{{ route('cliente.acceder') }}" method="POST" class="w-100">
            @csrf
            <div class="mb-4 text-center">
                <input type="text" name="codigo" class="form-control form-control-lg text-center fw-bold mb-3"
                    placeholder="Ej: A1B2C3" maxlength="6"
                    style="font-size: 1.5rem; letter-spacing: 0.5rem; border-radius: 16px; border: 2px solid #E5E7EB; text-transform: uppercase;"
                    value="{{ old('codigo') }}" required>

                @error('codigo')
                    <div class="text-danger small mt-2 fw-medium mb-3">{{ $message }}</div>
                @enderror

                <input type="text" name="nombre" class="form-control form-control-lg text-center fw-bold"
                    placeholder="Tu nombre (Ej: Juan)" 
                    style="border-radius: 16px; border: 2px solid #E5E7EB;"
                    value="{{ old('nombre') }}" required>

                @error('nombre')
                    <div class="text-danger small mt-2 fw-medium">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn w-100 py-3 fw-bold rounded-pill"
                style="background-color: var(--primary-orange); color: white; font-size: 1.1rem; transition: transform 0.2s;">
                Entrar a la Mesa
            </button>
        </form>

    </div>
</x-layouts.cliente>