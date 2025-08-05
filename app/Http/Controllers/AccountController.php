<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allAccounts = \App\Models\Account::where('user_id', auth()->id())->get();
        $accounts = \App\Models\Account::where('user_id', auth()->id())->paginate(10);
        $total_accounts = $allAccounts->count();
        $total_balance = $allAccounts->sum('balance');
        $checking_accounts = $allAccounts->where('type', 'checking')->count();
        $savings_accounts = $allAccounts->where('type', 'savings')->count();
        $stats = [
            'total_accounts' => $total_accounts,
            'total_balance_formatted' => number_format($total_balance, 2, ',', '.'),
            'checking_accounts' => $checking_accounts,
            'savings_accounts' => $savings_accounts
        ];
        return view('accounts.index', compact('accounts', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Implementar lógica de criação de conta
        return redirect()->route('accounts.index')->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('accounts.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('accounts.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implementar lógica de atualização de conta
        return redirect()->route('accounts.index')->with('success', 'Conta atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implementar lógica de exclusão de conta
        return redirect()->route('accounts.index')->with('success', 'Conta excluída com sucesso!');
    }
}
