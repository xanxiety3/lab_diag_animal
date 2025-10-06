<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales no coinciden con nuestros registros.'],
        ]);
    }

    // Validar contraseña
    if (!Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales no coinciden con nuestros registros.'],
        ]);
    }

    // Validar estado
    if ($user->estado !== 'activo') {
        throw ValidationException::withMessages([
            'email' => ['El usuario no está activo. Contacte al administrador.'],
        ]);
    }

    // Autenticación manual
    Auth::login($user);

    return redirect()->route('dashboard');
}



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegisterForm()
{
    $roles = Role::all();
    return view('auth.register', compact('roles'));
}



    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'numero_documento' => ['required', 'string', 'max:20', 'unique:users'],
            'telefono' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'rol_id' => ['required', 'integer'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'numero_documento' => $request->numero_documento,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
            'estado' => 'activo'
        ]);

        return redirect()->route('dashboard');
    }
}


