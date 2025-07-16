<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // User who is booking
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Astrologer (can be null for instant booking until assigned)
            $table->unsignedBigInteger('astrologer_id')->nullable();
            $table->foreign('astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');

            // For fake astrologer support - original astrologer after assignment
            $table->unsignedBigInteger('original_astrologer_id')->nullable();
            $table->foreign('original_astrologer_id')->references('id')->on('astrologers')->onDelete('cascade');

            // Service type (chat, call, video_call)
            $table->enum('service_type', ['chat', 'call', 'video_call']);

            // Booking type (instant, scheduled)
            $table->enum('booking_type', ['instant', 'scheduled']);

            // For scheduled appointments
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('duration_minutes')->default(15); // 10, 15, 20 minutes

            // For instant appointments
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            // Status tracking
            $table->enum('status', [
                'pending',      // Waiting for astrologer to accept
                'accepted',     // Astrologer accepted
                'in_progress',  // Session started
                'completed',    // Session completed
                'cancelled',    // Cancelled by user/astrologer
                'expired',      // Not accepted within time limit
                'no_astrologer' // No astrologer available
            ])->default('pending');

            // Pricing
            $table->decimal('base_amount', 10, 2); // Original price
            $table->decimal('final_amount', 10, 2); // After any discounts
            $table->decimal('amount_paid', 10, 2)->default(0); // Amount deducted from wallet

            // Payment status
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('payment_timing', ['on_request', 'on_accept'])->default('on_accept');

            // Broadcast settings (for instant booking)
            $table->boolean('is_broadcast')->default(false); // Send to multiple astrologers
            $table->integer('max_wait_time')->default(300); // 5 minutes default

            // Cancellation
            $table->text('cancellation_reason')->nullable();
            $table->enum('cancelled_by', ['user', 'astrologer', 'system'])->nullable();

            // Session details
            $table->text('user_notes')->nullable(); // User's notes/questions
            $table->text('astrologer_notes')->nullable(); // Astrologer's notes
            $table->integer('rating')->nullable(); // User's rating 1-5
            $table->text('review')->nullable(); // User's review

            // Technical details
            $table->string('session_id')->nullable(); // For tracking session
            $table->json('session_meta')->nullable(); // Additional session data

            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['astrologer_id', 'status']);
            $table->index(['original_astrologer_id', 'status']);
            $table->index(['booking_type', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['requested_at', 'status']);
            $table->index(['service_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
