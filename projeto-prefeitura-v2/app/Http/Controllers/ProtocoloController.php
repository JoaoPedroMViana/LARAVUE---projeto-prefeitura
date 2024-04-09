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
