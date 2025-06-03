<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('marketplace_invoice_id')->unsigned();
            $table->foreign('marketplace_invoice_id')->references('id')->on('marketplace_invoices')->cascadeOnDelete();

            $table->integer('invoice_item_id')->unsigned();
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_invoice_items');
    }
}
