<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        return view('cliente.perfil.index');
    }
} 