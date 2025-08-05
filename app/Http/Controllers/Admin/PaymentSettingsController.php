<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EfiPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PaymentSettingsController extends Controller
{
    /**
     * Exibir configurações de pagamento
     */
    public function index()
    {
        $settings = [
            'efi_client_id' => config('payment.efi.client_id'),
            'efi_client_secret' => config('payment.efi.client_secret') ? str_repeat('*', 20) : '',
            'efi_sandbox' => config('payment.efi.sandbox'),
            'efi_pix_key' => config('payment.efi.pix_key'),
            'efi_certificate_path' => config('payment.efi.certificate_path'),
            'efi_webhook_url' => config('payment.efi.webhook_url', url('/webhook/efi')),
        ];

        return view('admin.payment-settings.index', compact('settings'));
    }

    /**
     * Atualizar configurações de pagamento
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'efi_client_id' => 'required|string',
            'efi_client_secret' => 'nullable|string',
            'efi_sandbox' => 'boolean',
            'efi_pix_key' => 'required|string',
            'efi_webhook_url' => 'required|url',
            'efi_certificate' => 'nullable|file|mimes:p12,pfx|max:2048'
        ]);

        try {
            // Processar upload do certificado
            $certificatePath = config('payment.efi.certificate_path');
            if ($request->hasFile('efi_certificate')) {
                $certificate = $request->file('efi_certificate');
                $certificateName = 'efi_certificate_' . time() . '.' . $certificate->getClientOriginalExtension();
                
                // Salvar na pasta storage/certificates (fora do público)
                $certificatesPath = storage_path('certificates');
                if (!File::exists($certificatesPath)) {
                    File::makeDirectory($certificatesPath, 0755, true);
                }
                
                $certificate->move($certificatesPath, $certificateName);
                $certificatePath = $certificatesPath . '/' . $certificateName;
            }

            // Atualizar arquivo .env
            $this->updateEnvFile([
                'EFI_CLIENT_ID' => $validated['efi_client_id'],
                'EFI_CLIENT_SECRET' => $validated['efi_client_secret'] ?? config('payment.efi.client_secret'),
                'EFI_SANDBOX' => $validated['efi_sandbox'] ? 'true' : 'false',
                'EFI_PIX_KEY' => $validated['efi_pix_key'],
                'EFI_WEBHOOK_URL' => $validated['efi_webhook_url'],
                'EFI_CERTIFICATE_PATH' => $certificatePath
            ]);

            // Limpar cache
            Artisan::call('config:clear');
            Cache::flush();

            return redirect()->back()->with('success', 'Configurações EFI Pay atualizadas com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar configurações EFI Pay', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Testar conexão com a EFI Pay
     */
    public function testConnection(Request $request)
    {
        try {
            $efiService = new EfiPayService();
            
            // Teste simples: tentar obter token de acesso
            $reflection = new \ReflectionClass($efiService);
            $method = $reflection->getMethod('getAccessToken');
            $method->setAccessible(true);
            $token = $method->invoke($efiService);

            if ($token) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conexão com EFI Pay estabelecida com sucesso! Token obtido.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível obter token de acesso da EFI Pay.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Erro no teste de conexão EFI Pay', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro na conexão: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Atualizar arquivo .env
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        foreach ($data as $key => $value) {
            if (empty($value)) continue;
            
            // Escapar aspas no valor
            $value = str_replace('"', '\"', $value);
            
            // Se a chave já existe, substitui
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
            } else {
                // Se não existe, adiciona no final
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        File::put($envPath, $envContent);
    }
}
