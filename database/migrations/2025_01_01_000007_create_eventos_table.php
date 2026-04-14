<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_temporada')->constrained('temporadas')->onDelete('cascade');
            $table->foreignId('id_circuito')->constrained('circuitos')->onDelete('cascade');
            $table->string('nombre');
            $table->unsignedTinyInteger('ronda');
            $table->dateTime('fecha');
            $table->boolean('completado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};