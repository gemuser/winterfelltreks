<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('section_type', 100);
            $table->string('custom_anchor_id', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index(['page_id', 'sort_order']);
            $table->index(['page_id', 'is_visible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};