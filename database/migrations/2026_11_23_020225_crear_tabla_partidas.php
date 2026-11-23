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
    Schema::create('partidas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jugador_x_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('jugador_o_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('turno', 1)->default('X');
        $table->string('estado')->default('esperando');
        $table->foreignId('ganador_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('resultado')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('partidas');
}
};
