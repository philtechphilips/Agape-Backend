<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassName;
use App\Models\Admin\Section;
use Illuminate\Http\Request;

class Academics extends Controller
{
    public function AddSection(Request $request){
        $request->validate([
            'section' => 'required|string|max:225',
        ]);

        $section = new Section();
        $section->section  = $request->section;
        $section->save();
        return response()->json(['message' => 'Section Created Sucessfully!'], 200);
    }

    public function GetSection(){
        $section = Section::all();
        return response()->json($section, 200);
    }


    public function AddClass(Request $request){
        $request->validate([
            'section' => 'required',
            'className' => 'required',
            'teacher' => 'required',
        ]);

        $class = new ClassName();
        $class->section  = $request->section;
        $class->classname  = $request->className;
        $class->teacher  = $request->teacher;
        $class->save();
        return response()->json(['message' => 'Class Created Sucessfully!'], 200);
    }

    public function GetClass(){
        $class = ClassName::with('sections', 'teachers')->get();
        return response()->json($class, 200);
    }
}
