<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('code', 10);
            $table->string('country_code', 5)->default('+91');
            $table->enum('status', ['pending', 'verified', 'expired', 'failed'])->default('pending');
            $table->timestamp('expired_at');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['phone', 'country_code']);
            $table->index(['phone', 'code']);
            $table->index('status');
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verifications');
    }
}
