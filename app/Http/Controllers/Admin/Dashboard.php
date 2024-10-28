<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassName;
use App\Models\Admin\Student;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function Index()
    {
        $students = Student::where('status', 'active')
        ->orWhereNull('status')
        ->count();
        $graduated_students = Student::where('status', 'Graduated')->count();
        $withdrawn_students = Student::where('status', 'left')->count();
        $parent = User::where('role', 'parent')->count();

        return response()->json([
            'student' => $students,
            'graduated' => $graduated_students,
            'left' => $withdrawn_students,
            'parent' => $parent
        ], 200);
    }
}
