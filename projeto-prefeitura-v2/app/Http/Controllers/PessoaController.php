<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pessoa;


class PessoaController extends Controller
{ 
    //
    function index() {
        return Inertia::render('Pessoas', [
            'pessoas' => Pessoa::paginate(5)
        ]);
        // enviar as pessoas junto como props
    }
}
