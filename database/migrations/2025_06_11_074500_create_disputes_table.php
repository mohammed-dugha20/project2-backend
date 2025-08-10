<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('finishing_companies')->onDelete('cascade');
            $table->enum('company_type', ['finishing_company', 'real_estate_office']);
            $table->foreignId('finishing_request_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['pending', 'investigating', 'resolved', 'closed'])->default('pending');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
}; 