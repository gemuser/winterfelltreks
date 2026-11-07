<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 20)->unique();
            $table->string('enquiry_type', 50); // general, destination, package, experience
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('expeditions')->nullOnDelete();
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            
            $table->string('travel_type')->nullable();
            $table->date('expected_departure_date')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->integer('number_of_travellers')->default(1);
            $table->integer('number_of_adults')->nullable();
            $table->integer('number_of_children')->nullable();
            
            $table->string('accommodation_preference')->nullable();
            $table->string('budget_range')->nullable();
            $table->json('preferred_activities')->nullable();
            $table->string('travel_flexibility')->nullable();
            
            $table->text('additional_message')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('dietary_requirements')->nullable();
            $table->text('accessibility_requirements')->nullable();
            
            $table->boolean('consent')->default(false);
            
            $table->enum('status', ['new', 'under_review', 'contacted', 'quotation_sent', 'follow_up_required', 'closed'])->default('new');
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_notes')->nullable();
            
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['status', 'submitted_at']);
            $table->index(['enquiry_type', 'submitted_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};