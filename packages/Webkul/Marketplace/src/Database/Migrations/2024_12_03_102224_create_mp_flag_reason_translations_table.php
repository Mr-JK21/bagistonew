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
        Schema::create('mp_flag_reason_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mp_flag_reason_id')->constrained()->cascadeOnDelete();
            $table->string('locale');
            $table->text('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mp_flag_reason_translations');
    }
};
