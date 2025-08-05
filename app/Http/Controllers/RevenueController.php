<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Revenue;
use App\Models\Account;
use App\Models\Category;

class RevenueController extends Controller
{
    /**
     * Display a listing of revenues.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->revenues()->with('account');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('client_name', 'like', '%' . $request->search . '%');
            });
        }

        // Business revenues filter for PJ users
        if ($user->isPJ() && $request->filled('business_only')) {
            $query->business();
        }

        $revenues = $query->latest('date')->paginate(20);

        // Get categories for filter dropdown
        $categories = config('financeiro.revenue_categories', []);

        // Get summary statistics
        $total_received = $user->revenues()->confirmed()->sum('amount');
        $total_pending = $user->revenues()->pending()->sum('amount');
        $total_amount = $query->sum('amount');
        $monthly_average = $user->revenues()->selectRaw('SUM(amount)/COUNT(DISTINCT MONTH(date)) as avg')->value('avg') ?? 0;
        $summary = [
            'total_amount' => $total_amount,
            'confirmed_amount' => $total_received,
            'pending_amount' => $total_pending,
            'current_month' => $user->revenues()->currentMonth()->sum('amount'),
            'received_formatted' => number_format($total_received, 2, ',', '.'),
            'pending_formatted' => number_format($total_pending, 2, ',', '.'),
            'total_formatted' => number_format($total_amount, 2, ',', '.'),
            'monthly_average_formatted' => number_format($monthly_average, 2, ',', '.')
        ];

        return view('revenues.index', compact('revenues', 'categories', 'summary'));
    }

    /**
     * Show the form for creating a new revenue.
     */
    public function create()
    {
        $user = Auth::user();
        $accounts = $user->accounts()->active()->get();
        $categories = \App\Models\Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->where('type', 'revenue')->orderBy('name')->get();
        return view('revenues.create', compact('accounts', 'categories'));
    }

    /**
     * Store a newly created revenue.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:50',
            'client_name' => 'nullable|string|max:255',
            'client_document' => 'nullable|string|max:20',
            'recurring' => 'boolean',
            'recurring_type' => 'nullable|in:weekly,monthly,yearly',
            'recurring_until' => 'nullable|date|after:date',
            'status' => 'required|in:pending,confirmed',
            'is_business_revenue' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify account ownership
        $account = $user->accounts()->findOrFail($request->account_id);

        // Handle file upload
        $receiptUrl = null;
        if ($request->hasFile('receipt')) {
            $receiptUrl = $request->file('receipt')->store('receipts', 'public');
        }

        // Create revenue
        $revenue = $user->revenues()->create([
            'account_id' => $account->id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'payment_method' => $request->payment_method,
            'client_name' => $request->client_name,
            'client_document' => $request->client_document,
            'recurring' => $request->boolean('recurring'),
            'recurring_type' => $request->recurring_type,
            'recurring_until' => $request->recurring_until,
            'status' => $request->status,
            'is_business_revenue' => $request->boolean('is_business_revenue'),
            'tax_rate' => $request->tax_rate ?? 0,
            'invoice_number' => $request->invoice_number,
            'reference_number' => $request->reference_number,
            'receipt_url' => $receiptUrl,
            'tags' => $request->tags,
            'notes' => $request->notes,
        ]);

        // Update account balance if confirmed
        if ($request->status === 'confirmed') {
            $account->addTransaction($request->amount, 'credit', "Receita: {$request->title}");
        }

        return redirect()->route('revenues.index')->with('success', 'Receita cadastrada com sucesso!');
    }

    /**
     * Display the specified revenue.
     */
    public function show(Revenue $revenue)
    {
        $this->authorize('view', $revenue);

        return view('revenues.show', compact('revenue'));
    }

    /**
     * Show the form for editing the specified revenue.
     */
    public function edit(Revenue $revenue)
    {
        $this->authorize('update', $revenue);

        $user = Auth::user();
        $accounts = $user->accounts()->active()->get();
        $categories = \App\Models\Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->where('type', 'revenue')->orderBy('name')->get();
        return view('revenues.edit', compact('revenue', 'accounts', 'categories'));
    }

    /**
     * Update the specified revenue.
     */
    public function update(Request $request, Revenue $revenue)
    {
        $this->authorize('update', $revenue);

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|string|max:50',
            'client_name' => 'nullable|string|max:255',
            'client_document' => 'nullable|string|max:20',
            'recurring' => 'boolean',
            'recurring_type' => 'nullable|in:weekly,monthly,yearly',
            'recurring_until' => 'nullable|date|after:date',
            'status' => 'required|in:pending,confirmed,cancelled',
            'is_business_revenue' => 'boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify account ownership
        $account = $user->accounts()->findOrFail($request->account_id);

        $oldAmount = $revenue->amount;
        $oldStatus = $revenue->status;
        $oldAccount = $revenue->account;

        // Handle file upload
        $receiptUrl = $revenue->receipt_url;
        if ($request->hasFile('receipt')) {
            // Delete old file
            if ($receiptUrl) {
                Storage::disk('public')->delete($receiptUrl);
            }
            $receiptUrl = $request->file('receipt')->store('receipts', 'public');
        }

        // Update revenue
        $revenue->update([
            'account_id' => $account->id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'payment_method' => $request->payment_method,
            'client_name' => $request->client_name,
            'client_document' => $request->client_document,
            'recurring' => $request->boolean('recurring'),
            'recurring_type' => $request->recurring_type,
            'recurring_until' => $request->recurring_until,
            'status' => $request->status,
            'is_business_revenue' => $request->boolean('is_business_revenue'),
            'tax_rate' => $request->tax_rate ?? 0,
            'invoice_number' => $request->invoice_number,
            'reference_number' => $request->reference_number,
            'receipt_url' => $receiptUrl,
            'tags' => $request->tags,
            'notes' => $request->notes,
        ]);

        // Update account balances if needed
        if ($oldStatus === 'confirmed' && $request->status !== 'confirmed') {
            // Remove from old account
            $oldAccount->addTransaction($oldAmount, 'debit', "Estorno: {$revenue->title}");
        } elseif ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
            // Add to new account
            $account->addTransaction($request->amount, 'credit', "Receita: {$request->title}");
        } elseif ($oldStatus === 'confirmed' && $request->status === 'confirmed') {
            // Update amounts if changed
            if ($oldAmount != $request->amount || $oldAccount->id != $account->id) {
                // Remove old amount from old account
                $oldAccount->addTransaction($oldAmount, 'debit', "Ajuste: {$revenue->title}");
                // Add new amount to new account
                $account->addTransaction($request->amount, 'credit', "Receita: {$request->title}");
            }
        }

        return redirect()->route('revenues.index')->with('success', 'Receita atualizada com sucesso!');
    }

    /**
     * Remove the specified revenue.
     */
    public function destroy(Revenue $revenue)
    {
        $this->authorize('delete', $revenue);

        // Remove from account balance if confirmed
        if ($revenue->status === 'confirmed' && $revenue->account) {
            $revenue->account->addTransaction($revenue->amount, 'debit', "Exclusão: {$revenue->title}");
        }

        // Delete receipt file
        if ($revenue->receipt_url) {
            Storage::disk('public')->delete($revenue->receipt_url);
        }

        $revenue->delete();

        return redirect()->route('revenues.index')->with('success', 'Receita excluída com sucesso!');
    }

    /**
     * Confirm a pending revenue.
     */
    public function confirm(Revenue $revenue)
    {
        $this->authorize('update', $revenue);

        if ($revenue->status !== 'pending') {
            return back()->with('error', 'Apenas receitas pendentes podem ser confirmadas.');
        }

        $revenue->update(['status' => 'confirmed']);

        // Add to account balance
        if ($revenue->account) {
            $revenue->account->addTransaction($revenue->amount, 'credit', "Receita: {$revenue->title}");
        }

        return back()->with('success', 'Receita confirmada com sucesso!');
    }

    /**
     * Cancel a revenue.
     */
    public function cancel(Revenue $revenue)
    {
        $this->authorize('update', $revenue);

        if ($revenue->status === 'cancelled') {
            return back()->with('error', 'Receita já está cancelada.');
        }

        $oldStatus = $revenue->status;
        $revenue->update(['status' => 'cancelled']);

        // Remove from account balance if was confirmed
        if ($oldStatus === 'confirmed' && $revenue->account) {
            $revenue->account->addTransaction($revenue->amount, 'debit', "Cancelamento: {$revenue->title}");
        }

        return back()->with('success', 'Receita cancelada com sucesso!');
    }

    /**
     * Duplicate a revenue.
     */
    public function duplicate(Revenue $revenue)
    {
        $this->authorize('view', $revenue);

        $newRevenue = $revenue->replicate();
        $newRevenue->date = now()->format('Y-m-d');
        $newRevenue->status = 'pending';
        $newRevenue->title = $newRevenue->title . ' (Cópia)';
        $newRevenue->receipt_url = null; // Don't copy file
        $newRevenue->save();

        return redirect()->route('revenues.edit', $newRevenue)->with('success', 'Receita duplicada com sucesso!');
    }

    /**
     * Export revenues to Excel/CSV.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->revenues()->with('account');

        // Apply same filters as index
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($user->isPJ() && $request->filled('business_only')) {
            $query->business();
        }

        $revenues = $query->orderBy('date', 'desc')->get();

        // Generate CSV
        $filename = 'receitas-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function() use ($revenues) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Data', 'Título', 'Categoria', 'Valor', 'Status', 
                'Conta', 'Cliente', 'Método Pagamento', 'Nota Fiscal'
            ]);

            foreach ($revenues as $revenue) {
                fputcsv($file, [
                    $revenue->date->format('d/m/Y'),
                    $revenue->title,
                    $revenue->category,
                    'R$ ' . number_format($revenue->amount, 2, ',', '.'),
                    ucfirst($revenue->status),
                    $revenue->account->name ?? 'N/A',
                    $revenue->client_name ?? 'N/A',
                    $revenue->payment_method ?? 'N/A',
                    $revenue->invoice_number ?? 'N/A',
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Get revenue categories for API.
     */
    public function getCategories()
    {
        $user = Auth::user();
        
        $categories = $user->revenues()
            ->select('category', 'subcategory')
            ->distinct()
            ->get()
            ->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'name' => $category,
                    'subcategories' => $items->pluck('subcategory')->filter()->unique()->values()
                ];
            })
            ->values();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Quick store revenue for dashboard modal
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
                'status' => 'required|in:pending,confirmed',
                'is_recurring' => 'boolean',
                'frequency' => 'nullable|string|in:monthly,weekly,yearly',
                'repeat_until' => 'nullable|date'
            ]);

            // Get user accounts and categories
            $account = Account::where('user_id', auth()->id())->find($validatedData['account_id']);
            $category = \App\Models\Category::where('user_id', auth()->id())->find($validatedData['category_id']);

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

            $revenue = Revenue::create([
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

            // If confirmed, update account balance
            if ($validatedData['status'] === 'confirmed') {
                $account->increment('balance', $validatedData['amount']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Receita criada com sucesso!',
                'revenue' => $revenue
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar receita rápida: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }
}
