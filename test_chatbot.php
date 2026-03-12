<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulasi request
$request = new Illuminate\Http\Request();
$request->merge(['message' => 'halo']); // Ganti dengan pesan test

$controller = new App\Http\Controllers\DashboardController();
$response = $controller->chatbotMessage($request);

echo "Response: " . $response->getContent() . "\n";
?>