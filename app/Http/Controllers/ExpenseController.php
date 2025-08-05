<?php

namespace App\Http\Controllers;

use function config;
use App\Models\Expense;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', Auth::id())
                        ->with(['account']);

        // Filtros
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->input('account_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->input('search') . '%');
        }

        $expenses = $query->orderBy('due_date', 'desc')
                         ->paginate(15);

        $accounts = Account::where('user_id', Auth::id())->get();
        $categories = Category::where(function($q){
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->where('type', 'expense')->orderBy('name')->get();

        // Cálculo dos cards de resumo
        $total_paid = Expense::where('user_id', Auth::id())->where('status', 'paid')->sum('amount');
        $total_pending = Expense::where('user_id', Auth::id())->where('status', 'pending')->sum('amount');
        $total_cancelled = Expense::where('user_id', Auth::id())->where('status', 'cancelled')->sum('amount');
        $total_amount = Expense::where('user_id', Auth::id())->sum('amount');
        $current_month = Expense::where('user_id', Auth::id())
            ->whereMonth('due_date', Carbon::now()->month)
            ->whereYear('due_date', Carbon::now()->year)
            ->sum('amount');
        $monthly_average = Expense::where('user_id', Auth::id())
            ->selectRaw('SUM(amount)/COUNT(DISTINCT MONTH(due_date)) as avg')
            ->value('avg') ?? 0;
        $summary = [
            'total_amount' => $total_amount,
            'paid_amount' => $total_paid,
            'pending_amount' => $total_pending,
            'cancelled_amount' => $total_cancelled,
            'current_month' => $current_month,
            'paid_formatted' => number_format($total_paid, 2, ',', '.'),
            'pending_formatted' => number_format($total_pending, 2, ',', '.'),
            'cancelled_formatted' => number_format($total_cancelled, 2, ',', '.'),
            'total_formatted' => number_format($total_amount, 2, ',', '.'),
            'monthly_average_formatted' => number_format($monthly_average, 2, ',', '.'),
            // Adiciona despesas vencidas
            'overdue_formatted' => number_format(
                Expense::where('user_id', Auth::id())
                    ->where('status', 'overdue')
                    ->sum('amount'),
                2, ',', '.'
            )
        ];

        return view('expenses.index', compact('expenses', 'accounts', 'categories', 'summary'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        $categories = config('financeiro.expense_categories', []);

        return view('expenses.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:expense_categories,id',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|in:monthly,yearly',
            'recurring_until' => 'nullable|date|after:due_date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        if ($request->hasFile('receipt')) {
            $validated['receipt_url'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create($validated);

        return redirect()->route('expenses.index')
                        ->with('success', 'Despesa criada com sucesso!');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        $expense->load(['account', 'category']);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $accounts = Account::where('user_id', Auth::id())->get();
        $categories = config('financeiro.expense_categories', []);

        return view('expenses.edit', compact('expense', 'accounts', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:expense_categories,id',
            'is_recurring' => 'boolean',
            'recurring_type' => 'nullable|in:monthly,yearly',
            'recurring_until' => 'nullable|date|after:due_date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($request->hasFile('receipt')) {
            // Remove old receipt
            if ($expense->receipt_url) {
                Storage::disk('public')->delete($expense->receipt_url);
            }
            $validated['receipt_url'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
                        ->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        if ($expense->receipt_url) {
            Storage::disk('public')->delete($expense->receipt_url);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
                        ->with('success', 'Despesa excluída com sucesso!');
    }

    public function approve(Expense $expense)
    {
        $this->authorize('approve', $expense);

        $expense->update(['status' => 'approved']);

        return redirect()->back()
                        ->with('success', 'Despesa aprovada com sucesso!');
    }

    public function reject(Expense $expense)
    {
        $this->authorize('approve', $expense);

        $expense->update(['status' => 'rejected']);

        return redirect()->back()
                        ->with('success', 'Despesa rejeitada!');
    }

    public function markAsPaid(Expense $expense)
    {
        $this->authorize('pay', $expense);

        $expense->update([
            'status' => 'paid',
            'paid_date' => now()
        ]);

        // Debitar da conta
        $expense->account->decrement('balance', $expense->amount);

        return redirect()->back()
                        ->with('success', 'Despesa marcada como paga!');
    }

    public function duplicate(Expense $expense)
    {
        $this->authorize('view', $expense);

        $newExpense = $expense->replicate();
        $newExpense->due_date = Carbon::parse($expense->due_date)->addMonth();
        $newExpense->status = 'pending';
        $newExpense->paid_date = null;
        $newExpense->save();

        return redirect()->route('expenses.edit', $newExpense)
                        ->with('success', 'Despesa duplicada com sucesso!');
    }

    public function export(Request $request)
    {
        $query = Expense::where('user_id', Auth::id())
                        ->with(['account', 'category']);

        // Aplicar mesmos filtros da listagem
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('due_date', 'desc')->get();

        $filename = 'despesas_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // Headers do CSV
            fputcsv($file, [
                'Data',
                'Descrição',
                'Valor',
                'Conta',
                'Categoria',
                'Status',
                'Data Pagamento'
            ]);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->due_date->format('d/m/Y'),
                    $expense->description,
                    'R$ ' . number_format($expense->amount, 2, ',', '.'),
                    $expense->account->name,
                    $expense->category->name,
                    ucfirst($expense->status),
                    $expense->paid_date ? $expense->paid_date->format('d/m/Y') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getCategories()
    {
        $categories = config('financeiro.expense_categories', []);
        return response()->json($categories);
    }

    /**
     * Quick store expense for dashboard modal
     */
    public function quickStore(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'description' => 'required|string|max:255',
                'account_id' => 'required|exists:accounts,id',
                'category_id' => 'required|exists:categories,id',
                'status' => 'required|in:pending,paid',
                'is_recurring' => 'boolean',
                'frequency' => 'nullable|string|in:monthly,weekly,yearly',
                'repeat_until' => 'nullable|date'
            ]);

            // Get user accounts and categories
            $account = Account::where('user_id', auth()->id())->find($validatedData['account_id']);
            $category = Category::where('user_id', auth()->id())->find($validatedData['category_id']);

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conta não encontrada ou não pertence ao usuário.'
                ], 422);
            }
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoria não encontrada ou não pertence ao usuário.'
                ], 422);
            }

            $expense = Expense::create([
                'user_id' => auth()->id(),
                'amount' => $validatedData['amount'],
                'title' => $validatedData['description'],
                'description' => $validatedData['description'],
                'account_id' => $validatedData['account_id'],
                'category' => $category->name ?? 'Sem categoria',
                'status' => $validatedData['status'],
                'recurring' => $request->boolean('is_recurring'),
                'recurring_type' => $validatedData['frequency'] ?? null,
                'recurring_until' => $validatedData['repeat_until'] ?? null,
                'date' => now()
            ]);

            // If paid, update account balance
            if ($validatedData['status'] === 'paid') {
                $account->decrement('balance', $validatedData['amount']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Despesa criada com sucesso!',
                'expense' => $expense
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar despesa rápida: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }
}
