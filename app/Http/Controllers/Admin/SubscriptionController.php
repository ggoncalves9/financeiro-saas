<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request): View
    {
        $query = Subscription::with(['user'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->paginate(20);

        // Get filter options
        $plans = Plan::all();
        $statuses = ['active', 'cancelled', 'trialing', 'expired'];

        return view('admin.subscriptions.index', compact('subscriptions', 'plans', 'statuses'));
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create(): View
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();

        return view('admin.subscriptions.create', compact('users', 'plans'));
    }

    /**
     * Store a newly created subscription.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string',
            'status' => 'required|in:active,trialing,cancelled',
            'amount' => 'required|numeric|min:0',
            'trial_ends_at' => 'nullable|date',
        ]);

        $subscription = Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Assinatura criada com sucesso!');
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load(['user']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(Subscription $subscription): View
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();

        return view('admin.subscriptions.edit', compact('subscription', 'users', 'plans'));
    }

    /**
     * Update the specified subscription.
     */
    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => 'required|string',
            'status' => 'required|in:active,trialing,cancelled',
            'amount' => 'required|numeric|min:0',
            'trial_ends_at' => 'nullable|date',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Assinatura atualizada com sucesso!');
    }

    /**
     * Remove the specified subscription.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Assinatura removida com sucesso!');
    }

    /**
     * Activate a subscription.
     */
    public function activate(Subscription $subscription): JsonResponse
    {
        $subscription->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Assinatura ativada com sucesso!'
        ]);
    }

    /**
     * Cancel a subscription.
     */
    public function cancel(Subscription $subscription): JsonResponse
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assinatura cancelada com sucesso!'
        ]);
    }

    /**
     * Extend a subscription trial.
     */
    public function extend(Request $request, Subscription $subscription): JsonResponse
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $newTrialEnd = $subscription->trial_ends_at 
            ? $subscription->trial_ends_at->addDays($validated['days'])
            : now()->addDays($validated['days']);

        $subscription->update([
            'trial_ends_at' => $newTrialEnd,
            'status' => 'trialing'
        ]);

        return response()->json([
            'success' => true,
            'message' => "Período de teste estendido por {$validated['days']} dias!"
        ]);
    }
}
