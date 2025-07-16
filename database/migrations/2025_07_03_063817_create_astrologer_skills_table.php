<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('astrologer_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('astrologer_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('astrologer_skills');
    }
}
