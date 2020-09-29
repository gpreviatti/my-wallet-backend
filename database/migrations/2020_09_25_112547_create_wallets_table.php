<?php

/**
 * Laravel Schematics
 *
 * WARNING: removing @tag value will disable automated removal
 *
 * @package Laravel-schematics
 * @author  Maarten Tolhuijs <mtolhuys@protonmail.com>
 * @url     https://github.com/mtolhuys/laravel-schematics
 * @tag     laravel-schematics-wallets-model
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', static function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('uuid')->unique();
            $table->integer('wallets_types_id');
            $table->foreign('wallets_types_id')->references('id')->on('wallet_types');
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
            $table->decimal('current_value')->nullable();
            $table->integer('due_date')->nullable();
            $table->integer('close_date')->nullable();
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
        Schema::dropIfExists('wallets');
    }
}
