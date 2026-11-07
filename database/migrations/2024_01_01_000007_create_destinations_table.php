<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 191)->unique();
            $table->string('country', 100);
            $table->string('region', 100)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('featured_image', 500)->nullable();
            $table->json('gallery')->nullable();
            $table->text('best_time_to_visit')->nullable();
            $table->longText('travel_information')->nullable();
            $table->string('category', 100)->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'country']);
            $table->index(['is_published', 'category']);
        });

        Schema::create('destination_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
        Schema::dropIfExists('destination_categories');
    }
};