<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$k = $app->make(Illuminate\Contracts\Console\Kernel::class);
$k->bootstrap();

App\Models\Page::where('show_in_navbar', false)->update(['navbar_order' => 0]);
echo "Updated non-navbar pages to navbar_order = 0\n";
