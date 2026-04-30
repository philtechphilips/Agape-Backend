<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Admin\Guardian;
use App\Models\Admin\Staff;
use App\Models\Admin\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JD\Cloudder\Facades\Cloudder;

use App\Traits\Paginatable;

class Profiles extends Controller
{
    use Paginatable;
    public function AddStudent(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:225',
            'firstname' => 'required',
            'middlename' => 'required|string|max:225',
            'city' => 'required|string|max:225',
            'gender' => 'required',
            'dob' => 'required|string|max:225',
            'country' => 'required|string|max:225',
            'state' => 'required|string|max:225',
            'lga' => 'required|string|max:225',
            'religion' => 'required|string|max:225',
            'className' => 'required',
            'section' => 'required',
            'adNum' => 'required|string|max:225',
            'adDate' => 'required|string|max:225',
            'rollNumber' => 'required',
            'address' => 'required|string',
            'parent' => 'required',
        ]);

        $user = User::create([
            'name' => $request->surname . ' ' . $request->firstname . ' ' . $request->middlename,
            'email' => $request->adNum,
            'role' => 'student',
            'password' => Hash::make(strtolower($request->surname)),
        ]);

        if ($user) {
            Student::create([
                'surname' => $request->surname,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'city' => $request->city,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'country' => $request->country,
                'state' => $request->state,
                'lga' => $request->lga,
                'religion' => $request->religion,
                'class_name_id' => $request->className,
                'section' => $request->section,
                'adNum' => $request->adNum,
                'adDate' => $request->adDate,
                'rollNumber' => $request->rollNumber,
                'address' => $request->address,
                'parent_id' => $request->parent,
                'user_id' => $user->id,
            ]);
        }
        return response()->json(['message' => 'Student Profile Created Sucessfully!'], 200);
    }

    public function UpdateStudent(Request $request, $id)
    {
        $request->validate([
            'surname' => 'required|string|max:225',
            'firstname' => 'required',
            'middlename' => 'required|string|max:225',
            'city' => 'required|string|max:225',
            'gender' => 'required',
            'dob' => 'required|string|max:225',
            'adNum' => 'required|string|max:225',
            'adDate' => 'required|string|max:225',
            'rollNumber' => 'required',
            'address' => 'required|string',
            'className' => 'required',
            'section' => 'required',
            'parent' => 'required',
        ]);

        $student = Student::where('uuid', $id)->orWhere('id', $id)->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $user = User::where('email', $student->adNum)->first();
        if ($user) {
            $user->update([
                'name' => $request->surname . ' ' . $request->firstname . ' ' . $request->middlename,
                'email' => $request->adNum,
                'password' => Hash::make(strtolower($request->surname)),
            ]);
        }

        $student->update([
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'city' => $request->city,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'adNum' => $request->adNum,
            'adDate' => $request->adDate,
            'rollNumber' => $request->rollNumber,
            'address' => $request->address,
            'class_name_id' => $request->className,
            'section' => $request->section,
            'parent_id' => $request->parent,
        ]);

        return response()->json(['message' => 'Student Profile Updated Successfully!'], 200);
    }




    public function AddStaff(Request $request)
    {
        $request->validate([
            'surname' => 'required|string|max:225',
            'firstname' => 'required|string|max:225',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'role' => 'required|string|max:225',
        ]);

        $user = User::create([
            'name' => $request->surname . ' ' . $request->firstname,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make('password'),
        ]);

        if ($user) {
            $staf = Staff::create([
                'surname' => $request->surname,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'role' => $request->role,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);

            return response()->json(['message' => 'Staff Profile Created Sucessfully!'], 200);
        }
    }

    public function AllStaff()
    {
        $staffs = Staff::query();
        return $this->paginateResponse($staffs, 20);
    }

    public function UpdateStaff(Request $request, $id)
    {
        $request->validate([
            'surname' => 'required|string|max:225',
            'firstname' => 'required|string|max:225',
            'email' => 'required|email|unique:users,email,' . Staff::find($id)->user_id,
            'phone' => 'required',
            'role' => 'required|string|max:225',
        ]);

        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $user = User::find($staff->user_id);
        if ($user) {
            $user->update([
                'name' => $request->surname . ' ' . $request->firstname,
                'email' => $request->email,
                'role' => $request->role,
            ]);
        }

        $staff->update([
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'role' => $request->role,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Staff Profile Updated Successfully!'], 200);
    }

    public function GetStudents($classes)
    {
        $students = Student::with('className', 'section', 'parent')
            ->where('class_name_id', '=', $classes)
            ->where('status', '=', "active");
        
        return $this->paginateResponse($students, 20);
    }


    public function GetGraduatedStudents()
    {
        $students = Student::with('className', 'section', 'parent')
            ->where('status', '=', "Graduated");
        
        return $this->paginateResponse($students, 20);
    }


    public function GetWithdrawnStudents()
    {
        $students = Student::with('className', 'section', 'parent')
            ->where('status', '=', "left");
        
        return $this->paginateResponse($students, 20);
    }


    public function UpdateAllStudentStatus($classes, Request $request)
    {
        $students = Student::with('className', 'section', 'parent')
            ->where('class_name_id', '=', $classes)
            ->where('status', '=', "active")
            ->get();

        foreach ($students as $stud) {
            $stud->update([
                'status' => $request->newStatus,
                'status_year' => date("Y")
            ]);
        }
        return response()->json([
            'message' => 'Student(s) status updated successfully',
            'students' => $students
        ]);
    }

    public function UpdateAStudentStatus($id, Request $request)
    {
        $student = Student::with('className', 'section', 'parent')
            ->where('id', '=', $id)
            ->first();

        if (!$student) {
            return response()->json([
                'message' => 'Student not found!',
                'students' => $student
            ], 400);
        }

        $student->update([
            'status' => $request->newStatus,
            'status_year' => date("Y")
        ]);

        return response()->json([
            'message' => 'Student status updated successfully',
            'students' => $student
        ]);
    }

    public function GetStudentById($id)
    {
        Log::info($id);
        $students = Student::where('user_id', '=', $id)->first();
        return response()->json($students);
    }

    public function AddParent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'parent',
            'password' => Hash::make('password'),
        ]);

        if ($user) {
            $parent = Guardian::create([
                'name' => $request->name,
                'role' => 'parent',
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);

            return response()->json(['message' => 'Parent Profile Created Sucessfully!'], 200);
        }
    }

    public function AllParent()
    {
        $parent = Guardian::query();
        return $this->paginateResponse($parent, 20);
    }

    public function UpdateParent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users,email,' . Guardian::find($id)->user_id,
            'phone' => 'required',
            'address' => 'required|string',
        ]);

        $parent = Guardian::find($id);

        if (!$parent) {
            return response()->json(['message' => 'Parent not found'], 404);
        }

        $user = User::find($parent->user_id);
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        $parent->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Parent Profile Updated Successfully!'], 200);
    }

    public function AddAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        if ($user) {
            $admin = Admin::create([
                'name' => $request->name,
                'role' => 'admin',
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);

            return response()->json(['message' => 'Administrator Profile Created Sucessfully!'], 200);
        }
    }

    public function AllAdmin()
    {
        $admin = Admin::query();
        return $this->paginateResponse($admin, 20);
    }

    public function UpdateAdmin(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users,email,' . Admin::find($id)->user_id,
            'phone' => 'required',
        ]);

        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Administrator not found'], 404);
        }

        $user = User::find($admin->user_id);
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Administrator Profile Updated Successfully!'], 200);
    }

    public function DeleteParent($id)
    {
        $parent = Guardian::find($id);
        $user = User::find($parent->user_id);
        $delete = $user->delete();
        return response()->json(['message' => 'User Deleted Suessfully!'], 200);
    }

    public function DeleteStaff($id)
    {
        $staff = Staff::find($id);
        $user = User::find($staff->user_id);
        $delete = $user->delete();
        return response()->json(['message' => 'User Deleted Suessfully!'], 200);
    }


    public function DeleteAdmin($id)
    {
        $admin = Admin::find($id);
        $user = User::find($admin->user_id);
        $delete = $user->delete();
        return response()->json(['message' => 'User Deleted Suessfully!'], 200);
    }

    public function UploadStudentPassport(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required',
        ]);

        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->update([
            'imageUrl' => $request->image,
        ]);

        return response()->json(['message' => 'Passport uploaded Suessfully!'], 200);
    }

    public function UpdateMyProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string|max:225',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update linked profile based on role
        if ($user->role === 'admin') {
            Admin::where('user_id', $user->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        } elseif ($user->role === 'staff' || $user->role === 'teacher') {
            Staff::where('user_id', $user->id)->update([
                'surname' => explode(' ', $request->name)[0] ?? '',
                'firstname' => explode(' ', $request->name)[1] ?? '',
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        } elseif ($user->role === 'parent') {
            Guardian::where('user_id', $user->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function ChangeMyPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password does not match'], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
