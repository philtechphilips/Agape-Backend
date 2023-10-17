<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Event;
use Illuminate\Http\Request;

class Schedule extends Controller
{
    public function AddEvent(Request $request){
        $request->validate([
            'name' => 'required|string|max:225',
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        Event::create([
            'name' => $request->name,
            'startDateTime' => $request->startDate,
            'endDateTime' => $request->endDate,
        ]);

        return response()->json(['message' => 'Event Scheduled Sucessfully!'], 200);
    }

    public function Events(){
        $event = Event::all();
        return response()->json($event);
    }
}
