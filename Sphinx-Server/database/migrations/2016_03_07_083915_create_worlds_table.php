<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWorldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worlds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('realm_id');
            $table->string('name');
            $table->boolean('pvp')->default(true);
            $table->smallInteger('gamemode')->default(0);
            $table->boolean('spawn_animals')->default(true);
            $table->smallInteger('difficulty')->default(1);
            $table->boolean('spawn_monsters')->default(true);
            $table->integer('spawn_protection')->default(0);
            $table->boolean('spawn_npcs')->default(true);
            $table->boolean('force_gamemode')->default(false);
            $table->boolean('command_blocks')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('worlds');
    }
}
