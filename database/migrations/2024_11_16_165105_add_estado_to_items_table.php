<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('items') && !Schema::hasColumn('items', 'estado')) {
            Schema::table('items', function (Blueprint $table) {
                $table->string('estado')->default('disponible')->after('cantidad');
            });
         }
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Eliminar el campo 'estado' en caso de rollback
            $table->dropColumn('estado');
        });
    }
};
