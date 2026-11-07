<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspiring;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('winterfell:seed', function () {
    $this->call('db:seed');
    $this->info('Winterfell database seeded successfully!');
})->purpose('Seed the Winterfell database');

Artisan::command('winterfell:refresh', function () {
    $this->call('migrate:fresh', ['--seed' => true]);
    $this->info('Winterfell database refreshed and seeded!');
})->purpose('Refresh and seed the Winterfell database');