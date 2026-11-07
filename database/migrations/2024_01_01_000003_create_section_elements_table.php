<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('section_elements')) {
            Schema::create('section_elements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained('page_sections')->cascadeOnDelete();
                $table->string('element_key', 100);
                $table->enum('element_type', ['text', 'richtext', 'image', 'link', 'repeater', 'json'])->default('text');
                $table->longText('content')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->index(['section_id', 'element_key']);
                $table->index(['section_id', 'sort_order']);
            });

            return;
        }

        $needsForeignKey = ! Schema::hasForeignKey('section_elements', 'section_elements_section_id_foreign');
        $needsElementKeyIndex = ! Schema::hasIndex('section_elements', 'section_elements_section_id_element_key_index');
        $needsSortOrderIndex = ! Schema::hasIndex('section_elements', 'section_elements_section_id_sort_order_index');

        if ($needsForeignKey || $needsElementKeyIndex || $needsSortOrderIndex) {
            Schema::table('section_elements', function (Blueprint $table) use ($needsForeignKey, $needsElementKeyIndex, $needsSortOrderIndex) {
                if ($needsForeignKey) {
                    $table->foreign('section_id', 'section_elements_section_id_foreign')
                        ->references('id')->on('page_sections')->cascadeOnDelete();
                }

                if ($needsElementKeyIndex) {
                    $table->index(['section_id', 'element_key'], 'section_elements_section_id_element_key_index');
                }

                if ($needsSortOrderIndex) {
                    $table->index(['section_id', 'sort_order'], 'section_elements_section_id_sort_order_index');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('section_elements');
    }
};
