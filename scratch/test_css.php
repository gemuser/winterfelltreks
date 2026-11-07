<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = App\Models\Page::with('sections.elements')->find(3);

foreach ($page->sections as $sec) {
    if (!empty($sec->settings['styles']) && is_array($sec->settings['styles'])) {
        foreach ($sec->settings['styles'] as $targetKey => $rules) {
            $cleanRules = [];
            foreach($rules as $p => $v) {
                $k = Illuminate\Support\Str::kebab($p);
                if (!in_array($k, ['top', 'left', 'position', 'transform', 'z-index'])) {
                    $cleanRules[$k] = html_entity_decode($v, ENT_QUOTES, 'UTF-8');
                }
            }
            echo "--- Sec: {$sec->id} | Key: {$targetKey} ---\n";
            if (str_starts_with($targetKey, 'custom_')) {
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"],\n";
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"] > .canva-photo-box,\n";
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"] > .canva-photo-box img,\n";
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"] > .canva-custom-text,\n";
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"] > .canva-custom-button,\n";
                echo "#section-view-{$sec->id} .canva-draggable-item[data-key=\"{$targetKey}\"] > .canva-component-box {\n";
                foreach ($cleanRules as $p => $v) {
                    echo "  $p: $v !important;\n";
                }
                echo "}\n";
            }
        }
    }
}
