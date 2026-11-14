<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('estado', 20)->default('completada')->after('total');
            $table->string('motivo_anulacion', 255)->nullable()->after('estado');

            $table->foreignId('anulada_por')
                ->nullable()
                ->after('motivo_anulacion')
                ->constrained('usuarios')
                ->restrictOnDelete();

            $table->timestamp('anulada_at')->nullable()->after('anulada_por');

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['anulada_por']);
            $table->dropIndex(['estado']);
            $table->dropColumn([
                'estado',
                'motivo_anulacion',
                'anulada_por',
                'anulada_at',
            ]);
        });
    }
};