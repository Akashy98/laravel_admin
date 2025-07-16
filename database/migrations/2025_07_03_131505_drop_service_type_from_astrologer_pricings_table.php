<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropServiceTypeFromAstrologerPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologer_pricings', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('astrologer_pricings', function (Blueprint $table) {
            $table->string('service_type')->after('astrologer_id'); // e.g., chat, call, video
        });
    }
}
