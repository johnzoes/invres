<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('id_rol'); // Elimina la columna de roles
        });
    }
    
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol'); // Recupera la columna en caso de rollback
        });
    }
    
};
