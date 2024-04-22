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
            'audit' => Audit::with('user')->paginate($itens_por_pag)
        ]);
    }
}
