<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalRatingToAstrologersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('astrologers', 'total_rating')) {
            Schema::table('astrologers', function (Blueprint $table) {
                $table->float('total_rating', 3, 2)->nullable()->default(0)->after('experience_years');
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
        if (Schema::hasColumn('astrologers', 'total_rating')) {
            Schema::table('astrologers', function (Blueprint $table) {
                $table->dropColumn('total_rating');
            });
        }
    }
}
