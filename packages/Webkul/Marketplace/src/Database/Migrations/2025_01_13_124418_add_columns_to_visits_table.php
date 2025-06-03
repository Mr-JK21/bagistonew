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
        $tableName = config('visitor.table_name');

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (! Schema::hasColumn($tableName, 'marketplace_seller_id')) {
                $table->unsignedInteger('marketplace_seller_id')
                    ->nullable()
                    ->after('visitor_id');

                $table->foreign('marketplace_seller_id')
                    ->references('id')
                    ->on('marketplace_sellers')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('visitor.table_name'), function (Blueprint $table) {
            $table->dropForeign(['marketplace_seller_id']);
            $table->dropColumn('marketplace_seller_id');
        });
    }
};
