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
        Schema::create('real_estate_offices', function (Blueprint $table) {
            $table->id();
            $table->string('commercial_name');
            $table->string('address');
            $table->string('license_number');
            $table->string('profile_description');
            $table->string('phone_number');
            $table->boolean('is_active');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_offices');
    }
};
