<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsImagesToCaja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caja', function (Blueprint $table) {
            $table->date('fechadeposito')->nullable();
            $table->string('nrooperacion')->nullable();
            $table->string('nombrebanco')->nullable();
            $table->string('urlimagen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caja', function (Blueprint $table) {
            $table->dropColumn('fechadeposito');
            $table->dropColumn('nrooperacion');
            $table->dropColumn('nombrebanco');
            $table->dropColumn('urlimagen');
        });
    }
}
