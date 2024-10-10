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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('surname');
            $table->string('other_name')->nullable();
            $table->string('phone_number');
            $table->string('hcp_code');
            $table->string('hcp_name');
            $table->string('principal_id')->nullable();
            $table->string('beneficiary_id');
            $table->string('entity_type');
            $table->string('gender');
            $table->string('date_of_birth')->nullable();
            $table->string('nin')->nullable();
            $table->string('psn')->nullable();
            $table->foreignId('ward_id')->constrained();
            $table->foreignId('household_id')->nullable()->constrained();
            $table->foreignId('community_id')->nullable()->constrained();
            $table->foreignId('health_facility_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
