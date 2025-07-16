<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsFakeAndIsTestToAstrologersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->boolean('is_fake')->default(false)->after('is_online');
            $table->boolean('is_test')->default(false)->after('is_fake');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('astrologers', function (Blueprint $table) {
            $table->dropColumn(['is_fake', 'is_test']);
        });
    }
}
