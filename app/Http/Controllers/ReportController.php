<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $revenues = Revenue::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expenses = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netIncome = $revenues - $expenses;

        // Receitas agrupadas (sem categoria_id)
        // Se existir outro campo para agrupar, substitua abaixo. Caso contrário, retorna apenas o total geral.
        $revenuesByCategory = [
            [
                'label' => 'Total de Receitas',
                'total' => Revenue::where('user_id', Auth::id())
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount')
            ]
        ];

        // Despesas agrupadas (sem category_id)
        $expensesByCategory = [
            [
                'label' => 'Total de Despesas',
                'total' => Expense::where('user_id', Auth::id())
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount')
            ]
        ];

        return view('reports.income-statement', compact(
            'revenues', 'expenses', 'netIncome', 
            'revenuesByCategory', 'expensesByCategory',
            'startDate', 'endDate'
        ));
    }

    public function cashFlow(Request $request)
    {
        $months = $request->get('months', 12);
        $startDate = now()->subMonths($months);

        $cashFlow = [];
        
        for ($i = $months; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $revenues = Revenue::where('user_id', Auth::id())
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses = Expense::where('user_id', Auth::id())
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $cashFlow[] = [
                'month' => $month->format('M/Y'),
                'revenues' => $revenues,
                'expenses' => $expenses,
                'net' => $revenues - $expenses
            ];
        }

        return view('reports.cash-flow', compact('cashFlow', 'months'));
    }

    public function categoryAnalysis(Request $request)
    {
        $period = $request->get('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
        }

        // Detalhamento por categoria - receitas
        $revenueColors = ['#10b981','#06b6d4','#6366f1','#f59e0b','#ef4444','#64748b'];
        $revenueCategories = Revenue::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->map(function($item, $i) use ($revenueColors) {
                return [
                    'name' => $item->category ?: 'Sem categoria',
                    'total' => $item->total,
                    'color' => $revenueColors[$i % count($revenueColors)]
                ];
            })->toArray();

        // Detalhamento por categoria - despesas
        $expenseColors = ['#ef4444','#f59e0b','#06b6d4','#6366f1','#10b981','#64748b'];
        $expenseCategories = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->map(function($item, $i) use ($expenseColors) {
                return [
                    'name' => $item->category ?: 'Sem categoria',
                    'total' => $item->total,
                    'color' => $expenseColors[$i % count($expenseColors)]
                ];
            })->toArray();

        // Totais gerais (mantidos para compatibilidade)
        $topRevenueCategories = [
            [
                'label' => 'Total de Receitas',
                'total' => array_sum(array_column($revenueCategories, 'total'))
            ]
        ];
        $topExpenseCategories = [
            [
                'label' => 'Total de Despesas',
                'total' => array_sum(array_column($expenseCategories, 'total'))
            ]
        ];

        return view('reports.category-analysis', compact(
            'revenueCategories', 'expenseCategories',
            'topRevenueCategories', 'topExpenseCategories',
            'period', 'startDate', 'endDate'
        ));
    }

    public function goalProgress()
    {
        $goals = Goal::where('user_id', Auth::id())
            ->orderBy('target_date', 'asc')
            ->get()
            ->map(function ($goal) {
                $goal->progress_percentage = $goal->target_amount > 0 
                    ? ($goal->current_amount / $goal->target_amount) * 100 
                    : 0;
                
                $goal->days_remaining = now()->diffInDays($goal->target_date, false);
                
                $goal->monthly_required = $goal->days_remaining > 0 
                    ? max(0, ($goal->target_amount - $goal->current_amount) / ($goal->days_remaining / 30))
                    : 0;

                return $goal;
            });

        return view('reports.goal-progress', compact('goals'));
    }

    public function dre(Request $request)
    {
        // Só para PJ
        if (Auth::user()->user_type !== 'pj') {
            abort(403);
        }

        $year = $request->get('year', now()->year);

        $data = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $revenues = Revenue::where('user_id', Auth::id())
                ->whereBetween('received_date', [$startDate, $endDate])
                ->sum('amount');

            $expenses = Expense::where('user_id', Auth::id())
                ->whereBetween('paid_date', [$startDate, $endDate])
                ->sum('amount');

            $data[] = [
                'month' => $startDate->format('M'),
                'revenues' => $revenues,
                'expenses' => $expenses,
                'profit' => $revenues - $expenses
            ];
        }

        return view('reports.dre', compact('data', 'year'));
    }

    public function taxSummary(Request $request)
    {
        // Só para PJ
        if (Auth::user()->user_type !== 'pj') {
            abort(403);
        }

        $year = $request->get('year', now()->year);
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        $totalRevenues = Revenue::where('user_id', Auth::id())
            ->whereBetween('received_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = Expense::where('user_id', Auth::id())
            ->whereBetween('paid_date', [$startDate, $endDate])
            ->sum('amount');

        $grossProfit = $totalRevenues - $totalExpenses;

        // Simulação de impostos (valores aproximados)
        $taxes = [
            'irpj' => $grossProfit * 0.15, // 15% IRPJ
            'csll' => $grossProfit * 0.09, // 9% CSLL
            'pis' => $totalRevenues * 0.0165, // 1.65% PIS
            'cofins' => $totalRevenues * 0.076, // 7.6% COFINS
        ];

        $totalTaxes = array_sum($taxes);
        $netProfit = $grossProfit - $totalTaxes;

        return view('reports.tax-summary', compact(
            'totalRevenues', 'totalExpenses', 'grossProfit', 
            'taxes', 'totalTaxes', 'netProfit', 'year'
        ));
    }

    public function teamExpenses(Request $request)
    {
        // Só para PJ
        if (Auth::user()->user_type !== 'pj') {
            abort(403);
        }

        $period = $request->get('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
        }

        $teamExpenses = Expense::where('user_id', Auth::id())
            ->whereBetween('paid_date', [$startDate, $endDate])
            ->whereNotNull('team_member_id')
            ->with(['teamMember', 'category'])
            ->get()
            ->groupBy('team_member_id');

        return view('reports.team-expenses', compact('teamExpenses', 'period'));
    }
}
