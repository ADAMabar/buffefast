<x-layouts.guest>
    <div class="clean-card w-100" style="max-width: 420px;">
        <div class="text-center mb-4">
            <h2 class="h4 fw-semibold mb-1 text-dark">Recuperar Contraseña</h2>
            <p class="small" style="color: var(--text-muted);">Introduce tu correo y te enviaremos las instrucciones</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success small text-center fw-medium mb-4" style="border-radius: 10px; background-color: #d1e7dd; color: #0f5132; padding: 12px;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label small ms-1 fw-medium" style="color: var(--text-main);">Correo Electrónico</label>
                <input type="email" class="form-control form-control-2026" id="email" name="email"
                    placeholder="ejemplo@buffefast.com" required autofocus>
                
                @error('email')
                    <div class="text-danger small mt-1 ms-1 fw-bold">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-orange w-100 mb-3">
                Enviar instrucciones
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('login') }}" class="small text-decoration-none fw-medium" style="color: var(--text-muted);">
                    &larr; Volver al Login
                </a>
            </div>
        </form>
    </div>
</x-layouts.guest>