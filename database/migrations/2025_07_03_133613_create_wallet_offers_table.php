<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_offers', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2); // The recharge amount for the offer
            $table->unsignedInteger('extra_percent'); // Extra percentage (was bonus_percent)
            $table->boolean('is_popular')->default(false); // For the 'Most Popular' tag
            $table->string('label')->nullable(); // Optional label/tag
            $table->string('status')->default('active'); // active/inactive
            $table->integer('sort_order')->default(0); // For display order
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
        Schema::dropIfExists('wallet_offers');
    }
}
