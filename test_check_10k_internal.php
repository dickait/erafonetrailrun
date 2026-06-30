<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Participant;

$filePath = __DIR__ . '/public/bib/BIB 10K ERATRAILRUN.csv';
$handle = fopen($filePath, 'r');
$header = fgetcsv($handle, 1000, ',');
$header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
$header = array_map('trim', $header);

$internalRows = [];
while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    if (count($header) === count($data)) {
        $row = array_combine($header, $data);
        if (str_contains(strtolower($row['category_id']), 'internal')) {
            $internalRows[] = $row;
        }
    }
}
fclose($handle);

echo "Total 10K Internal rows in CSV: " . count($internalRows) . "\n";

$notFoundCount = 0;
foreach ($internalRows as $row) {
    $rawName = trim($row['full_name']);
    if (empty($rawName) || strtolower($rawName) === 'bod') continue;
    
    $fullName = ucwords(strtolower($rawName));
    $p = Participant::where('full_name', $fullName)->first();
    if ($p) {
        echo "Found: '{$fullName}', Payment Status: '{$p->payment_status}', BIB: '{$p->bib_number}'\n";
    } else {
        echo "NOT FOUND: '{$fullName}'\n";
        $notFoundCount++;
    }
}
echo "Total Not Found: {$notFoundCount}\n";
