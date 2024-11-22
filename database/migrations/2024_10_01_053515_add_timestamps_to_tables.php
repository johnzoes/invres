<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToTables extends Migration
{
    public function up()
    {
        // Tablas que necesitan timestamps
        Schema::table('salones', function (Blueprint $table) {
            if (!Schema::hasColumn('salones', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });

        Schema::table('armarios', function (Blueprint $table) {
            if (!Schema::hasColumn('armarios', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });

        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });

        Schema::table('categorias', function (Blueprint $table) {
            if (!Schema::hasColumn('categorias', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });

        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });

        Schema::table('reservas', function (Blueprint $table) {
            if (!Schema::hasColumn('reservas', 'created_at')) {
                $table->timestamps();  // Agrega created_at y updated_at si no existen
            }
        });


    }

    public function down()
    {
        // Eliminar los timestamps de las tablas seleccionadas si es necesario
        Schema::table('salones', function (Blueprint $table) {
            $table->dropTimestamps();  // Elimina created_at y updated_at
        });

        Schema::table('armarios', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('reservas', function (Blueprint $table) {
            $table->dropTimestamps();
        });


    }
}
