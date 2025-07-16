<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAstrologerLanguagesTableAddLanguageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologer_languages', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id')->after('astrologer_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });

        // Drop the old language column
        Schema::table('astrologer_languages', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('astrologer_languages', function (Blueprint $table) {
            $table->string('language')->after('astrologer_id');
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
        });
    }
}
