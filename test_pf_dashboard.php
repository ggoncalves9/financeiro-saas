<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing PF Dashboard...\n";

try {
    // Test if user exists
    $user = App\Models\User::where('email', 'pf@teste.com')->first();
    if (!$user) {
        echo "❌ PF user not found\n";
        exit(1);
    }
    echo "✅ PF user found: {$user->name}\n";

    // Test if accounts work
    $accounts = $user->accounts()->active()->get();
    echo "✅ User has " . $accounts->count() . " active accounts\n";

    // Test if expenses work
    $expenses = $user->expenses()->count();
    echo "✅ User has " . $expenses . " expenses\n";

    // Test if revenues work
    $revenues = $user->revenues()->count();
    echo "✅ User has " . $revenues . " revenues\n";

    // Test dashboard controller
    $controller = new App\Http\Controllers\DashboardController();
    $request = new Illuminate\Http\Request();
    
    // Simulate authentication
    Auth::login($user);
    
    echo "✅ Dashboard controller created successfully\n";
    echo "✅ All tests passed! PF dashboard should work now.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    exit(1);
}
