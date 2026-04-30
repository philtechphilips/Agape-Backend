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
        Schema::create('timetable_periods', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('type')->default('subject'); // subject, break, assembly, registration
            $table->decimal('period_number', 4, 1); // allow 3.5 for breaks
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('timetable_periods');
    }
};
