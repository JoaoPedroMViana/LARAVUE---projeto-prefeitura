<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\UserRequest;

class RegisteredUserController extends Controller
{

    public function index() 
    {
        $itens_por_pag = 5;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Usuarios', [
            'users' => User::paginate($itens_por_pag)
        ]);
    }
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request): RedirectResponse
    {

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => $request->perfil,
            'cpf' => $request->cpf,
            'ativo' => $request->ativo
        ]);

        return redirect('/usuarios')->with('message', 'Usuário criado com sucesso!');
    }
}
