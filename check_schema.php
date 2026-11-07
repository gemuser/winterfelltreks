<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$columns = Schema::getColumnListing('section_elements');
echo "Columns in section_elements: " . implode(', ', $columns) . "\n";

$first = DB::table('section_elements')->first();
echo "First row: " . json_encode($first) . "\n";

$sec17Elements = DB::table('section_elements')->where('page_section_id', 17)->get();
echo "Elements with page_section_id = 17: " . $sec17Elements->count() . "\n";

unlink(__FILE__);

