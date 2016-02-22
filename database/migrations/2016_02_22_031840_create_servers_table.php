<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use App\Realms\Server;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('state')->default(Server::STATE_OPEN);
            $table->integer('days_left')->default(365);
            $table->boolean('expired')->default(false);
            $table->json('invited_players')->default('[]');
            $table->json('operators')->default('[]');
            $table->boolean('minigames_server')->default(false);
            $table->string('motd')->default('A Minecraft Realm');
            $table->string('owner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('servers');
    }
}
