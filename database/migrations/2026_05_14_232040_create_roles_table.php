<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('roles', function (Blueprint $table) {
        $table->tinyInteger('rol_id')->autoIncrement(); // mejor que integer(1)
        $table->dateTime('created');
        $table->integer('createdby');
        $table->dateTime('updated')->nullable();
        $table->integer('updatedby')->nullable();
        $table->enum('isactive', ['Y', 'N']);
        $table->string('name', 100);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
