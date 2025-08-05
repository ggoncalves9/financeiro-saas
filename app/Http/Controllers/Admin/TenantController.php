<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants.
     */
    public function index()
    {
        // Por enquanto retornar view simples
        return view('admin.tenants.index', [
            'tenants' => collect([])
        ]);
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant criado com sucesso!');
    }

    /**
     * Display the specified tenant.
     */
    public function show($tenant)
    {
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit($tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(Request $request, $tenant)
    {
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant atualizado com sucesso!');
    }

    /**
     * Remove the specified tenant from storage.
     */
    public function destroy($tenant)
    {
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deletado com sucesso!');
    }

    /**
     * Activate a tenant.
     */
    public function activate($tenant)
    {
        return back()->with('success', 'Tenant ativado com sucesso!');
    }

    /**
     * Deactivate a tenant.
     */
    public function deactivate($tenant)
    {
        return back()->with('success', 'Tenant desativado com sucesso!');
    }

    /**
     * Extend trial for a tenant.
     */
    public function extendTrial($tenant)
    {
        return back()->with('success', 'Trial estendido com sucesso!');
    }

    /**
     * Change plan for a tenant.
     */
    public function changePlan(Request $request, $tenant)
    {
        return back()->with('success', 'Plano alterado com sucesso!');
    }
}
