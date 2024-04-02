<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pessoa;


class PessoaController extends Controller
{ 
    //
    function index() {
        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Pessoas', [
            'pessoas' => Pessoa::paginate($itens_por_pag)
        ]);
    }

    function show() {
        $query = '';
        if(request('search') != 'null') $query = request('search'); // verificar se possui uma query 

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');

        $pessoasPesquisadas = Pessoa::where([['nome', 'like', '%'.$query.'%']])->paginate($itens_por_pag);
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
