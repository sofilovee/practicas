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
    Schema::create('movimientos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('partida_id')->constrained('partidas')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->unsignedTinyInteger('posicion');
        $table->string('simbolo', 1);
        $table->timestamps();

        // Una casilla solo puede ocuparse una vez por partida
        $table->unique(['partida_id', 'posicion']);
    });
}

public function down(): void
{
    Schema::dropIfExists('movimientos');
}
};
