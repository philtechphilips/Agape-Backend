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
        Schema::create('continuous_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('stuId');
            $table->string('surname');
            $table->string('firstname');
            $table->string('subject');
            $table->string('term');
            $table->string('assignment_one')->nullable();
            $table->string('assignment_two')->nullable();
            $table->string('assignment_three')->nullable();
            $table->string('assignment_four')->nullable();
            $table->string('assignment_five')->nullable();
            $table->string('classwork_one')->nullable();
            $table->string('classwork_two')->nullable();
            $table->string('classwork_three')->nullable();
            $table->string('classwork_four')->nullable();
            $table->string('classwork_five')->nullable();
            $table->string('text_one')->nullable();
            $table->string('text_two')->nullable();
            $table->string('text_three')->nullable();
            $table->unsignedBigInteger('classId');
            $table->unsignedInteger('score');
            $table->unsignedInteger('session');
            $table->unsignedBigInteger('termId');
            $table->unsignedInteger('examId');
            $table->unsignedInteger('section');
            $table->boolean('is_released')->default(false);
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
        Schema::dropIfExists('continuous_assessments');
    }
};
