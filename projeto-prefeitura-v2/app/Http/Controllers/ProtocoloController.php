<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProtocoloRequest;
use Inertia\Inertia;
use App\Models\Protocolo;
use App\Models\Pessoa;

class ProtocoloController extends Controller
{
    //
    function index() {
        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Protocolos', [
           'protocolos' => Protocolo::with('pessoa')->paginate($itens_por_pag)
        ]);
    }

    function create() {
        return Inertia::render('CadastroProtocolos', [
            'pessoas_select' => Pessoa::select('id', 'nome')->get()
        ]);
    }

    function store(ProtocoloRequest $request) {

        Protocolo::create([
            'descricao' => $request->descricao,
            'data_registro' => $request->data_registro,
            'prazo' => $request->prazo,
            'pessoa_id' => $request->pessoa_id
        ]);

        return redirect('/protocolos')->with('message', 'O protocolo foi criado com sucesso!');
    }

    function show() {
        $queryNumero = '';
        $queryDescricao = '';
        $queryNome = '';
        $queryData = '';

        if(request('numero') != 'null' && request('numero') != 'undefined') $queryNumero = request('numero');
        if(request('descricao') != 'null' && request('descricao') != 'undefined') $queryDescricao = request('descricao');
        if(request('pessoa') != 'null' && request('pessoa') != 'undefined') $queryNome = request('pessoa');
        if(request('data') != 'null' && request('data') != 'undefined') $queryData = request('data');

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
 
        $protocolosPesquisados = Protocolo::with('pessoa')
        ->where('numero', 'like', '%' . $queryNumero . '%')
        ->where('descricao', 'like', '%' . $queryDescricao . '%')
        ->whereHas('pessoa', function ($query) use ($queryNome) {
            $query->where('nome', 'like', '%' . $queryNome . '%');
        })
        ->when($queryData, function ($query) use ($queryData) {
            return $query->whereDate('data_registro', $queryData);
        })
        ->paginate($itens_por_pag);
        return Inertia::render('Protocolos', [
            'searchNumero' => $queryNumero,
            'protocolos' => $protocolosPesquisados,
            'searchDescricao' => $queryDescricao,
            'searchPessoa' => $queryNome,
            'searchData' => $queryData,
            'focus' => request('inputFocus')
        ]);
    }

    function edit($numero) {
        $protocolo = Protocolo::with('pessoa')->where([['numero', $numero]])->get();
        return Inertia::render('Editar_protocolo', [
            'protocolo' => $protocolo,
            'pessoas_select' => Pessoa::select('id', 'nome')->get()
        ]);
    }

    function update(ProtocoloRequest $request) {
        Protocolo::where([['numero', $request->numero]])->update($request->all());
        return redirect('/protocolos')->with('message', 'O protocolo foi salvo com sucesso!');
    }

    function destroy($numero) {
        try {
            $protocolo = Protocolo::where([['numero', $numero]]);
            if($protocolo->delete()){
                return redirect('/protocolos')->with('message', 'O protocolo foi deletado com sucesso!');
            }else{
                return redirect()->back()->with('message', 'Erro ao deletar protocolo');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('message', 'Protocolo não encontrado');
        }
    }
}
