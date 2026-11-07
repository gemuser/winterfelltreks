<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$page = App\Models\Page::with(['sections.elements'])->find(3);

try {
    echo "Rendering frontend for Page 3...\n";
    $publicController = new \App\Http\Controllers\PageController();
    $reflection = new \ReflectionClass($publicController);
    $method = $reflection->getMethod('getPageData');
    $method->setAccessible(true);
    $pageData = $method->invoke($publicController, $page);

    $html = view('pages.render', [
        'page' => $page,
        'pageData' => $pageData,
    ])->render();
    echo "Frontend rendered successfully! Length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "Frontend Render Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

try {
    echo "Rendering admin edit view for Page 3...\n";
    $adminController = new \App\Http\Controllers\Admin\PageController();
    $response = $adminController->edit($page);
    $html = $response->render();
    echo "Admin edit rendered successfully! Length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "Admin Edit Render Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
