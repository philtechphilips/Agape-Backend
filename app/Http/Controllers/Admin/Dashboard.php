<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ClassName;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function Index(){
        $students = User::where('role', '=', 'student')->count();
        //  $class = ClassName::withCount('students')->get();
        $parent = User::where('role', '=', 'parent')->count();
        return response()->json(['student' => $students, 'parent' => $parent], 200);
    }
}
