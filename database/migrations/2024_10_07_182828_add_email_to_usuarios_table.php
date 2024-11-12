<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Verifica si la columna 'email' no existe antes de agregarla
            if (!Schema::hasColumn('usuarios', 'email')) {
                $table->string('email')->unique()->after('nombre_usuario');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Verifica si la columna 'email' existe antes de eliminarla
            if (Schema::hasColumn('usuarios', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
}
