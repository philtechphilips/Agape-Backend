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
        Schema::create('appliactions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("surname");
            $table->string("middlename");
            $table->string("dob");
            $table->string("height");
            $table->string("weight");
            $table->string("school_attended");
            $table->string("last_class");
            $table->string("other_school");
            $table->string("highest_class_before_leaving");
            $table->string("reason_for_leaving");
            $table->string("head_teacher_of_school");
            $table->string("class_to_be_admitted");
            $table->string("highest_class");
            $table->string("academic_ability");
            $table->string("position_in_last_exam");
            $table->string("introvert");
            $table->string("troublesome");
            $table->string("games");
            $table->string("fathers_name");
            $table->string("mothers_name");
            $table->string("fathers_place_of_work");
            $table->string("fathers_home_address");
            $table->string("mothers_home_address");
            $table->string("mothers_place_of_work");
            $table->string("mothers_phone");
            $table->string("fathers_phone");
            $table->string("name_of_financer");
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
