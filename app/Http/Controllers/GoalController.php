<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $query = Goal::where('user_id', Auth::id());

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $goals = $query->orderBy('target_date', 'asc')->paginate(10);

        $stats = [
            'active_goals' => $goals->where('status', 'active')->count(),
            'completed_goals' => $goals->where('status', 'completed')->count(),
            'expired_goals' => $goals->where('status', 'expired')->count(),
            'total_goals' => $goals->count(),
        ];

        // Total economizado (somando saved_amount das metas concluídas)
        $total_saved = $goals->where('status', 'completed')->sum('saved_amount');
        $stats['total_saved_formatted'] = number_format($total_saved, 2, ',', '.');

        // Progresso médio (exemplo: média do campo progress de todas as metas)
        $average_progress = $goals->count() > 0 ? $goals->avg('progress') : 0;
        $stats['average_progress'] = $average_progress;

        return view('goals.index', compact('goals', 'stats'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('goals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'required|date|after:today',
            'account_id' => 'required|exists:accounts,id',
            'priority' => 'required|in:low,medium,high',
            'auto_contribution' => 'boolean',
            'contribution_amount' => 'nullable|numeric|min:0',
            'contribution_frequency' => 'nullable|in:weekly,monthly'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';
        $validated['current_amount'] = 0;

        $goal = Goal::create($validated);

        return redirect()->route('goals.index')
                        ->with('success', 'Meta criada com sucesso!');
    }

    public function show(Goal $goal)
    {
        $this->authorize('view', $goal);

        return view('goals.show', compact('goal'));
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        $accounts = Account::where('user_id', Auth::id())->get();
        return view('goals.edit', compact('goal', 'accounts'));
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'priority' => 'required|in:low,medium,high',
            'auto_contribution' => 'boolean',
            'contribution_amount' => 'nullable|numeric|min:0',
            'contribution_frequency' => 'nullable|in:weekly,monthly'
        ]);

        $goal->update($validated);

        return redirect()->route('goals.index')
                        ->with('success', 'Meta atualizada com sucesso!');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return redirect()->route('goals.index')
                        ->with('success', 'Meta excluída com sucesso!');
    }

    public function addContribution(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $goal->increment('current_amount', $validated['amount']);
        
        // Debitar da conta
        $goal->account->decrement('balance', $validated['amount']);

        // Verificar se a meta foi atingida
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->update(['status' => 'completed']);
        }

        return redirect()->back()
                        ->with('success', 'Contribuição adicionada com sucesso!');
    }

    public function withdraw(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $goal->current_amount
        ]);

        $goal->decrement('current_amount', $validated['amount']);
        
        // Creditar na conta
        $goal->account->increment('balance', $validated['amount']);

        return redirect()->back()
                        ->with('success', 'Saque realizado com sucesso!');
    }

    public function complete(Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->update(['status' => 'completed']);

        return redirect()->back()
                        ->with('success', 'Meta marcada como concluída!');
    }

    public function pause(Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->update(['status' => 'paused']);

        return redirect()->back()
                        ->with('success', 'Meta pausada!');
    }

    public function resume(Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->update(['status' => 'active']);

        return redirect()->back()
                        ->with('success', 'Meta reativada!');
    }
}
