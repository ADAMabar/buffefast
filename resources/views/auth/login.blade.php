<x-layouts.guest>
    <div class="clean-card w-100" style="max-width: 420px;" >
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                style="width: 100px; height: 100px; border-radius: 14px; background: rgba(255,122,0,0.1); border: 1px solid var(--primary-orange);">
                <img src="{{ asset('storage/logos/LogoBuffeFast.png') }}" alt="Logo" style="width: 350px; height: 170px;">
            </div>
            <h2 class="h4 fw-semibold mb-1 text-dark">BuffeFast System</h2>
            <p class="small" style="color: var(--text-muted);">Acceso exclusivo para Staff de <span class="fw-bold" style="color: var(--primary-orange);"> {{ configuracion('nombre_restaurante') }}</span></p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label small ms-1 fw-medium" style="color: var(--text-main);">Correo
                    Electrónico</label>
                <input type="email" class="form-control form-control-2026" id="email" name="email"
                    placeholder="admin@buffefast.com" value="{{ old('email') }}" autofocus>
                @error('email')
                    <div class="text-danger small mt-1 ms-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small ms-1 fw-medium"
                    style="color: var(--text-main);">Contraseña</label>
                <input type="password" class="form-control form-control-2026" id="password" name="password"
                    placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-orange w-100 mb-3">
                Ingresar al Sistema
            </button>
        </form>
    </div>
</x-layouts.guest>