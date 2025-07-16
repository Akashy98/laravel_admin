<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAstrologerIdToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->unsignedBigInteger('astrologer_id')->nullable()->after('end_date');
            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('set null');
            $table->index('astrologer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['astrologer_id']);
            $table->dropIndex(['astrologer_id']);
            $table->dropColumn('astrologer_id');
        });
    }
}
