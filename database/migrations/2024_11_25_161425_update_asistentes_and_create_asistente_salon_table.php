<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAsistentesAndCreateAsistenteSalonTable extends Migration
{
    public function up()
    {
        // Crear la tabla intermedia asistente_salon
        Schema::create('asistente_salon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asistente_id');
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('asistente_id')->references('id')->on('asistentes')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salones')->onDelete('cascade');
        });

        // Eliminar la columna id_salon de la tabla asistentes
        Schema::table('asistentes', function (Blueprint $table) {
            if (Schema::hasColumn('asistentes', 'id_salon')) {
                $table->dropForeign(['id_salon']); // Eliminar la clave foránea
                $table->dropColumn('id_salon');   // Eliminar la columna
            }
        });
    }

    public function down()
    {
        // Restaurar la columna id_salon en la tabla asistentes
        Schema::table('asistentes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_salon')->nullable();
            $table->foreign('id_salon')->references('id')->on('salones')->onDelete('set null');
        });

        // Eliminar la tabla intermedia asistente_salon
        Schema::dropIfExists('asistente_salon');
    }
}
