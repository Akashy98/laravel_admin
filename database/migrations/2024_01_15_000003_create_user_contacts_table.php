<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('contact_type', ['phone', 'emergency', 'whatsapp', 'telegram'])->default('phone');
            $table->string('country_code', 5)->default('+91');
            $table->string('phone_number', 20)->nullable();
            $table->string('contact_name')->nullable(); // For emergency contacts
            $table->string('relationship')->nullable(); // For emergency contacts
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'contact_type']);
            $table->index(['user_id', 'is_primary']);
            $table->index('phone_number');
            $table->index('is_verified');
            $table->index('is_active');

            // Unique constraint for phone numbers per user
            $table->unique(['user_id', 'phone_number'], 'user_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_contacts');
    }
}
