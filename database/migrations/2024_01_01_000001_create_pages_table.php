<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 191)->unique();
            $table->string('layout_type', 100)->default('standard');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('is_published')->default(false);
            
            $table->boolean('show_in_navbar')->default(false);
            $table->string('navbar_title', 100)->nullable();
            $table->integer('navbar_order')->default(0);
            
            $table->boolean('show_in_sidebar')->default(false);
            $table->string('sidebar_title', 100)->nullable();
            $table->integer('sidebar_order')->default(0);
            
            $table->boolean('show_in_footer')->default(false);
            $table->integer('footer_column')->default(1);
            $table->integer('footer_order')->default(0);
            
            $table->timestamps();
            
            $table->index(['is_published', 'slug']);
            $table->index(['show_in_navbar', 'navbar_order']);
            $table->index(['show_in_sidebar', 'sidebar_order']);
            $table->index(['show_in_footer', 'footer_column', 'footer_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};