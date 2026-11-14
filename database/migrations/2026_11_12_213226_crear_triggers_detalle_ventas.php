<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS validar_existencias_lote');
        DB::unprepared('DROP TRIGGER IF EXISTS descontar_existencias_lote');

        DB::unprepared("
            CREATE TRIGGER validar_existencias_lote
            BEFORE INSERT ON detalle_ventas
            FOR EACH ROW
            BEGIN
                DECLARE stock_actual INT;
                DECLARE fecha_vencimiento_lote DATE;

                SELECT cantidad, fecha_vencimiento
                INTO stock_actual, fecha_vencimiento_lote
                FROM lotes
                WHERE id = NEW.lote_id;

                IF stock_actual < NEW.cantidad THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Existencias insuficientes en el lote seleccionado';
                END IF;

                IF fecha_vencimiento_lote < CURDATE() THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'No se puede vender un producto vencido';
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER descontar_existencias_lote
            AFTER INSERT ON detalle_ventas
            FOR EACH ROW
            BEGIN
                UPDATE lotes
                SET cantidad = cantidad - NEW.cantidad
                WHERE id = NEW.lote_id;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS validar_existencias_lote');
        DB::unprepared('DROP TRIGGER IF EXISTS descontar_existencias_lote');
    }
};