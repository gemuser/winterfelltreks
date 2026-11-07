<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 191)->unique();
            $table->string('category', 100);
            $table->text('short_description')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('detailed_description')->nullable();
            $table->json('available_locations')->nullable();
            $table->json('activities')->nullable();
            $table->string('recommended_duration', 100)->nullable();
            $table->text('important_information')->nullable();
            $table->string('hero_image', 500)->nullable();
            $table->json('gallery')->nullable();
            $table->json('related_package_ids')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'category']);
        });

        Schema::create('experience_categories', function (Blueprint $table) {
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
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('experience_categories');
    }
};