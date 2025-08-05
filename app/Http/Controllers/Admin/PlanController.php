<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with(['users'])
            ->withCount('users')
            ->orderBy('sort_order')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $features = config('features.available');
        return view('admin.plans.create', compact('features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_days' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:1',
            'max_transactions' => 'nullable|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        DB::transaction(function () use ($validated) {
            $plan = Plan::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'billing_cycle' => $validated['billing_cycle'],
                'trial_days' => $validated['trial_days'] ?? 0,
                'max_users' => $validated['max_users'],
                'max_transactions' => $validated['max_transactions'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => $validated['sort_order'] ?? 0,
                'features' => $validated['features'] ?? []
            ]);
        });

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano criado com sucesso!');
    }

    public function show(Plan $plan)
    {
        $plan->load(['users', 'features']);
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        $features = config('features.available');
        return view('admin.plans.edit', compact('plan', 'features'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'trial_days' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:1',
            'max_transactions' => 'nullable|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        DB::transaction(function () use ($plan, $validated) {
            $plan->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'billing_cycle' => $validated['billing_cycle'],
                'trial_days' => $validated['trial_days'] ?? 0,
                'max_users' => $validated['max_users'],
                'max_transactions' => $validated['max_transactions'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => $validated['sort_order'] ?? 0,
                'features' => $validated['features'] ?? []
            ]);
        });

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->users()->count() > 0) {
            return redirect()
                ->route('admin.plans.index')
                ->with('error', 'Não é possível excluir um plano que possui usuários.');
        }

        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano excluído com sucesso!');
    }

    public function activate(Plan $plan)
    {
        $plan->update(['is_active' => true]);
        
        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano ativado com sucesso!');
    }

    public function deactivate(Plan $plan)
    {
        $plan->update(['is_active' => false]);
        
        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano desativado com sucesso!');
    }

    public function toggle(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $plan->update(['is_active' => $validated['is_active']]);

        return response()->json([
            'success' => true,
            'message' => $validated['is_active'] ? 'Plano ativado!' : 'Plano desativado!',
            'is_active' => $validated['is_active']
        ]);
    }
}
