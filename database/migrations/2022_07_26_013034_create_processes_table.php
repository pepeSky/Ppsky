<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->text('iframe');
            $table->unsignedBigInteger('platform_id')->nullable();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('instruction_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();


            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('set null');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
            $table->foreign('instruction_id')->references('id')->on('instructions')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
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
        Schema::dropIfExists('processes');
    }
}
