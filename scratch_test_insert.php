<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\DB::statement("ALTER TABLE section_elements MODIFY element_type VARCHAR(50) NOT NULL DEFAULT 'text'");
    echo "ALTER TABLE SUCCESS!\n";

    $sec = App\Models\PageSection::find(43);
    $el = $sec->elements()->create([
        'element_key' => 'test_comp_' . time(),
        'element_type' => 'component',
        'content' => ['type' => 'badge_certified', 'text' => 'Test'],
        'sort_order' => 999,
    ]);
    echo "SUCCESS: Created component element ID " . $el->id . "\n";
    $el->delete();
    echo "SUCCESS: Deleted test element.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
