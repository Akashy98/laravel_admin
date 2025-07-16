<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Birth Details
            $table->date('birth_date')->nullable();
            $table->time('birth_time')->nullable();
            $table->string('birth_time_accuracy')->nullable(); // 'exact', 'approximate', 'unknown'
            $table->text('birth_notes')->nullable();

            // Personal Details
            $table->string('gender')->nullable(); // 'male', 'female', 'other'
            $table->string('marital_status')->nullable(); // 'single', 'married', 'divorced', 'widowed'
            $table->date('marriage_date')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('gotra')->nullable();
            $table->string('nakshatra')->nullable();
            $table->string('rashi')->nullable();

            // Additional Information
            $table->text('about_me')->nullable();
            $table->text('additional_notes')->nullable();
            $table->boolean('is_profile_complete')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'is_active']);
            $table->index('birth_date');
            $table->index('gender');
            $table->index('marital_status');
            $table->index('religion');
            $table->index('caste');
            $table->index('nakshatra');
            $table->index('rashi');
            $table->index('is_profile_complete');

            // Unique constraint - one profile per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
