<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProtocoloRequest;
use App\Http\Requests\RequestAnexos;
use Inertia\Inertia;
use App\Models\Protocolo;
use App\Models\Arq_anexado;
use App\Models\Departamento;
use App\Models\Acompanhamento;
use App\Models\User;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ProtocoloController extends Controller
{
    //
    function index() {
        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');

        $protocolos = Protocolo::with('pessoa', 'departamento')->paginate($itens_por_pag);
         
        if(Gate::allows('isAtendente')){
            $user = User::with('permissoes')->find(Auth::user()->id);
            $permitidos_id = [];
            foreach($user->permissoes as $permissao){
                array_push($permitidos_id, $permissao->departamento_id);
            }
        
            $protocolos = Protocolo::with('pessoa', 'departamento')->whereIn('departamento_id', $permitidos_id)->paginate($itens_por_pag);  
            // Pega só os procolocos com o departamento_id igual aos departamento_id das permissões do usuário
        }
        
        return Inertia::render('Protocolos', [
           'protocolos' => $protocolos,
        ]);
    }

    function create() {
        $departamentos_select = Departamento::select('id', 'nome')->get();
        if(Gate::allows('isAtendente')){
            $user = User::with('permissoes')->find(Auth::user()->id);
            $permitidos_id = [];
            foreach($user->permissoes as $permissao){
                array_push($permitidos_id, $permissao->departamento_id);
            }
            $departamentos_select = Departamento::select('id', 'nome')->whereIn('id', $permitidos_id)->get();   
            // pega só os departamentos que o usuário tem permissão
        }
        return Inertia::render('CadastroProtocolos', [
            'pessoas_select' => Pessoa::select('id', 'nome')->get(),
            'departamentos_select' => $departamentos_select
        ]);
    }

    function store(ProtocoloRequest $request) {
        $protocolo = Protocolo::create([
            'descricao' => $request->descricao,
            'data_registro' => $request->data_registro,
            'prazo' => $request->prazo,
            'pessoa_id' => $request->pessoa_id,
            'departamento_id' => $request->departamento_id,
            'situacao' => $request->situacao
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

        $permitidos_id = [];
        if(Gate::allows('isAtendente')){
            $user = User::with('permissoes')->find(Auth::user()->id);
            foreach($user->permissoes as $permissao){
                array_push($permitidos_id, $permissao->departamento_id);
            }

        }else{
            $permitidos_id = Departamento::select('id')->get();
        }

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
 
        $protocolosPesquisados = Protocolo::with('pessoa', 'departamento')
        ->whereIn('departamento_id', $permitidos_id)
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
        $departamentos_select = Departamento::select('id', 'nome')->get();
        if(Gate::allows('isAtendente')){
            $user = User::with('permissoes')->find(Auth::user()->id);
            $permitidos_id = [];
            foreach($user->permissoes as $permissao){
                array_push($permitidos_id, $permissao->departamento_id);
            }
            $departamentos_select = Departamento::select('id', 'nome')->whereIn('id', $permitidos_id)->get();   
            // pega só os departamentos que o usuário tem permissão
        }
        $protocolo = Protocolo::with('pessoa', 'departamento')->where([['numero', $numero]])->get();
        $anexados = Arq_anexado::where([['protocolo_id', $numero]])->get();
        $acompanhamentos = Acompanhamento::where([['protocolo_id', $numero]])->orderBy('data', 'desc')->get();
        return Inertia::render('Editar_protocolo', [
            'protocolo' => $protocolo,
            'anexados' => $anexados,
            'acompanhamentos' => $acompanhamentos,
            'pessoas_select' => Pessoa::select('id', 'nome')->get(),
            'departamentos_select' => $departamentos_select,
        ]);
    }

    function update(ProtocoloRequest $request) {

        $newData = $request->only(['descricao', 'data_registro', 'prazo','pessoa_id', 'departamento_id', 'situacao']);
        
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
          //return Storage::disk('public')->download($path);  
        }// não ta baixando 
    }
}
