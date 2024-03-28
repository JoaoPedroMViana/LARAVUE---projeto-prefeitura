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

    function show() {
        $query = '';
        if(request('search') != 'null') $query = request('search'); // verificar se possui uma query 

        $pessoasPesquisadas = Pessoa::where([['nome', 'like', '%'.$query.'%']])->paginate(5);
        return Inertia::render('Pessoas', [
            'pesquisa' => request('search'),
            'pessoas' => $pessoasPesquisadas
        ]);
    }
}
