<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class StudentApplication extends Controller
{
    public function SubmitApplication(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'dob' => 'required',
        ]);

        $randomNumber = rand(10000, 99999);


        $app_num = "ABC" . '-' . Date('y') . '-' . $randomNumber;

        $application = new Application();
        $application->name = $request->name;
        $application->app_num = $app_num;
        $application->dob = $request->dob;
        $application->height = $request->height;
        $application->weight = $request->weight;
        $application->other_school = $request->otherSchool;
        $application->school_attended = $request->schoolAttended;
        $application->last_class = $request->lastClass;
        $application->highest_class_before_leaving = $request->highestClassBeforeLeaving;
        $application->reason_for_leaving = $request->reasonForLeaving;
        $application->head_teacher_of_school = $request->headTeacher;
        $application->class_to_be_admitted = $request->classToBeAdmitted;
        $application->highest_class = $request->highestClass;
        $application->academic_ability = $request->academicAbility;
        $application->position_in_last_exam = $request->positionInLastExam;
        $application->introvert = $request->introvert;
        $application->troublesome = $request->troublesome;
        $application->games = $request->games;
        $application->fathers_name = $request->fathersName;
        $application->mothers_name = $request->mothersName;
        $application->fathers_place_of_work = $request->fathersPlaceOfWork;
        $application->fathers_home_address = $request->fathersHomeAddress;
        $application->mothers_home_address = $request->mothersHomeAddress;
        $application->mothers_place_of_work = $request->mothersPlaceOfWork;
        $application->mothers_phone = $request->mothersPhone;
        $application->fathers_phone = $request->fathersPhone;
        $application->name_of_financer  = $request->nameOfFinancer;
        $application->save();

        return response()->json(['message' => 'Application Submitted Sucessfully!', 'appNum' => $app_num], 200);
    }


    public function FetchApplication($app_num, Request $request)
    {
        $application = Application::where('app_num', '=', $app_num)->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found!'], 400);
        }

        return response()->json(['message' => 'Application Fetched Sucessfully!', 'application' => $application], 200);
    }


    public function FetchAllApplications(Request $request)
    {
        $application = Application::all();

        return response()->json(['message' => 'Application Fetched Sucessfully!', 'application' => $application], 200);
    }

    public function DeleteApplication($app_num, Request $request)
    {
        $application = Application::where('id', '=', $app_num);

        if (!$application) {
            return response()->json(['message' => 'Application Deleted Sucessfully!'], 400);
        }

        $application->delete();

        return response()->json(['message' => 'Application Deleted Sucessfully!'], 200);
    }


    public function UploadStudentPassport(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required',
        ]);

        $student = Application::find($id);

        Log::info($request->image);

        if (!$student) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        $student->update([
            'imageUrl' => $request->image,
        ]);

        return response()->json(['message' => 'Passport uploaded Suessfully!'], 200);
    }
}
