<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PessoaRequest;
use Inertia\Inertia;
use App\Models\Pessoa;
use Illuminate\Support\Arr;


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

    function create() {
        return Inertia::render('CadastroPessoas');
    } 

    function store(PessoaRequest $request) {

        Pessoa::create([
            'nome' => $request->nome,
            'data_nascimento' => $request->data_nascimento,
            'CPF' => $request->CPF,
            'sexo' => $request->sexo,
            'cidade' => $request->cidade,
            'bairro' => $request->bairro,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'complemento' => $request->complemento
        ]);

        return redirect('/pessoas')->with('message', 'A pessoa foi criada com sucesso!');
    }

    function show() {
        $queryNome = '';
        $queryCpf = '';
        $querySexo = '';

        // verificar se possui uma query 
        if(request('nome') != 'undefined' && request('nome') != 'null') $queryNome = request('nome');
        if(request('cpf') != 'undefined' && request('cpf') != 'null') $queryCpf = request('cpf');
        if(request('sexo') != 'undefined' && request('sexo') != 'null' && request('sexo') != 'false') $querySexo = request('sexo');

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');

        $pessoasPesquisadas = Pessoa::where([['nome', 'like', '%'.$queryNome.'%'], ['cpf', 'like', $queryCpf.'%'], ['sexo','like', $querySexo.'%']])->paginate($itens_por_pag);
        return Inertia::render('Pessoas', [
            'searchNome' => $queryNome,
            'searchCpf' => $queryCpf,
            'searchSexo' => $querySexo,
            'pessoas' => $pessoasPesquisadas,
            'focus' => request('inputFocus')
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
