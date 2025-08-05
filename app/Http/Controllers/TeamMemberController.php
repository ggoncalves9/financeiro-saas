<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::where('company_user_id', Auth::id())
            ->with('member')
            ->get();

        return view('team.index', compact('teamMembers'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('team.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('temporary123'),
            'user_type' => 'employee',
            'team_leader_id' => Auth::id()
        ]);

        // Criar membro da equipe
        $teamMember = TeamMember::create([
            'user_id' => $user->id,
            'team_leader_id' => Auth::id(),
            'role' => $validated['role'],
            'status' => 'active'
        ]);

        // Atribuir permissões
        if (!empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        // Enviar email de boas-vindas (implementar depois)
        // Mail::to($user)->send(new TeamMemberInvitation($user, 'temporary123'));

        return redirect()->route('team.index')
            ->with('success', 'Membro da equipe adicionado com sucesso!');
    }

    public function show(TeamMember $teamMember)
    {
        $this->authorize('view', $teamMember);
        
        $teamMember->load('user.permissions');
        
        return view('team.show', compact('teamMember'));
    }

    public function edit(TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);
        
        $permissions = Permission::all();
        $teamMember->load('user.permissions');
        
        return view('team.edit', compact('teamMember', 'permissions'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Atualizar usuário
        $teamMember->user->update([
            'name' => $validated['name']
        ]);

        // Atualizar membro da equipe
        $teamMember->update([
            'role' => $validated['role']
        ]);

        // Atualizar permissões
        $teamMember->user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('team.index')
            ->with('success', 'Membro da equipe atualizado com sucesso!');
    }

    public function destroy(TeamMember $teamMember)
    {
        $this->authorize('delete', $teamMember);

        // Desativar ao invés de excluir
        $teamMember->update(['status' => 'inactive']);
        $teamMember->user->update(['status' => 'inactive']);

        return redirect()->route('team.index')
            ->with('success', 'Membro da equipe removido da equipe!');
    }

    public function activate(TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);

        $teamMember->update(['status' => 'active']);
        $teamMember->user->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Membro ativado com sucesso!');
    }

    public function deactivate(TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);

        $teamMember->update(['status' => 'inactive']);
        $teamMember->user->update(['status' => 'inactive']);

        return redirect()->back()
            ->with('success', 'Membro desativado!');
    }

    public function updatePermissions(Request $request, TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $teamMember->user->syncPermissions($validated['permissions'] ?? []);

        return redirect()->back()
            ->with('success', 'Permissões atualizadas com sucesso!');
    }
}
