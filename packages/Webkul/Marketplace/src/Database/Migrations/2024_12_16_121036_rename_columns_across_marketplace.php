<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('seller_categories', 'seller_id')) {
            Schema::table('seller_categories', function (Blueprint $table) {
                $table->renameColumn('seller_id', 'marketplace_seller_id');

                $table->foreign('marketplace_seller_id')
                    ->references('id')
                    ->on('marketplace_sellers')
                    ->cascadeOnDelete();
            });
        }

        Schema::table('payment_requests', function (Blueprint $table) {
            if (Schema::hasColumn('payment_requests', 'seller_id')) {
                $table->renameColumn('seller_id', 'marketplace_seller_id');

                $table->foreign('marketplace_seller_id')
                    ->references('id')
                    ->on('marketplace_sellers')
                    ->nullOnDelete();
            }

            if (Schema::hasColumn('payment_requests', 'order_id')) {
                $table->dropColumn('order_id');
            }

            if (! Schema::hasColumn('payment_requests', 'marketplace_order_id')) {
                $table->unsignedInteger('marketplace_order_id')->after('id');

                $table->foreign('marketplace_order_id')
                    ->references('id')
                    ->on('marketplace_orders')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
