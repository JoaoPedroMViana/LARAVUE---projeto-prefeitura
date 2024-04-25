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
use Illuminate\Support\Facades\Gate;

class RegisteredUserController extends Controller
{

    public function index() 
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        $itens_por_pag = 7;
        if(request('itens_pag')) $itens_por_pag = request('itens_pag');
        return Inertia::render('Usuarios', [
            'users' => User::paginate($itens_por_pag)
        ]);
        
    }
    /**
     * Display the registration view.
     */
    public function create()
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        // Validação do tipo de usuário
        if(Auth::user()->perfil == 'S' && $request->perfil != 'A'){
            return redirect()->back()->with('message_error', 'Apenas Administradores da TI podem criar este tipo de usuário');
        }else{
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

    public function edit($id)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        $user = User::findOrFail($id);
        $canEdit = true;
        if(Auth::user()->perfil == 'S' && $user->perfil != 'A'){
            $canEdit = false;
        }
        return Inertia::render('Editar_usuario', [
            'user' => $user,
            'canEdit' => $canEdit
        ]);
    }

    public function update(UserRequest $request)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        if(Auth::user()->perfil == 'S' && $request->perfil != 'A'){
            return redirect()->back()->with('message_error', 'Apenas Administradores da TI podem editar para este tipo de usuário');
        }
        $user = User::findOrFail($request->id);
        $user->update([
            'name'=> $request->name,
            'perfil' => $request->perfil
        ]);

        return redirect('/usuarios')->with('message', 'Usuário salvo com sucesso!');
    }

    public function desativar($id)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        $user = User::findOrFail($id);
       
        if($user->ativo == 'S'){
            $user->update(['ativo' => 'N']);
            return redirect()->back()->with('message', 'Usuário desativado');
        }else{
            return redirect()->back()->with('message', 'O usuário já está desativado');
        }
    }

    public function ativar($id)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        $user = User::findOrFail($id);
       
        if($user->ativo == 'N'){
            $user->update(['ativo' => 'S']);
            return redirect()->back()->with('message', 'Usuário ativado');
        }else{
            return redirect()->back()->with('message', 'O usuário já está ativado');
        }
    }

    public function mudar_senha(PasswordRequest $request)
    {
        if(Gate::allows('isAtendente')) return redirect()->back();
        $user = User::findOrFail($request->id);
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->back()->with('message', 'Senha alterada com sucesso');
    }
}
