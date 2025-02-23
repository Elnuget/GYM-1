<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MembresiaController extends Controller
{
    public function index()
    {
        $gimnasio = auth()->user()->cliente->gimnasio;
        $membresias = $gimnasio->membresias;
        
        return view('cliente.membresias.index', compact('membresias'));
    }
} 