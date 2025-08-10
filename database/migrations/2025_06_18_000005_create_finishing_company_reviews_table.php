<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finishing_company_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finishing_company_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->unique(['finishing_company_id', 'reviewer_id'], 'fc_reviews_company_reviewer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finishing_company_reviews');
    }
}; 