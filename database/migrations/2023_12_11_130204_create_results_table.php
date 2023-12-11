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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stuId');
            $table->unsignedBigInteger('classId');
            $table->unsignedInteger('session');
            $table->unsignedBigInteger('termId');
            $table->unsignedInteger('examId');
            $table->foreign('classId')->references('id')->on('class_names');
            $table->foreign('termId')->references('id')->on('terms');
            $table->foreign('examId')->references('id')->on('exams');
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
        Schema::dropIfExists('results');
    }
};
