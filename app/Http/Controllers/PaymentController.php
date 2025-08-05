<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\EfiPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $efiPayService;

    public function __construct(EfiPayService $efiPayService)
    {
        $this->efiPayService = $efiPayService;
    }

    /**
     * Processar pagamento via PIX
     */
    public function processPix(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|in:pix'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $plan = Plan::findOrFail($request->plan_id);

            // Criar registro de pagamento
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'transaction_id' => 'TXN_' . uniqid() . time(),
                'amount' => $plan->price,
                'payment_method' => 'pix',
                'description' => "Assinatura do plano {$plan->name}",
                'status' => 'pending'
            ]);

            // Gerar cobrança PIX na EFI
            $pixData = $this->efiPayService->createPixCharge(
                $plan->price,
                "Assinatura {$plan->name} - {$user->name}",
                $payment->transaction_id
            );

            // Atualizar pagamento com dados do PIX
            $payment->update([
                'txid' => $pixData['txid'] ?? null,
                'qr_code' => $pixData['qr_code'] ?? null,
                'qr_code_image' => $pixData['qr_code_image'] ?? null,
                'expires_at' => now()->addHour(),
                'gateway_response' => $pixData
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'qr_code' => $pixData['qr_code'] ?? null,
                'qr_code_image' => $pixData['qr_code_image'] ?? null,
                'txid' => $pixData['txid'] ?? null,
                'expires_at' => $payment->expires_at->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar pagamento PIX', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'plan_id' => $request->plan_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar status do pagamento
     */
    public function checkStatus($paymentId)
    {
        try {
            $payment = Payment::where('id', $paymentId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Se já foi pago, retorna sucesso
            if ($payment->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'payment' => $payment
                ]);
            }

            // Verificar na EFI se houve pagamento
            if ($payment->txid) {
                $status = $this->efiPayService->checkPixStatus($payment->txid);
                
                if ($status['status'] === 'CONCLUIDA') {
                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'gateway_response' => array_merge(
                            $payment->gateway_response ?? [],
                            ['webhook_data' => $status]
                        )
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => 'paid',
                        'payment' => $payment->fresh()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do pagamento', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar pagamento'
            ], 500);
        }
    }

    /**
     * Listar pagamentos do usuário
     */
    public function index()
    {
        $payments = Payment::with(['plan'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Detalhes do pagamento
     */
    public function show($id)
    {
        $payment = Payment::with(['plan', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('payments.show', compact('payment'));
    }
}
