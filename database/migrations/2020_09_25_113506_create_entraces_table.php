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
            $table->bigIncrements('id');
            $table->bigInteger('wallet_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('ticker', 15)->nullable();
            $table->string('tyoe', 20)->nullable();
            $table->string('observation', 255)->nullable();
            $table->decimal('value')->nullable();
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
