<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Página de checkout para um plano específico
     */
    public function show($planId)
    {
        $plan = Plan::where('is_active', true)->findOrFail($planId);
        $user = Auth::user();

        // Verificar se o usuário já tem uma assinatura ativa
        $hasActiveSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->exists();

        return view('checkout.show', compact('plan', 'user', 'hasActiveSubscription'));
    }

    /**
     * Processar checkout
     */
    public function process(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|in:pix,credit_card',
            'cpf_cnpj' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string'
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Atualizar dados do usuário se necessário
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cpf' => $request->cpf_cnpj
        ]);

        // Redirecionar para processamento do pagamento
        return redirect()->route('payment.process', [
            'plan_id' => $plan->id,
            'payment_method' => $request->payment_method
        ]);
    }

    /**
     * Página de sucesso
     */
    public function success($paymentId)
    {
        $payment = \App\Models\Payment::where('user_id', Auth::id())
            ->findOrFail($paymentId);

        return view('checkout.success', compact('payment'));
    }

    /**
     * Página de erro
     */
    public function error($paymentId = null)
    {
        $payment = null;
        if ($paymentId) {
            $payment = \App\Models\Payment::where('user_id', Auth::id())
                ->find($paymentId);
        }

        return view('checkout.error', compact('payment'));
    }

    /**
     * Página de aguardando pagamento (PIX)
     */
    public function pending($paymentId)
    {
        $payment = \App\Models\Payment::with('plan')
            ->where('user_id', Auth::id())
            ->findOrFail($paymentId);

        return view('checkout.pending', compact('payment'));
    }
}
