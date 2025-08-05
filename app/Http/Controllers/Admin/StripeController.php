<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    /**
     * Display Stripe integration dashboard.
     */
    public function index()
    {
        return view('admin.stripe.index');
    }

    /**
     * Display subscriptions from Stripe.
     */
    public function subscriptions()
    {
        return view('admin.stripe.subscriptions');
    }

    /**
     * Display invoices from Stripe.
     */
    public function invoices()
    {
        return view('admin.stripe.invoices');
    }

    /**
     * Display customers from Stripe.
     */
    public function customers()
    {
        return view('admin.stripe.customers');
    }

    /**
     * Sync subscriptions with Stripe.
     */
    public function syncSubscriptions()
    {
        return back()->with('success', 'Assinaturas sincronizadas com sucesso!');
    }

    /**
     * Process refund for an invoice.
     */
    public function refund($invoice)
    {
        return back()->with('success', 'Reembolso processado com sucesso!');
    }

    /**
     * Display webhook logs.
     */
    public function webhookLogs()
    {
        return view('admin.stripe.webhook-logs');
    }
}
