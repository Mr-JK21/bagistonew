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
        Schema::create('mp_communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('marketplace_seller_id');
            $table->string('subject')->nullable();
            $table->boolean('is_blocked')->default(0);
            $table->timestamps();

            $table->foreign('marketplace_seller_id')
                ->references('id')
                ->on('marketplace_sellers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mp_communications');
    }
};
