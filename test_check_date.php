<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Participant;
use App\Models\Event;

$event = Event::latest()->first();
echo "Latest Event ID: " . ($event ? $event->id : 'None') . " (Name: " . ($event ? $event->name : 'None') . ")\n";

$p = Participant::where('full_name', 'like', '%Siti Hafsah%')->first();
if ($p) {
    echo "Siti Hafsah: ID={$p->id}, Event ID={$p->event_id}, Category ID={$p->category_id}, Payment Status='{$p->payment_status}', BIB='{$p->bib_number}'\n";
} else {
    echo "Siti Hafsah NOT FOUND\n";
}

$events = Event::all();
foreach ($events as $ev) {
    echo "Event: ID={$ev->id}, Name='{$ev->name}'\n";
}
