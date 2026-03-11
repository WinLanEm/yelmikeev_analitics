<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('income_id')->index();
            $table->string('number')->nullable();
            $table->date('date')->index();
            $table->date('last_change_date')->nullable();
            $table->date('date_close')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->default(0);
            $table->bigInteger('nm_id')->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('warehouse_name')->nullable();

            $table->char('row_hash', 32)->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
