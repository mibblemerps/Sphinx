<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddGenerationColumnsToWorldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worlds', function (Blueprint $table) {
            $table->string('seed')->nullable();
            $table->string('level_type')->default(\App\Realms\World::LEVEL_TYPE_DEFAULT);
            $table->string('template_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worlds', function (Blueprint $table) {
            $table->dropColumn('seed');
            $table->dropColumn('level_type');
            $table->dropColumn('template_id');
        });
    }
}
