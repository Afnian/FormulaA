// database/migrations/2026_05_18_000001_make_id_usuario_nullable_in_pilotos.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pilotos', function (Blueprint $table) {
            $table->foreignId('id_usuario')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pilotos', function (Blueprint $table) {
            $table->foreignId('id_usuario')->nullable(false)->change();
        });
    }
};