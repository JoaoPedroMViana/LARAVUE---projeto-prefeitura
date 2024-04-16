<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartamentosRequest;
use App\Models\Departamento;
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
}
