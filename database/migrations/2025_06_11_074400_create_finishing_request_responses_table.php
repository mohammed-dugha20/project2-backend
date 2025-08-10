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
        Schema::create('finishing_request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finishing_request_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['accepted', 'rejected']);
            $table->text('reason')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->string('implementation_period')->nullable();
            $table->text('materials')->nullable();
            $table->text('work_phases')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finishing_request_responses');
    }
}; 