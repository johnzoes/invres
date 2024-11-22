<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Añadir el campo 'estado' a la tabla 'items'
            $table->string('estado')->default('disponible')->after('cantidad');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Eliminar el campo 'estado' en caso de rollback
            $table->dropColumn('estado');
        });
    }
};
