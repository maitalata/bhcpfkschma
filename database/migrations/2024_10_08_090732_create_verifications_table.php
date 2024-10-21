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
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('beneficiary_id')->constrained();
            $table->foreignId('g_r_o_id')->constrained();
            $table->boolean(('are_you_able_to_sight_the_beneficiary'))->nullable()->default(false);
            $table->enum('reason_for_not_sighting_beneficiary', ['Dead', 'Relocated Permanently', 'Cannot be located'])->nullable();
            $table->string('new_location_of_beneficiary')->nullable();
            $table->boolean(('did_the_beneficiary_has_nin'))->default(false);
            $table->boolean(('is_the_distance_to_assigned_phc_trekkable'))->default(false);
            $table->enum('transport_fare_to_facility', ['200 - 500', '501 - 1000', '1001 - 1500', '1501 - 2000', 'Over 2001'])->nullable();
            $table->boolean(('does_the_beneficiary_want_to_change_their_assigned_facility'))->default(false);
            $table->string(('what_is_the_primary_reason_for_the_change_of_facility'))->nullable();
            $table->boolean(('beneficiary_ever_accessed_healthcare_services_in_assigned_phc'))->default(false);
            $table->boolean(('did_you_educate_the_beneficiary_about_bhcpf_during_the_exercise'))->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
