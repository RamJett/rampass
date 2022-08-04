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
      $table->timestamp('expires_at');
      $table->text('uuid');
      $table->text('secret');
      $table->unsignedBigInteger('count_views')->default(0);
      $table->unsignedBigInteger('expire_views')->default(1);
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
