<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filtros de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }
        
        $users = $query->with('subscription')->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => 'required|in:pf,pj',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'is_admin' => $request->boolean('is_admin'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['subscription', 'revenues', 'expenses']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'type' => 'required|in:pf,pj',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'is_admin' => $request->boolean('is_admin'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode deletar seu próprio usuário!');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário deletado com sucesso!');
    }

    /**
     * Activate a user.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        
        return back()->with('success', 'Usuário ativado com sucesso!');
    }

    /**
     * Deactivate a user.
     */
    public function deactivate(User $user)
    {
        // Não permitir desativar o próprio usuário
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode desativar seu próprio usuário!');
        }
        
        $user->update(['is_active' => false]);
        
        return back()->with('success', 'Usuário desativado com sucesso!');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user)
    {
        $newPassword = str()->random(8);
        $user->update(['password' => Hash::make($newPassword)]);
        
        // Aqui você pode enviar email com a nova senha
        // Mail::to($user->email)->send(new PasswordResetMail($newPassword));
        
        return back()->with('success', 'Senha resetada! Nova senha: ' . $newPassword);
    }

    /**
     * Send email verification.
     */
    public function sendVerification(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Este usuário já verificou o email.');
        }
        
        $user->sendEmailVerificationNotification();
        
        return back()->with('success', 'Email de verificação enviado!');
    }

    /**
     * Login as user (impersonate).
     */
    public function loginAs(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode fazer login como você mesmo!');
        }
        
        session(['admin_user_id' => auth()->id()]);
        auth()->login($user);
        
        return redirect()->route('dashboard')
            ->with('info', 'Você está logado como: ' . $user->name);
    }
}
