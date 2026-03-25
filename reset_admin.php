<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the application to use Eloquent and Facades
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'user@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('password');
    $user->save();
    echo "Password for user 'admin' has been reset to 'password'.\n";
} else {
    echo "User 'admin' not found.\n";
}
