<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_evento')->nullable()->constrained('eventos')->nullOnDelete();
            $table->foreignId('id_autor')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->longText('contenido');
            $table->enum('estado', ['borrador', 'publicada'])->default('borrador');
            $table->timestamp('publicado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};