<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PessoaRequest;
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
            'pessoa' => $pessoa,
            
        ]);
    }

    function update(PessoaRequest $request) {
        
        Pessoa::findOrFail($request->id)->update($request->all());
        return redirect('/pessoas')->with('message', 'A pessoa foi salva com sucesso!');
    }

    function destroy($id) {
        try {
            $pessoa = Pessoa::findOrFail($id);
            if($pessoa->delete()){
                return redirect('/pessoas')->with('message', 'A pessoa foi deletada com sucesso!');
            }else{
                return redirect()->back()->with('message', 'Erro ao deletar pessoa');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('message', 'Pessoa não encontrada');
        }
    }
}
