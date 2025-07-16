<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstrologerPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('astrologer_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('astrologer_id');
            $table->string('service_type'); // e.g., chat, call, video
            $table->decimal('price_per_minute', 8, 2);
            $table->decimal('offer_price', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('astrologer_pricings');
    }
}
