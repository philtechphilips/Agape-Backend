<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassName;
use App\Models\Admin\Section;
use App\Models\Admin\Session;
use App\Models\Admin\Student;
use App\Models\Admin\Subject;
use App\Models\Admin\Term;
use Illuminate\Http\Request;

use App\Traits\Paginatable;

class Academics extends Controller
{
    use Paginatable;
    public function AddSection(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:225',
        ]);

        $section = new Section();
        $section->section  = $request->section;
        $section->save();
        return response()->json(['message' => 'Section Created Sucessfully!'], 200);
    }

    public function GetSection()
    {
        $section = Section::all();
        return response()->json($section, 200);
    }


    public function AddClass(Request $request)
    {
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

    public function GetClass()
    {
        $query = ClassName::with('sections', 'teachers')
            ->withCount(['students' => function ($query) {
                $query->where('status', 'Active')->orWhere('status', 'active');
            }]);
        return $this->paginateResponse($query, 10);
    }

    public function GetClassById($id)
    {
        $class = ClassName::where("id", "=", $id)->with('sections', 'teachers')->get();
        return response()->json($class, 200);
    }

    public function UpdateClass(Request $request, $id)
    {
        $request->validate([
            'section' => 'required',
            'className' => 'required',
            'teacher' => 'required',
        ]);

        $class = ClassName::where('uuid', $id)->orWhere('id', $id)->first();

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        $class->update([
            'section' => $request->section,
            'classname' => $request->className,
            'teacher' => $request->teacher,
        ]);

        return response()->json(['message' => 'Class Updated Successfully!'], 200);
    }

    public function AddSubject(Request $request)
    {
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

    public function GetSubject()
    {
        $subject = Subject::with('sections', 'teachers')->get();
        return response()->json($subject, 200);
    }

    public function GetSubjectBySection($section)
    {
        $subject = Subject::where("section", "=", $section)->with('sections', 'teachers')->get();
        return response()->json($subject, 200);
    }

    public function GetSubjectById($id)
    {
        $subject = Subject::with('sections', 'teachers')->where("id", "=", $id)->get();
        return response()->json($subject, 200);
    }

    public function DeleteSubject($id)
    {
        $subject = Subject::where('uuid', $id)->orWhere('id', $id)->first();
        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        $subject->delete();
        return response()->json(['message' => 'Subject Deleted Successfully!'], 200);
    }


    public function AddSession(Request $request)
    {
        $request->validate([
            'session' => 'required',
            'term' => 'required',
        ]);

        Session::create([
            'session' => $request->input('session'),
            'term' => $request->input('term'),
        ]);

        return response()->json(['message' => 'Session Created Successfully!'], 200);
    }

    public function GetSession()
    {
        $session = Session::with('term')->get();
        return response()->json($session, 200);
    }

    public function GetSessionById($id)
    {
        $session = Session::where('id', '=', $id)->with('term')->first();
        return response()->json($session, 200);
    }

    public function ActivateSession($id)
    {
        $session = Session::where('uuid', $id)->orWhere('id', $id)->first();
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        // Deactivate all
        Session::query()->update(['status' => 0]);

        // Activate this one
        $session->update(['status' => 1]);

        return response()->json(['message' => 'Session Activated Successfully!'], 200);
    }

    public function DeleteSession($id)
    {
        $session = Session::where('uuid', $id)->orWhere('id', $id)->first();
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        $session->delete();
        return response()->json(['message' => 'Session Deleted Successfully!'], 200);
    }

    public function GetTerm()
    {
        $term = Term::all();
        return response()->json($term, 200);
    }

    public function DeleteSection($id)
    {
        $section = Section::where('uuid', $id)->orWhere('id', $id)->first();
        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }
        $section->delete();
        return response()->json(['message' => 'Section Deleted Successfully!'], 200);
    }

    public function DeleteClass($id)
    {
        $class = ClassName::where('uuid', $id)->orWhere('id', $id)->first();
        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }
        $class->delete();
        return response()->json(['message' => 'Class Deleted Successfully!'], 200);
    }

    public function PromoteStudent(Request $request)
    {
        foreach ($request->selectedData as $studentDetails) {
            $stuId = $studentDetails['stuId'];
            $student = Student::where("id", "=", $stuId)->first();

            if (!$student) {
                return response()->json(['message' => 'Student not found!'], 400);
            }

            $student->update([
                'class_name_id' => $studentDetails['new_class'],
            ]);
        }
        return response()->json(['message' => 'Student(s) promoted!'], 200);
    }
}
