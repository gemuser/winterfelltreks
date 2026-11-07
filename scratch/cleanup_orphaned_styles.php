<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sections = App\Models\PageSection::with('elements')->get();
$cleanedCount = 0;

foreach ($sections as $section) {
    $settings = is_array($section->settings) ? $section->settings : [];
    if (!empty($settings['styles']) && is_array($settings['styles'])) {
        $existingKeys = $section->elements->pluck('element_key')->toArray();
        $changed = false;
        foreach (array_keys($settings['styles']) as $styleKey) {
            if (str_starts_with($styleKey, 'custom_') && !in_array($styleKey, $existingKeys)) {
                echo "Removing orphaned style key '{$styleKey}' from section {$section->id} ({$section->section_type})\n";
                unset($settings['styles'][$styleKey]);
                $changed = true;
                $cleanedCount++;
            }
        }
        if ($changed) {
            $section->settings = $settings;
            $section->save();
        }
    }
}

echo "Cleanup complete. Cleaned {$cleanedCount} orphaned style keys.\n";

