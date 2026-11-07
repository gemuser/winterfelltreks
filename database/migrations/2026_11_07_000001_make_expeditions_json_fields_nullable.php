<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->json('itinerary')->nullable()->change();
            $table->json('gear_checklist')->nullable()->change();
            $table->json('included_services')->nullable()->change();
            $table->json('excluded_services')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->json('itinerary')->nullable(false)->change();
            $table->json('gear_checklist')->nullable(false)->change();
            $table->json('included_services')->nullable(false)->change();
            $table->json('excluded_services')->nullable(false)->change();
        });
    }
};

