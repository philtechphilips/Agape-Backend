<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\TimetablePeriod;

class TimetablePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periods = [
            ['label' => 'Assembly', 'start_time' => '08:00', 'end_time' => '08:15', 'type' => 'assembly', 'period_number' => 0, 'sort_order' => 0],
            ['label' => '1st Period', 'start_time' => '08:15', 'end_time' => '08:55', 'type' => 'subject', 'period_number' => 1, 'sort_order' => 1],
            ['label' => '2nd Period', 'start_time' => '08:55', 'end_time' => '09:35', 'type' => 'subject', 'period_number' => 2, 'sort_order' => 2],
            ['label' => '3rd Period', 'start_time' => '09:35', 'end_time' => '10:15', 'type' => 'subject', 'period_number' => 3, 'sort_order' => 3],
            ['label' => 'Short Break', 'start_time' => '10:15', 'end_time' => '10:30', 'type' => 'break', 'period_number' => 3.5, 'sort_order' => 4],
            ['label' => '4th Period', 'start_time' => '10:30', 'end_time' => '11:10', 'type' => 'subject', 'period_number' => 4, 'sort_order' => 5],
            ['label' => '5th Period', 'start_time' => '11:10', 'end_time' => '11:50', 'type' => 'subject', 'period_number' => 5, 'sort_order' => 6],
            ['label' => '6th Period', 'start_time' => '11:50', 'end_time' => '12:30', 'type' => 'subject', 'period_number' => 6, 'sort_order' => 7],
            ['label' => 'Long Break', 'start_time' => '12:30', 'end_time' => '13:15', 'type' => 'break', 'period_number' => 6.5, 'sort_order' => 8],
            ['label' => '7th Period', 'start_time' => '13:15', 'end_time' => '13:55', 'type' => 'subject', 'period_number' => 7, 'sort_order' => 9],
            ['label' => '8th Period', 'start_time' => '13:55', 'end_time' => '14:35', 'type' => 'subject', 'period_number' => 8, 'sort_order' => 10],
        ];

        foreach ($periods as $period) {
            TimetablePeriod::create($period);
        }
    }
}
