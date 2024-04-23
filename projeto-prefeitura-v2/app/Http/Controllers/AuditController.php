<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Audit;

class AuditController extends Controller
{
    public function index() {
        if(Gate::allows('isAtendente')) return redirect()->back();

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Auditoria', [
            'audits' => Audit::with('user')->paginate($itens_por_pag)
        ]);
    }

    public function individual($id) {
        if(Gate::allows('isAtendente')) return redirect()->back();

        $audit = Audit::with('user')->where([['id', '=', $id]])->get()->first();
        return Inertia::render('AuditoriaIndividual', [
            'audit' => $audit,
        ]);
    }

    public function show() {

        $queryUsuario = '';
        $queryEvent = '';
        $queryTabela = '';
        $queryData = '';

        if(request('usuario') != 'null' && request('usuario') != 'undefined') $queryUsuario = request('usuario');
        if(request('evento') != 'null' && request('evento') != 'undefined') $queryEvent = request('evento');
        if(request('tabela') != 'null' && request('tabela') != 'undefined') $queryTabela = request('tabela');
        if(request('data') != 'null' && request('data') != 'undefined') $queryData = request('data');

        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');

        $auditPesquisadas = Audit::with('user')
        ->whereHas('user', function($query) use($queryUsuario) {
            $query->where('name', 'like', '%'.$queryUsuario.'%');
        })
        ->where([['event', 'like', '%'.$queryEvent.'%']])
        ->where([['auditable_type', 'like', '%'.$queryTabela.'%']])
        ->when($queryData, function($query) use ($queryData) {
            return $query->whereDate('created_at', $queryData);
        })
        ->paginate($itens_por_pag);

        if(request('tabela') == 'null') $queryTabela = null;
        if(request('evento') == 'null') $queryEvent = null;

        return Inertia::render('Auditoria', [
            'searchUsuario' => $queryUsuario,
            'searchEvent' => $queryEvent,
            'searchTabela' => $queryTabela,
            'searchData' => $queryData,
            'audits' => $auditPesquisadas,
            'inputFocus' => request('inputFocus')
        ]);
    }
}
