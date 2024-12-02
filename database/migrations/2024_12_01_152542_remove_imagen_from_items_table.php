<?php
// 2024_12_01_100000_remove_imagen_from_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('imagen')->nullable();
        });
    }
};