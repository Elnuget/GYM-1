<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $gimnasio = auth()->user()->cliente->gimnasio;
        $servicios = $gimnasio->servicios;
        
        return view('cliente.servicios.index', compact('servicios'));
    }
} 