<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Participant;

$names = [
    'Romi Falindo',
    'Farhan Maherajaya',
    'Yudi Wahyudi',
    'Riski Furwanto'
];

foreach ($names as $name) {
    $p = Participant::where('full_name', $name)->first();
    if ($p) {
        echo "Found: '{$p->full_name}', Category ID: {$p->category_id}, Payment Status: '{$p->payment_status}', BIB: '{$p->bib_number}', Email: '{$p->email}'\n";
    } else {
        echo "NOT FOUND: '{$name}'\n";
    }
}
