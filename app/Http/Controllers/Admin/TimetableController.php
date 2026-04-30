<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $class_id = $request->class_id;
        $type = $request->input('type', 'class');
        
        $timetable = Timetable::with('subject')
            ->where('class_id', $class_id)
            ->where('timetable_type', $type)
            ->get();
        return response()->json($timetable);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $type = $request->input('type', 'class'); // Actually, type might be in the individual entries
        
        foreach ($data as $entry) {
            Timetable::updateOrCreate(
                [
                    'class_id' => $entry['class_id'],
                    'day' => $entry['day'],
                    'period_number' => $entry['period_number'],
                    'timetable_type' => $entry['timetable_type'] ?? $type,
                ],
                [
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'subject_id' => $entry['subject_id'] ?? null,
                    'activity_type' => $entry['activity_type'] ?? 'subject',
                    'custom_activity' => $entry['custom_activity'] ?? null,
                ]
            );
        }
        return response()->json(['message' => 'Schedule updated successfully']);
    }
}
