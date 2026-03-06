<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$count = \App\Models\Province::count();
echo "Total Provinces: " . $count . "\n";
$citiesCount = \App\Models\City::count();
echo "Total Cities: " . $citiesCount . "\n";
