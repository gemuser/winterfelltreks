<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 191)->unique();
            $table->string('region', 100);
            $table->string('max_elevation', 50);
            $table->integer('duration_days');
            $table->string('technical_grade', 50);
            $table->longText('route_overview')->nullable();
            $table->json('itinerary');
            $table->json('gear_checklist');
            $table->json('included_services');
            $table->json('excluded_services');
            $table->string('featured_image', 500)->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            
            $table->index(['is_published', 'region']);
            $table->index(['is_published', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};