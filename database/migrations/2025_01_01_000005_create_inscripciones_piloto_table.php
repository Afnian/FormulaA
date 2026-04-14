<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones_piloto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_piloto')->constrained('pilotos')->onDelete('cascade');
            $table->foreignId('id_escuderia')->constrained('escuderias')->onDelete('cascade');
            $table->foreignId('id_temporada')->constrained('temporadas')->onDelete('cascade');
            $table->enum('tipo', ['oficial', 'reserva', 'academia'])->default('oficial');
            $table->timestamps();

            $table->unique(['id_piloto', 'id_temporada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_piloto');
    }
};