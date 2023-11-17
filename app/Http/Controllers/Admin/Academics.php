<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassName;
use App\Models\Admin\Section;
use App\Models\Admin\Session;
use App\Models\Admin\Subject;
use App\Models\Admin\Term;
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
        return response()->json(['message' => 'Subject Created Sucessfully!'], 200);
    }

    public function GetClass(){
        $class = ClassName::with('sections', 'teachers')->get();
        return response()->json($class, 200);
    }

    public function AddSubject(Request $request){
        $request->validate([
            'subject' => 'required',
            'section' => 'required',
        ]);

        $class = new Subject();
        $class->section  = $request->section;
        $class->subject  = $request->subject;
        $class->teacher  = $request->teacher;
        $class->save();
        return response()->json(['message' => 'Subject Created Sucessfully!'], 200);
    }

    public function GetSubject(){
        $class = Subject::with('sections', 'teachers')->get();
        return response()->json($class, 200);
    }

    public function DeleteSubject($id){
        $subject = Subject::find($id);
        $delete = $subject->delete();
        return response()->json(['message' => 'Subject Deleted Suessfully!'], 200);
    }


    public function AddSession(Request $request){
        $request->validate([
            'session' => 'required',
            'term' => 'required',
        ]);

        $session = new Session();
        $session->session  = $request->session;
        $session->term  = $request->term;
        $session->save();
        return response()->json(['message' => 'Session Created Sucessfully!'], 200);
    }

    public function GetSession(){
        $session = Session::with('term')->get();
        return response()->json($session, 200);
    }

    public function DeleteSession($id){
        $session = Session::find($id);
        $delete = $session->delete();
        return response()->json(['message' => 'Session Deleted Suessfully!'], 200);
    }

    public function GetTerm(){
        $term = Term::all();
        return response()->json($term, 200);
    }
}
