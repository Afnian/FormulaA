<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_evento')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('id_inscripcion')->constrained('inscripciones_piloto')->onDelete('cascade');
            $table->unsignedTinyInteger('posicion')->nullable();
            $table->string('diferencia')->nullable();
            $table->unsignedTinyInteger('pts_carrera')->default(0);
            $table->unsignedTinyInteger('pts_vuelta_rap')->default(0);
            $table->unsignedTinyInteger('pts_pole')->default(0);
            $table->boolean('dnf')->default(false);
            $table->timestamps();

            $table->unique(['id_evento', 'id_inscripcion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};