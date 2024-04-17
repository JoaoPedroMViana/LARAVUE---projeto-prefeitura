<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartamentosRequest;
use App\Models\Departamento;
use App\Models\Permissoe;
use App\Models\User;
use Inertia\Inertia;

class DepartamentosController extends Controller
{
    public function index() {
        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Departamentos', [
            'departamentos' => Departamento::paginate($itens_por_pag)
        ]);
    }

    public function create() {
        return Inertia::render('CadastroDepartamentos');
    }

    public function store(DepartamentosRequest $request) {
        $departamento = Departamento::where([['nome', '=', $request->nome]])->get()->first();

        if($departamento) {
            return redirect()->back()->with('message_error', 'Departamento já cadastrado');
        }else{
            Departamento::create([
                'nome' => $request->nome
            ]);
            return redirect('/departamentos')->with('message', 'Departamento cadastrado com sucesso!');
        }
    }

    public function edit($id) {
        $departamento = Departamento::findOrFail($id);
        $atendentes = User::where([['perfil', '=','A']])->get(); 
        $permitidos = Permissoe::with('user')->where('departamento_id', '=',$id)->get();

        return Inertia::render('EditarDepartamento', [
            'departamento' => $departamento,
            'usuarios' => $atendentes,
            'permitidos' => $permitidos
        ]);
    }

    public function update(DepartamentosRequest $request) {
        $departamento = Departamento::where([['nome', '=', $request->nome]])->get()->first();

        if($departamento) {
            return redirect()->back()->with('message_error', 'Departamento já cadastrado');
        }else{
            $departamento = Departamento::findOrFail($request->id);
            $departamento->update([
                'nome' => $request->nome,
            ]);
            return redirect('/departamentos')->with('message', 'Departamento salvo com sucesso!');
        }
    }

    public function liberarPermissao(Request $request) {
        $hasPermissoe =  Permissoe::where([['departamento_id', '=', $request->departamento_id], ['user_id', '=',$request->user_id]])->get()->first();

        if($hasPermissoe){
            return redirect()->back()->with('message_error', 'O usuário já possui esta permissão');
        }else{
            Permissoe::create([
                'departamento_id' => $request->departamento_id,
                'user_id' => $request->user_id,
                'data' => $request->data_liberacao
            ]);
    
            return redirect()->back()->with('message', 'Permissão liberada com sucesso!');
        }
    }

    public function removerPermissao($id) {
        try {
            $permissao = Permissoe::findOrFail($id);
            if($permissao->delete()){
                return redirect()->back()->with('message', 'Permissão removida com sucesso!');
            }else{
                return redirect()->back()->with('message_error', 'Erro ao remover permissão');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('message_error', 'Permissão não encontrada');
        }
    }
}
