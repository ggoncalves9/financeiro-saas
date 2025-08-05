<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EfiPayService;

class TestEfiIntegration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'efi:test {--pix : Test PIX charge creation}';

    /**
     * The console command description.
     */
    protected $description = 'Test EFI Pay integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing EFI Pay integration...');

        try {
            $efiService = new EfiPayService();

            // Test connection
            $this->info('Testing connection...');
            $result = $efiService->testConnection();
            
            if ($result['success']) {
                $this->info('✓ ' . $result['message']);
                if (isset($result['token_preview'])) {
                    $this->line('Token preview: ' . $result['token_preview']);
                }
            } else {
                $this->error('✗ ' . $result['message']);
                return 1;
            }

            // Test PIX charge if requested
            if ($this->option('pix')) {
                $this->info('Testing PIX charge creation...');
                
                $txid = 'TEST_' . uniqid() . '_' . time();
                $amount = 10.00;
                $description = 'Teste de integração PIX';

                $charge = $efiService->createPixCharge($amount, $description, $txid);
                
                $this->info('✓ PIX charge created successfully');
                $this->line('TXID: ' . $charge['txid']);
                $this->line('Location: ' . ($charge['location'] ?? 'N/A'));
                
                if (isset($charge['pixCopiaECola'])) {
                    $this->line('PIX Code: ' . $charge['pixCopiaECola']);
                }
            }

            $this->info('All tests completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Test failed: ' . $e->getMessage());
            return 1;
        }
    }
}
