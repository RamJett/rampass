<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('maintenances', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('job');
      $table->unsignedInteger('counter_expire');
      $table->unsignedInteger('expire_counts');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('maintenances');
  }
};
