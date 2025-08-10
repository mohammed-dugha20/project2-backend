<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->enum('type', ['apartment', 'villa', 'land', 'office', 'commercial']);
            $table->float('price');
            $table->float('area');
            $table->integer('rooms');
            $table->enum('legal_status', ['registered', 'pending', 'customary']);
            $table->enum('offer_type', ['sale', 'rent']);
            $table->foreignId('status_id')->constrained('statuses')->cascadeOnDelete();
            $table->boolean('contact_visible');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('real_estate_office_id')->constrained('real_estate_offices')->cascadeOnDelete()->nullable();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
