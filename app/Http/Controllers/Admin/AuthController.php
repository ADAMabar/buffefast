<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginAdminRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginAdminRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirigir según el rol con el mensaje de éxito
        if (Auth::user()->rol === 'cocina') {
            return redirect()->to('/cocina') 
        ->with('success', '¡Sesión iniciada correctamente! Bienvenido a la cocina.');
}

            return redirect()->intended('/dashboard')
                ->with('success', '¡Sesión iniciada correctamente!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}