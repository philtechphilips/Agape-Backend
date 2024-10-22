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
        Schema::create('midterm_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stuId');
            $table->string('surname');
            $table->string('firstname');
            $table->string('subject');
            $table->string('term');
            $table->string('total');
            $table->unsignedBigInteger('classId');
            $table->unsignedInteger('exam_mark');
            $table->unsignedInteger('session');
            $table->unsignedBigInteger('termId');
            $table->unsignedInteger('examId');
            $table->unsignedInteger('section');
            $table->boolean('is_result_released')->default(false);
            $table->timestamps();
            $table->foreign('classId')->references('id')->on('class_names');
            $table->foreign('termId')->references('id')->on('terms');
            $table->foreign('examId')->references('id')->on('exams');
            $table->foreign('sectionId')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('midterm_results');
    }
};
