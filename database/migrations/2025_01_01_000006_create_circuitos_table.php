<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('circuitos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('pais');
            $table->string('imagen_url')->nullable();
            $table->unsignedSmallInteger('num_vueltas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circuitos');
    }
};