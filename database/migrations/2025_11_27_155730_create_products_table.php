<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->charset     = 'utf8mb4';
            $table->collation   = 'utf8mb4_general_ci';

            $table->comment('TABLA DE PRODUCTOS');
            $table->id()->unique('id_unique');
            $table->unsignedBigInteger('category_id')->index('fk_products_categories1_idx')->comment('ID DE CATEGORIA');
            $table->string('name')->comment('NOMBRE')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->text('description')->comment('DESCRIPCIÓN')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->unsignedBigInteger('price')->comment('PRECIO');
            $table->unsignedBigInteger('stock')->comment('CANTIDAD');
            $table->timestamps();

            $table->foreign(['category_id'], 'fk_products_categories1')->references(['id'])->on('categories')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('fk_products_categories1');
        });
        Schema::dropIfExists('products');
    }
};
