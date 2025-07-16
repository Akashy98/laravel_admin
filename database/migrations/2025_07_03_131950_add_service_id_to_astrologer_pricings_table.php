<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToAstrologerPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('astrologer_pricings', 'service_id')) {  
        Schema::table('astrologer_pricings', function (Blueprint $table) {
                $table->unsignedBigInteger('service_id')->nullable()->after('astrologer_id');
                $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('astrologer_pricings', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
}
