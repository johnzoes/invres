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
        // Verificar si la columna 'turno' no existe antes de agregarla
        if (!Schema::hasColumn('reservas', 'turno')) {
            Schema::table('reservas', function (Blueprint $table) {
                $table->enum('turno', ['mañana', 'noche'])->default('mañana')->after('fecha_devolucion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Verificar si la columna 'turno' existe antes de eliminarla
        if (Schema::hasColumn('reservas', 'turno')) {
            Schema::table('reservas', function (Blueprint $table) {
                $table->dropColumn('turno');
            });
        }
    }
};
