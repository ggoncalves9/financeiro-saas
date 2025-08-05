<?php

return [
    'efi' => [
        'client_id' => env('EFI_CLIENT_ID'),
        'client_secret' => env('EFI_CLIENT_SECRET'),
        'certificate_path' => env('EFI_CERTIFICATE_PATH'),
        'sandbox' => env('EFI_SANDBOX', true),
        'pix_key' => env('EFI_PIX_KEY'),
        'webhook_url' => env('EFI_WEBHOOK_URL'),
    ],
    
    'stripe' => [
        'public_key' => env('STRIPE_KEY'),
        'secret_key' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ]
];
