<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EfiPayService
{
    private $clientId;
    private $clientSecret;
    private $certificatePath;
    private $sandbox;
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = config('payment.efi.client_id');
        $this->clientSecret = config('payment.efi.client_secret');
        $this->certificatePath = config('payment.efi.certificate_path');
        $this->sandbox = config('payment.efi.sandbox', true);
        
        $this->baseUrl = $this->sandbox 
            ? 'https://sandbox.gerencianet.com.br' 
            : 'https://api.gerencianet.com.br';
    }

    /**
     * Get access token from EFI
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->asForm()->post($this->baseUrl . '/v1/authorize', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                return $this->accessToken;
            }

            throw new \Exception('Failed to get access token: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay authentication failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create PIX charge
     */
    public function createPixCharge($amount, $description, $txid = null)
    {
        $token = $this->getAccessToken();
        
        if (!$txid) {
            $txid = 'TXN' . uniqid() . time();
        }

        $data = [
            'calendario' => [
                'expiracao' => 3600 // 1 hora
            ],
            'devedor' => [
                'nome' => 'Cliente',
                'cpf' => '00000000000'
            ],
            'valor' => [
                'original' => number_format($amount, 2, '.', '')
            ],
            'chave' => config('payment.efi.pix_key'),
            'solicitacaoPagador' => $description
        ];

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put($this->baseUrl . "/v2/cob/{$txid}", $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create PIX charge: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay PIX charge creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get PIX charge details
     */
    public function getPixCharge($txid)
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->baseUrl . "/v2/cob/{$txid}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get PIX charge: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay PIX charge retrieval failed', [
                'error' => $e->getMessage(),
                'txid' => $txid
            ]);
            throw $e;
        }
    }

    /**
     * Create traditional charge (boleto/card)
     */
    public function createCharge($items, $customer = null)
    {
        $token = $this->getAccessToken();

        $data = [
            'items' => $items
        ];

        if ($customer) {
            $data['customer'] = $customer;
        }

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/v1/charge', $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create charge: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay charge creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get charge details
     */
    public function getCharge($chargeId)
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->baseUrl . "/v1/charge/{$chargeId}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get charge: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay charge retrieval failed', [
                'error' => $e->getMessage(),
                'charge_id' => $chargeId
            ]);
            throw $e;
        }
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            $token = $this->getAccessToken();
            return [
                'success' => true,
                'message' => 'Conexão com EFI Pay estabelecida com sucesso',
                'token_preview' => substr($token, 0, 20) . '...'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create subscription plan
     */
    public function createPlan($name, $amount, $interval)
    {
        $token = $this->getAccessToken();

        $data = [
            'name' => $name,
            'amount' => (int)($amount * 100), // Convert to cents
            'currency' => 'BRL',
            'interval' => $interval
        ];

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/v1/plan', $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create plan: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay plan creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Check PIX payment status
     */
    public function checkPixStatus($txid)
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withOptions([
                'cert' => $this->certificatePath,
                'ssl_key' => $this->certificatePath,
                'verify' => false
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . "/v2/cob/{$txid}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to check PIX status: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('EFI Pay PIX status check failed', [
                'error' => $e->getMessage(),
                'txid' => $txid
            ]);
            throw $e;
        }
    }
}
