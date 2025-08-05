<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Display general settings.
     */
    public function general()
    {
        return view('admin.settings.general');
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        return back()->with('success', 'Configurações gerais atualizadas!');
    }

    /**
     * Display email settings.
     */
    public function email()
    {
        return view('admin.settings.email');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        return back()->with('success', 'Configurações de email atualizadas!');
    }

    /**
     * Display Stripe settings.
     */
    public function stripe()
    {
        return view('admin.settings.stripe');
    }

    /**
     * Update Stripe settings.
     */
    public function updateStripe(Request $request)
    {
        return back()->with('success', 'Configurações do Stripe atualizadas!');
    }

    /**
     * Display features settings.
     */
    public function features()
    {
        return view('admin.settings.features');
    }

    /**
     * Update features settings.
     */
    public function updateFeatures(Request $request)
    {
        return back()->with('success', 'Configurações de funcionalidades atualizadas!');
    }

    /**
     * Display maintenance settings.
     */
    public function maintenance()
    {
        return view('admin.settings.maintenance');
    }

    /**
     * Update maintenance settings.
     */
    public function updateMaintenance(Request $request)
    {
        return back()->with('success', 'Configurações de manutenção atualizadas!');
    }
}
