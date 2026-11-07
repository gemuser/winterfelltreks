<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->string('group', 100)->default('general');
            $table->enum('type', ['text', 'textarea', 'image', 'json', 'toggle', 'number'])->default('text');
            $table->longText('value')->nullable();
            $table->timestamps();
            
            $table->index(['group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};