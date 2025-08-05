<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $plan = null): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->tenant) {
            return redirect()->route('billing.plans')->with('error', 'Você precisa de uma assinatura ativa para acessar este recurso.');
        }

        $tenant = $user->tenant;
        
        // Check if user has active subscription
        if (!$tenant->subscribed()) {
            return redirect()->route('billing.plans')->with('error', 'Sua assinatura expirou. Renove para continuar usando o sistema.');
        }

        // Check specific plan requirements
        if ($plan && !$tenant->subscribedToPrice($plan)) {
            return redirect()->route('billing.plans')->with('error', 'Este recurso requer um plano superior. Faça upgrade para continuar.');
        }

        // Check plan limits
        if (!$this->checkPlanLimits($tenant, $request)) {
            return redirect()->back()->with('error', 'Você atingiu o limite do seu plano. Faça upgrade para continuar.');
        }

        return $next($request);
    }

    /**
     * Check if user has reached plan limits
     */
    private function checkPlanLimits($tenant, $request): bool
    {
        $route = $request->route()->getName();
        $limits = $tenant->getPlanLimits();

        // Check specific limits based on route
        switch ($route) {
            case 'revenues.store':
            case 'revenues.create':
                $count = $tenant->users()->withCount('revenues')->first()->revenues_count ?? 0;
                return $count < $limits['revenues'];

            case 'expenses.store':
            case 'expenses.create':
                $count = $tenant->users()->withCount('expenses')->first()->expenses_count ?? 0;
                return $count < $limits['expenses'];

            case 'goals.store':
            case 'goals.create':
                $count = $tenant->users()->withCount('goals')->first()->goals_count ?? 0;
                return $count < $limits['goals'];

            case 'accounts.store':
            case 'accounts.create':
                $count = $tenant->users()->withCount('accounts')->first()->accounts_count ?? 0;
                return $count < $limits['accounts'];

            case 'team.store':
            case 'team.invite':
                if (!$limits['team_members']) {
                    return false;
                }
                $count = $tenant->teamMembers()->count();
                return $count < $limits['team_members'];

            default:
                return true;
        }
    }
}
