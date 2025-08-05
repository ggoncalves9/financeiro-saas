<?php

namespace App\Services;

use App\Models\User;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FinancialCalculationService
{
    /**
     * Calculate total balance for user
     */
    public function calculateTotalBalance(User $user): float
    {
        $accounts = $user->accounts()->where('is_active', true)->get();
        return $accounts->sum('balance');
    }

    /**
     * Calculate monthly revenues
     */
    public function calculateMonthlyRevenues(User $user, Carbon $month = null): float
    {
        $month = $month ?? now();
        
        return $user->revenues()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->where('status', 'received')
            ->sum('amount');
    }

    /**
     * Calculate monthly expenses
     */
    public function calculateMonthlyExpenses(User $user, Carbon $month = null): float
    {
        $month = $month ?? now();
        
        return $user->expenses()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->where('status', 'paid')
            ->sum('amount');
    }

    /**
     * Calculate monthly balance (revenues - expenses)
     */
    public function calculateMonthlyBalance(User $user, Carbon $month = null): float
    {
        $revenues = $this->calculateMonthlyRevenues($user, $month);
        $expenses = $this->calculateMonthlyExpenses($user, $month);
        
        return $revenues - $expenses;
    }

    /**
     * Get financial data for chart (last 12 months)
     */
    public function getChartData(User $user): array
    {
        $data = [
            'labels' => [],
            'revenues' => [],
            'expenses' => [],
            'balance' => []
        ];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthLabel = $month->format('M/Y');
            
            $revenues = $this->calculateMonthlyRevenues($user, $month);
            $expenses = $this->calculateMonthlyExpenses($user, $month);
            $balance = $revenues - $expenses;

            $data['labels'][] = $monthLabel;
            $data['revenues'][] = $revenues;
            $data['expenses'][] = $expenses;
            $data['balance'][] = $balance;
        }

        return $data;
    }

    /**
     * Calculate expenses by category
     */
    public function getExpensesByCategory(User $user, Carbon $month = null): Collection
    {
        $month = $month ?? now();
        
        return $user->expenses()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->where('status', 'paid')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
    }

    /**
     * Calculate revenues by category
     */
    public function getRevenuesByCategory(User $user, Carbon $month = null): Collection
    {
        $month = $month ?? now();
        
        return $user->revenues()
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->where('status', 'received')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
    }

    /**
     * Get upcoming expenses (next 30 days)
     */
    public function getUpcomingExpenses(User $user): Collection
    {
        return $user->expenses()
            ->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(30)])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get overdue expenses count
     */
    public function getOverdueExpensesCount(User $user): int
    {
        return $user->expenses()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
    }

    /**
     * Calculate goal progress
     */
    public function calculateGoalProgress(Goal $goal): array
    {
        $percentage = $goal->target_amount > 0 
            ? ($goal->current_amount / $goal->target_amount) * 100 
            : 0;

        $remaining = max(0, $goal->target_amount - $goal->current_amount);
        
        $daysRemaining = $goal->target_date 
            ? max(0, now()->diffInDays($goal->target_date, false))
            : null;

        $dailyNeeded = $daysRemaining > 0 
            ? $remaining / $daysRemaining 
            : 0;

        return [
            'percentage' => min(100, $percentage),
            'remaining_amount' => $remaining,
            'days_remaining' => $daysRemaining,
            'daily_amount_needed' => $dailyNeeded,
            'is_on_track' => $this->isGoalOnTrack($goal),
            'completion_date' => $this->estimateGoalCompletion($goal)
        ];
    }

    /**
     * Check if goal is on track
     */
    private function isGoalOnTrack(Goal $goal): bool
    {
        if (!$goal->target_date || $goal->target_amount <= 0) {
            return true;
        }

        $expectedProgress = now()->diffInDays($goal->created_at) / 
                           $goal->created_at->diffInDays($goal->target_date);
        
        $actualProgress = $goal->current_amount / $goal->target_amount;

        return $actualProgress >= $expectedProgress * 0.9; // 10% tolerance
    }

    /**
     * Estimate goal completion date
     */
    private function estimateGoalCompletion(Goal $goal): ?Carbon
    {
        if (!$goal->auto_save_enabled || $goal->auto_save_amount <= 0) {
            return null;
        }

        $remaining = $goal->target_amount - $goal->current_amount;
        if ($remaining <= 0) {
            return now();
        }

        $monthsNeeded = ceil($remaining / $goal->auto_save_amount);
        
        return match($goal->auto_save_frequency) {
            'weekly' => now()->addWeeks($monthsNeeded),
            'monthly' => now()->addMonths($monthsNeeded),
            'quarterly' => now()->addMonths($monthsNeeded * 3),
            'yearly' => now()->addYears($monthsNeeded),
            default => null
        };
    }

    /**
     * Generate financial insights
     */
    public function generateInsights(User $user): array
    {
        $insights = [];
        $currentMonth = now();
        $lastMonth = now()->subMonth();

        // Revenue growth
        $currentRevenues = $this->calculateMonthlyRevenues($user, $currentMonth);
        $lastRevenues = $this->calculateMonthlyRevenues($user, $lastMonth);
        
        if ($lastRevenues > 0) {
            $revenueGrowth = (($currentRevenues - $lastRevenues) / $lastRevenues) * 100;
            
            if ($revenueGrowth > 10) {
                $insights[] = [
                    'type' => 'positive',
                    'title' => 'Crescimento nas Receitas',
                    'message' => "Suas receitas cresceram " . number_format($revenueGrowth, 1) . "% em relação ao mês passado!"
                ];
            } elseif ($revenueGrowth < -10) {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Queda nas Receitas',
                    'message' => "Suas receitas diminuíram " . number_format(abs($revenueGrowth), 1) . "% em relação ao mês passado."
                ];
            }
        }

        // Expense analysis
        $currentExpenses = $this->calculateMonthlyExpenses($user, $currentMonth);
        $lastExpenses = $this->calculateMonthlyExpenses($user, $lastMonth);
        
        if ($lastExpenses > 0) {
            $expenseGrowth = (($currentExpenses - $lastExpenses) / $lastExpenses) * 100;
            
            if ($expenseGrowth > 20) {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Aumento nos Gastos',
                    'message' => "Suas despesas aumentaram " . number_format($expenseGrowth, 1) . "% este mês. Revise seus gastos!"
                ];
            } elseif ($expenseGrowth < -10) {
                $insights[] = [
                    'type' => 'positive',
                    'title' => 'Economia nos Gastos',
                    'message' => "Parabéns! Você economizou " . number_format(abs($expenseGrowth), 1) . "% em despesas este mês."
                ];
            }
        }

        // Goal progress
        $activeGoals = $user->goals()->where('status', 'active')->get();
        $onTrackGoals = $activeGoals->filter(function($goal) {
            return $this->isGoalOnTrack($goal);
        });

        if ($activeGoals->count() > 0) {
            $onTrackPercentage = ($onTrackGoals->count() / $activeGoals->count()) * 100;
            
            if ($onTrackPercentage >= 80) {
                $insights[] = [
                    'type' => 'positive',
                    'title' => 'Metas em Dia',
                    'message' => "Você está no caminho certo! " . number_format($onTrackPercentage, 0) . "% das suas metas estão no prazo."
                ];
            } else {
                $insights[] = [
                    'type' => 'info',
                    'title' => 'Atenção às Metas',
                    'message' => "Algumas metas precisam de atenção. Considere aumentar o valor das contribuições automáticas."
                ];
            }
        }

        // Overdue expenses
        $overdueCount = $this->getOverdueExpensesCount($user);
        if ($overdueCount > 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Despesas Vencidas',
                'message' => "Você tem " . $overdueCount . " despesa(s) em atraso. Regularize para evitar juros e multas."
            ];
        }

        // Savings rate
        $currentBalance = $this->calculateMonthlyBalance($user, $currentMonth);
        if ($currentRevenues > 0) {
            $savingsRate = ($currentBalance / $currentRevenues) * 100;
            
            if ($savingsRate >= 20) {
                $insights[] = [
                    'type' => 'positive',
                    'title' => 'Excelente Taxa de Poupança',
                    'message' => "Sua taxa de poupança é de " . number_format($savingsRate, 1) . "%. Muito bom!"
                ];
            } elseif ($savingsRate < 10 && $savingsRate > 0) {
                $insights[] = [
                    'type' => 'info',
                    'title' => 'Melhore sua Poupança',
                    'message' => "Sua taxa de poupança é de " . number_format($savingsRate, 1) . "%. Tente economizar pelo menos 20% da renda."
                ];
            } elseif ($savingsRate <= 0) {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Gastos Superiores à Renda',
                    'message' => "Seus gastos estão maiores que sua renda. Revise seu orçamento urgentemente!"
                ];
            }
        }

        return $insights;
    }

    /**
     * Format currency for display
     */
    public function formatCurrency(float $amount, string $currency = 'BRL'): string
    {
        return match($currency) {
            'BRL' => 'R$ ' . number_format($amount, 2, ',', '.'),
            'USD' => '$' . number_format($amount, 2, '.', ','),
            'EUR' => '€' . number_format($amount, 2, ',', '.'),
            default => number_format($amount, 2, '.', ',')
        };
    }

    /**
     * Calculate compound interest
     */
    public function calculateCompoundInterest(
        float $principal, 
        float $rate, 
        int $periods, 
        float $monthlyContribution = 0
    ): array {
        $data = [];
        $currentAmount = $principal;
        
        for ($period = 1; $period <= $periods; $period++) {
            $currentAmount = ($currentAmount + $monthlyContribution) * (1 + $rate / 100);
            
            $data[] = [
                'period' => $period,
                'amount' => $currentAmount,
                'interest' => $currentAmount - $principal - ($monthlyContribution * $period),
                'contributions' => $principal + ($monthlyContribution * $period)
            ];
        }
        
        return $data;
    }
}
