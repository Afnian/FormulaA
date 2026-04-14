<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sistema_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_temporada')->constrained('temporadas')->onDelete('cascade');
            $table->unsignedTinyInteger('posicion');
            $table->unsignedTinyInteger('puntos');
            $table->timestamps();

            $table->unique(['id_temporada', 'posicion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sistema_puntos');
    }
};