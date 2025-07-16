<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting key
            $table->text('value'); // Setting value (JSON for complex data)
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable(); // Description of the setting
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_settings');
    }
}
