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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('city');
            $table->string('gender');
            $table->string('dob');
            $table->string('country');
            $table->string('state');
            $table->string('lga');
            $table->string('religion');
            $table->unsignedBigInteger('class_name_id');
            $table->foreign('class_name_id')->references('id')->on('class_names');
            $table->integer('section');
            $table->string('adNum');
            $table->string('adDate');
            $table->string('rollNumber');
            $table->string('address');
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('guardians')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('students');
    }
};
