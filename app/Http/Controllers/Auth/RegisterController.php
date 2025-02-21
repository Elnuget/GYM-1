<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\DuenoGimnasio;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function create()
    {
        $gimnasios = Gimnasio::all(['id_gimnasio', 'nombre']);
        return view('auth.register', compact('gimnasios'));
    }

    public function registerCliente(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'gimnasio_id' => ['required', 'exists:gimnasios,id_gimnasio'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente',
            'telefono' => $request->telefono,
        ]);

        Cliente::create([
            'user_id' => $user->id,
            'gimnasio_id' => $request->gimnasio_id,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function registerDueno(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'nombre_comercial' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'dueño',
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        DuenoGimnasio::create([
            'user_id' => $user->id,
            'nombre_comercial' => $request->nombre_comercial,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
