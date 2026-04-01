<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;

class RifaController extends Controller
{
    public function index()
    {
        // Busca a primeira rifa do banco junto com os números dela
        $rifa = Rifa::with('numeros')->first();
        
        // Manda os dados para uma tela chamada 'welcome'
        return view('welcome', compact('rifa'));
    }
}