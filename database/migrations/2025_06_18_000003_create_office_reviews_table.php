<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('real_estate_office_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            // Ensure a reviewer can only review an office once
            $table->unique(['real_estate_office_id', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_reviews');
    }
}; 