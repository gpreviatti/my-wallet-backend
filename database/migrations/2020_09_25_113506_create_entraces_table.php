<?php

/**
 * Laravel Schematics
 *
 * WARNING: removing @tag value will disable automated removal
 *
 * @package Laravel-schematics
 * @author  Maarten Tolhuijs <mtolhuys@protonmail.com>
 * @url     https://github.com/mtolhuys/laravel-schematics
 * @tag     laravel-schematics-entraces-model
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntracesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entraces', static function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('uuid')->unique();

            $table->bigInteger('wallet_id')->nullable();
            $table
            ->foreign('wallet_id')
            ->references('id')
            ->on('wallets')
            ->onDelete('cascade');
            
            $table->bigInteger('category_id')->nullable();
            $table->foreign('category_id')
            ->references('id')
            ->on('categories')
            ->onDelete('cascade');
            
            $table->string('description', 255);
            $table->string('ticker', 15)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('observation', 255)->nullable();
            $table->decimal('value');
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
        Schema::dropIfExists('entraces');
    }
}
