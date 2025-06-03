<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Webkul\Marketplace\Enums\FlagReasonType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mp_flag_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('admin_name')->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->enum('type', FlagReasonType::values())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mp_flag_reasons');
    }
};
