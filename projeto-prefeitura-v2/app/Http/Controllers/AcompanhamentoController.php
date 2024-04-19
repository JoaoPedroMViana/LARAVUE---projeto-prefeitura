<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Acompanhamento;
use App\Models\Protocolo;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AcompanhamentoRequest;

class AcompanhamentoController extends Controller
{
    public function store(AcompanhamentoRequest $request) {
        Acompanhamento::create([
            'protocolo_id' => $request->protocolo_id,
            'descricao' => $request->descricao,
            'data' => $request->data_registro
        ]);

        $newData = $request->only(['situacao']);
        Protocolo::where([['numero', $request->protocolo_id]])->update($newData);

        return redirect()->back()->with('message', 'Acompanhamento adicionado com sucesso!');
    }
}
