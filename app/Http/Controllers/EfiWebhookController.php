<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Subscription;

class EfiWebhookController extends Controller
{
    /**
     * Handle EFI Pay webhook notifications
     */
    public function handle(Request $request)
    {
        Log::info('EFI Webhook received', $request->all());

        $event = $request->input('evento');
        $data = $request->input('data');

        try {
            switch ($event) {
                case 'pix':
                    $this->handlePixPayment($data);
                    break;
                
                case 'charge':
                    $this->handleChargePayment($data);
                    break;
                
                case 'carnet':
                    $this->handleCarnetPayment($data);
                    break;
                
                default:
                    Log::warning('Unknown EFI webhook event', ['event' => $event]);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('EFI webhook processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle PIX payment notifications
     */
    private function handlePixPayment($data)
    {
        if (!isset($data['pix']) || !is_array($data['pix'])) {
            return;
        }

        foreach ($data['pix'] as $pix) {
            $txid = $pix['txid'] ?? null;
            $valor = $pix['valor'] ?? null;
            $status = $pix['status'] ?? null;

            if (!$txid || !$valor) {
                continue;
            }

            Log::info('Processing PIX payment', [
                'txid' => $txid,
                'valor' => $valor,
                'status' => $status
            ]);

            // Aqui você implementaria a lógica para:
            // 1. Encontrar a subscription/user baseado no txid
            // 2. Atualizar o status da assinatura
            // 3. Ativar/renovar a assinatura do usuário

            $this->processPixPayment($txid, $valor, $status);
        }
    }

    /**
     * Handle charge payment notifications
     */
    private function handleChargePayment($data)
    {
        $chargeId = $data['id'] ?? null;
        $status = $data['status'] ?? null;
        $value = $data['value'] ?? null;

        if (!$chargeId) {
            return;
        }

        Log::info('Processing charge payment', [
            'charge_id' => $chargeId,
            'status' => $status,
            'value' => $value
        ]);

        // Implementar lógica de processamento de cobrança
        $this->processChargePayment($chargeId, $status, $value);
    }

    /**
     * Handle carnet payment notifications
     */
    private function handleCarnetPayment($data)
    {
        $carnetId = $data['carnet_id'] ?? null;
        $parcelId = $data['parcel_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$carnetId || !$parcelId) {
            return;
        }

        Log::info('Processing carnet payment', [
            'carnet_id' => $carnetId,
            'parcel_id' => $parcelId,
            'status' => $status
        ]);

        // Implementar lógica de processamento de carnê
        $this->processCarnetPayment($carnetId, $parcelId, $status);
    }

    /**
     * Process PIX payment
     */
    private function processPixPayment($txid, $valor, $status)
    {
        // Encontrar subscription baseada no txid (você deve salvar isso ao criar a cobrança)
        // $subscription = Subscription::where('efi_txid', $txid)->first();
        
        if ($status === 'CONCLUIDA') {
            // Pagamento aprovado
            // $subscription->markAsPaid();
            // $subscription->user->activateSubscription();
            
            Log::info('PIX payment approved', ['txid' => $txid, 'valor' => $valor]);
        }
    }

    /**
     * Process charge payment
     */
    private function processChargePayment($chargeId, $status, $value)
    {
        // Implementar processamento de cobrança
        if ($status === 'paid') {
            // Pagamento aprovado
            Log::info('Charge payment approved', ['charge_id' => $chargeId, 'value' => $value]);
        } elseif ($status === 'canceled') {
            // Pagamento cancelado
            Log::info('Charge payment canceled', ['charge_id' => $chargeId]);
        }
    }

    /**
     * Process carnet payment
     */
    private function processCarnetPayment($carnetId, $parcelId, $status)
    {
        // Implementar processamento de carnê
        if ($status === 'paid') {
            // Parcela paga
            Log::info('Carnet parcel paid', ['carnet_id' => $carnetId, 'parcel_id' => $parcelId]);
        }
    }

    /**
     * Test webhook endpoint
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'EFI webhook endpoint is working',
            'timestamp' => now()->toISOString()
        ]);
    }
}
