<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToTables extends Migration
{
    public function up()
    {
        // Verificar si no existen las columnas y agregarlas
        if (!Schema::hasColumn('salon', 'created_at')) {
            Schema::table('salon', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('armario', 'created_at')) {
            Schema::table('armario', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('item', 'created_at')) {
            Schema::table('item', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('categoria', 'created_at')) {
            Schema::table('categoria', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('profesor', 'created_at')) {
            Schema::table('profesor', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('unidad_didactica', 'created_at')) {
            Schema::table('unidad_didactica', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('usuario', 'created_at')) {
            Schema::table('usuario', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('reserva', 'created_at')) {
            Schema::table('reserva', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('detalle_reserva_item', 'created_at')) {
            Schema::table('detalle_reserva_item', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('estado_reserva', 'created_at')) {
            Schema::table('estado_reserva', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('notificacion', 'created_at')) {
            Schema::table('notificacion', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }

        if (!Schema::hasColumn('asistente', 'created_at')) {
            Schema::table('asistente', function (Blueprint $table) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            });
        }
    }

    public function down()
    {
        // Eliminar las columnas created_at y updated_at si es necesario
        Schema::table('salon', function (Blueprint $table) {
            $table->dropTimestamps();  // Elimina created_at y updated_at
        });

        Schema::table('armario', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('item', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('categoria', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('profesor', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('unidad_didactica', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('usuario', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('reserva', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('detalle_reserva_item', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('estado_reserva', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('notificacion', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('asistente', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}
