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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("surname")->nullable();
            $table->string("app_num")->nullable();
            $table->string("middlename")->nullable();
            $table->string("dob")->nullable();
            $table->string("height")->nullable();
            $table->string("weight")->nullable();
            $table->string("school_attended")->nullable();
            $table->string("last_class")->nullable();
            $table->string("other_school")->nullable();
            $table->string("highest_class_before_leaving")->nullable();
            $table->string("reason_for_leaving")->nullable();
            $table->string("head_teacher_of_school")->nullable();
            $table->string("class_to_be_admitted")->nullable();
            $table->string("highest_class")->nullable();
            $table->string("academic_ability")->nullable();
            $table->string("position_in_last_exam")->nullable();
            $table->string("introvert")->nullable();
            $table->string("troublesome")->nullable();
            $table->string("games")->nullable();
            $table->string("fathers_name")->nullable();
            $table->string("mothers_name")->nullable();
            $table->string("fathers_place_of_work")->nullable();
            $table->string("fathers_home_address")->nullable();
            $table->string("mothers_home_address")->nullable();
            $table->string("mothers_place_of_work")->nullable();
            $table->string("mothers_phone")->nullable();
            $table->string("fathers_phone")->nullable();
            $table->string("name_of_financer")->nullable();
            $table->string("imageUrl")->nullable();
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
        Schema::dropIfExists('appliactions');
    }
};
