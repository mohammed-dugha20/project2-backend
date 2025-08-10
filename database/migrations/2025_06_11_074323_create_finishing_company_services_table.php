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
        Schema::create('finishing_company_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finishing_company_id')->constrained('finishing_companies')->cascadeOnDelete();
            $table->string('service_type');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finishing_company_services');
    }
};
