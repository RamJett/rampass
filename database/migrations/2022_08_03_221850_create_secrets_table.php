<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('secrets', function (Blueprint $table) {

      $table->id();
      $table->timestamps();
      $table->text('uuid');
      $table->timestamp('expire_time');
      $table->bigInteger('count_views')->unsigned()->default(0);
      $table->bigInteger('expire_views')->unsigned()->default(1);
      $table->text('secret');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('secrets');
  }
};
