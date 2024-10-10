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
            $table->string('name');
            $table->foreignId('beneficiary_id')->constrained();
            $table->foreignId('g_r_o_id')->constrained();
            $table->boolean(('are_you_able_to_sight_the_beneficiary'))->default(false);
            $table->boolean(('did_the_beneficiary_has_nin'))->default(false);
            $table->boolean(('is_the_distance_to_assigned_phc_trekkable'))->default(false);
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
