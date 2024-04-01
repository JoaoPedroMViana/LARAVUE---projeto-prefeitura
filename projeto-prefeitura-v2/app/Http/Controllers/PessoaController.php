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
    }

    function show() {
        $query = '';
        if(request('search') != 'null') $query = request('search'); // verificar se possui uma query 

        $pessoasPesquisadas = Pessoa::where([['nome', 'like', '%'.$query.'%']])->paginate(5);
        return Inertia::render('Pessoas', [
            'pesquisa' => $query,
            'pessoas' => $pessoasPesquisadas
        ]);
    }

    function edit($id) {
        $pessoa = Pessoa::findOrFail($id);
        return Inertia::render('Editar_pessoa', [
            'pessoa' => $pessoa
        ]);
    }
}
