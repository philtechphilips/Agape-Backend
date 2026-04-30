<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\TimetablePeriod;
use Illuminate\Http\Request;

class TimetablePeriodController extends Controller
{
    public function index()
    {
        return TimetablePeriod::orderBy('sort_order')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            '*.label' => 'required|string',
            '*.start_time' => 'required',
            '*.end_time' => 'required',
            '*.type' => 'required|string',
            '*.period_number' => 'required',
        ]);

        // Clear existing and replace or use updateOrCreate
        // For simplicity in configuration, we'll clear and replace for now
        // or just let them manage individual rows.
        // Actually, let's do updateOrCreate based on ID or period_number
        
        foreach ($request->all() as $index => $data) {
            TimetablePeriod::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'label' => $data['label'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'type' => $data['type'],
                    'period_number' => $data['period_number'],
                    'sort_order' => $index,
                ]
            );
        }

        return response()->json(['message' => 'Periods configured successfully']);
    }

    public function destroy($id)
    {
        TimetablePeriod::destroy($id);
        return response()->json(['message' => 'Period removed']);
    }
}
