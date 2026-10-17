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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->restrictOnDelete();

            $table->foreignId('detalle_compra_id')
                ->constrained('detalle_compras')
                ->restrictOnDelete();

            $table->string('numero_lote', 100);
            $table->date('fecha_vencimiento');
            $table->date('fecha_ingreso');
            $table->unsignedInteger('cantidad')->default(0);
            $table->timestamps();

            $table->unique(['producto_id', 'numero_lote']);
            $table->index('fecha_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
