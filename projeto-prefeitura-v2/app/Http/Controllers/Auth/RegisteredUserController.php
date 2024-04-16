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
use App\Http\Requests\PasswordRequest;

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

    public function edit($id): Response
    {
        $user = User::findOrFail($id);
        return Inertia::render('Editar_usuario', [
            'user' => $user,
        ]);
    }

    public function update(UserRequest $request): RedirectResponse
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'name'=> $request->name,
            'perfil' => $request->perfil
        ]);

        return redirect('/usuarios')->with('message', 'Usuário salvo com sucesso!');
    }

    public function desativar($id): RedirectResponse 
    {
        $user = User::findOrFail($id);
       
        if($user->ativo == 'S'){
            $user->update(['ativo' => 'N']);
            return redirect()->back()->with('message', 'Usuário desativado');
        }else{
            return redirect()->back()->with('message', 'O usuário já está desativado');
        }
    }

    public function ativar($id): RedirectResponse 
    {
        $user = User::findOrFail($id);
       
        if($user->ativo == 'N'){
            $user->update(['ativo' => 'S']);
            return redirect()->back()->with('message', 'Usuário ativado');
        }else{
            return redirect()->back()->with('message', 'O usuário já está ativado');
        }
    }

    public function mudar_senha(PasswordRequest $request): RedirectResponse 
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->back()->with('message', 'Senha alterada com sucesso');
    }
}
