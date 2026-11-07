<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sections = App\Models\PageSection::where('page_id', 3)->get();
foreach ($sections as $s) {
    echo "=== Section ID: {$s->id} | Type: {$s->section_type} ===\n";
    echo "Settings:\n" . json_encode($s->settings, JSON_PRETTY_PRINT) . "\n";
    echo "Elements:\n" . json_encode($s->elements, JSON_PRETTY_PRINT) . "\n";
}
