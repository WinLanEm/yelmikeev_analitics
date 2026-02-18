<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number',50);
            $table->char('row_hash', 32);
            $table->dateTime('date')->index();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->integer('discount_percent')->default(0);
            $table->string('warehouse_name',150)->nullable();
            $table->string('oblast',100)->nullable();
            $table->bigInteger('income_id')->default(0);
            $table->bigInteger('odid')->default(0);
            $table->bigInteger('nm_id')->default(0);
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_cancel')->default(false);
            $table->date('cancel_dt')->nullable();
            $table->timestamps();

            //эндпоинт из апи отдает полные дубликаты строк, у нас они удаляются, проверял полным хешированием данных
            $table->unique(['g_number', 'nm_id','barcode', 'last_change_date', 'is_cancel','warehouse_name','date','oblast','total_price'],'order_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
