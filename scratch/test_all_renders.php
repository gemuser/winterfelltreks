<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pages = App\Models\Page::with(['sections.elements'])->get();
$publicController = new \App\Http\Controllers\PageController();
$reflection = new \ReflectionClass($publicController);
$method = $reflection->getMethod('getPageData');
$method->setAccessible(true);
$adminController = new \App\Http\Controllers\Admin\PageController();

$errors = 0;
foreach ($pages as $page) {
    try {
        $pageData = $method->invoke($publicController, $page);
        $frontHtml = view('pages.render', compact('page', 'pageData'))->render();
        $adminView = $adminController->edit($page)->render();
        echo "✓ Page ID {$page->id} ({$page->slug}) rendered OK (Front: " . strlen($frontHtml) . "b, Admin: " . strlen($adminView) . "b)\n";
    } catch (\Throwable $e) {
        echo "✗ Page ID {$page->id} ({$page->slug}) ERROR: " . $e->getMessage() . "\n";
        $errors++;
    }
}

if ($errors === 0) {
    echo "\nAll " . count($pages) . " pages rendered cleanly without any errors!\n";
} else {
    echo "\nFinished with {$errors} errors.\n";
    exit(1);
}

