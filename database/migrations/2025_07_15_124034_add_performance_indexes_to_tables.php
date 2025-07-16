<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Astrologers - only add if they don't exist
        Schema::table('astrologers', function (Blueprint $table) {
            // $table->index('status'); // Commented out - likely exists
            // $table->index('total_rating'); // Commented out - likely exists
            // $table->index(['status', 'total_rating']); // Commented out - likely exists
        });

        // Astrologer Pricings - only add if they don't exist
        Schema::table('astrologer_pricings', function (Blueprint $table) {
            // $table->index('service_id'); // Commented out - likely exists
            // $table->index(['astrologer_id', 'service_id']); // Commented out - likely exists
        });

        // Astrologer Service - only add if they don't exist
        Schema::table('astrologer_service', function (Blueprint $table) {
            // $table->index('is_enabled'); // Commented out - likely exists
            // $table->index(['astrologer_id', 'is_enabled']); // Commented out - likely exists
        });

        // Services - only add if they don't exist
        Schema::table('services', function (Blueprint $table) {
            // $table->index('name'); // Commented out - likely exists
        });

        // Users - only add if they don't exist
        Schema::table('users', function (Blueprint $table) {
            // $table->index('status'); // Commented out - likely exists
        });

        // Banners - only add if they don't exist
        Schema::table('banners', function (Blueprint $table) {
            // $table->index(['status', 'start_date', 'end_date']); // Commented out - likely exists
        });

        // Products - only add if they don't exist
        Schema::table('products', function (Blueprint $table) {
            // if (Schema::hasColumn('products', 'status')) {
            //     $table->index('status'); // Commented out - likely exists
            // }
            // if (Schema::hasColumn('products', 'created_at')) {
            //     $table->index('created_at'); // Commented out - likely exists
            // }
        });

        // Videos - only add if they don't exist
        Schema::table('videos', function (Blueprint $table) {
            // if (Schema::hasColumn('videos', 'status')) {
            //     $table->index('status'); // Commented out - likely exists
            // }
            // if (Schema::hasColumn('videos', 'sort_order')) {
            //     $table->index(['status', 'sort_order']); // Commented out - likely exists
            // }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // All indexes are commented out in up(), so nothing to drop
        // Schema::table('astrologers', function (Blueprint $table) {
        //     $table->dropIndex(['status']);
        //     $table->dropIndex(['total_rating']);
        //     $table->dropIndex(['status', 'total_rating']);
        // });
        // Schema::table('astrologer_pricings', function (Blueprint $table) {
        //     $table->dropIndex(['service_id']);
        //     $table->dropIndex(['astrologer_id', 'service_id']);
        // });
        // Schema::table('astrologer_service', function (Blueprint $table) {
        //     $table->dropIndex(['is_enabled']);
        //     $table->dropIndex(['astrologer_id', 'is_enabled']);
        // });
        // Schema::table('services', function (Blueprint $table) {
        //     $table->dropIndex(['name']);
        // });
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropIndex(['status']);
        // });
        // Schema::table('banners', function (Blueprint $table) {
        //     $table->dropIndex(['status', 'start_date', 'end_date']);
        // });
        // Schema::table('products', function (Blueprint $table) {
        //     if (Schema::hasColumn('products', 'status')) {
        //         $table->dropIndex(['status']);
        //     }
        //     if (Schema::hasColumn('products', 'created_at')) {
        //         $table->dropIndex(['created_at']);
        //     }
        // });
        // Schema::table('videos', function (Blueprint $table) {
        //     if (Schema::hasColumn('videos', 'status')) {
        //         $table->dropIndex(['status']);
        //     }
        //     if (Schema::hasColumn('videos', 'sort_order')) {
        //         $table->dropIndex(['status', 'sort_order']);
        //     }
        // });
    }
}
