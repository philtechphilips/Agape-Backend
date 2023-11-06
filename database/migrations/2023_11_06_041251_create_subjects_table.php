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
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->integer('section');
                $table->foreign('section')->references('id')->on('sections');
                $table->integer('teacher');
                $table->foreign('teacher')->references('id')->on('staff');
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
        Schema::dropIfExists('subjects');
    }
};
