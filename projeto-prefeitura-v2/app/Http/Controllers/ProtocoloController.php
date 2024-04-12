<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProtocoloRequest;
use App\Http\Requests\RequestAnexos;
use Inertia\Inertia;
use App\Models\Protocolo;
use App\Models\Arq_anexado;
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
        $protocolo = Protocolo::create([
            'descricao' => $request->descricao,
            'data_registro' => $request->data_registro,
            'prazo' => $request->prazo,
            'pessoa_id' => $request->pessoa_id
        ]);
        
        $file = null;
        $url = '';


        if($request->hasFile('files')){
            foreach($request->file('files') as $e){
                if($e->isValid()){
                    $file = $e;
                    $file->storePublicly('public');
                    $name = $file->hashName();
                    $url = Storage::url('public/'.$name);
                    Arq_anexado::create([
                        'path' => $url,
                        'protocolo_id' => $protocolo->id
                    ]);
                };
            }
        }

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
        $anexados = Arq_anexado::where([['protocolo_id', $numero]])->get();
        return Inertia::render('Editar_protocolo', [
            'protocolo' => $protocolo,
            'anexados' => $anexados,
            'pessoas_select' => Pessoa::select('id', 'nome')->get()
        ]);
    }

    function update(ProtocoloRequest $request) {

        $newData = $request->only(['descricao', 'data_registro', 'prazo','pessoa_id']);
        
        Protocolo::where([['numero', $request->numero]])->update($newData);

        $file = null;
        $url = '';

        if($request->hasFile('files')){
            foreach($request->file('files') as $e){
                if($e->isValid()){
                    $file = $e;
                    $file->storePublicly('public');
                    $name = $file->hashName();
                    $url = Storage::url('public/'.$name);
                    Arq_anexado::create([
                        'path' => $url,
                        'protocolo_id' => $request->numero
                    ]);
                };
            }
        }

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

    // Anexos:

    function storeAnexo(RequestAnexos $request) {
        $anexados = Arq_anexado::where([['protocolo_id', '=',request('numero')]])->get();
        if(count($anexados) == 5) return redirect()->back()->with('message_error', 'O protocolo já possui o tamanho máximo (5) de arquivos anexados');// verifica se o protocolo já possui 5 arquivos anexados

        $file = null;
        $url = '';

        if($request->hasFile('files')){
            foreach($request->file('files') as $e){
                if($e->isValid()){
                    $file = $e;
                    $file->storePublicly('public');
                    $name = $file->hashName();
                    $url = Storage::url('public/'.$name);
                    Arq_anexado::create([
                        'path' => $url,
                        'protocolo_id' => request('numero')
                    ]);
                };
            }
        }

        return redirect()->back()->with('message', 'Anexo adicionado ao protocolo com sucesso!');
    }

    function deleteAnexo($id) {
        $anexo = Arq_Anexado::find($id);
        $path = str_replace("/storage", "public", $anexo->path);

        if($anexo){
            $anexo->delete();
            if(Storage::exists($path)){
                Storage::delete($path);
            }
            return redirect()->back()->with('message', 'Anexo excluido com sucesso!');
        }else{
            return redirect()->back()->with('message_error', 'Anexo não encontrado');
        }
    }

    function downloadAnexo($path) {
        if(Storage::exists("public/".$path)){
          return Storage::download("public/".$path);
        }// não ta baixando 
    }
}
