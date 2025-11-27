<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->charset     = 'utf8mb4';
            $table->collation   = 'utf8mb4_general_ci';

            $table->comment('TABLA DE CATEGORÍAS');
            $table->id()->unique('id_unique');
            $table->string('name')->comment('NOMBRE')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
