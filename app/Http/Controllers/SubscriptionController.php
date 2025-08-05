<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        return view('subscription.show', compact('user'));
    }

    public function upgrade(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:premium,enterprise'
        ]);

        // Lógica do Stripe para upgrade seria implementada aqui
        
        return redirect()->back()
                        ->with('success', 'Plano atualizado com sucesso!');
    }

    public function cancel()
    {
        // Lógica para cancelar assinatura
        
        return redirect()->back()
                        ->with('success', 'Assinatura cancelada!');
    }

    public function resume()
    {
        // Lógica para reativar assinatura
        
        return redirect()->back()
                        ->with('success', 'Assinatura reativada!');
    }
}
