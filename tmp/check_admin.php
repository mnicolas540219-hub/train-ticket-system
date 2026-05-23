<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admins = App\Models\User::where('role', 'admin')->get();
foreach ($admins as $admin) {
    echo "id={$admin->id} email={$admin->email} username={$admin->username} password_hash={$admin->password}\n";
}
