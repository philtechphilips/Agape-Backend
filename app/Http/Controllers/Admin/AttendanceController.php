<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attendance;
use App\Models\Admin\ClassName;
use App\Models\Admin\Session;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function GetStudentsByClass($classId)
    {
        $students = Student::where('class_name_id', $classId)
            ->where('status', 'active')
            ->get(['id', 'surname', 'firstname', 'middlename', 'adNum', 'imageUrl']);
        
        return response()->json($students, 200);
    }

    public function MarkAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'date' => 'required|date',
            'period' => 'required|in:morning,afternoon',
            'attendance' => 'required|array',
        ]);

        $activeSession = Session::where('status', 'Active')->first();
        $sessionId = $activeSession ? $activeSession->id : null;
        $termId = $activeSession ? $activeSession->term : null;

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'class_id' => $request->class_id,
                    'date' => $request->date,
                    'period' => $request->period,
                ],
                [
                    'status' => $record['status'],
                    'session_id' => $sessionId,
                    'term_id' => $termId,
                ]
            );
        }

        return response()->json(['message' => 'Attendance marked successfully'], 200);
    }

    public function GetAttendanceSheet(Request $request)
    {
        $classId = $request->query('class_id');
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $students = Student::where('class_name_id', $classId)
            ->where('status', 'active')
            ->get(['id', 'surname', 'firstname', 'middlename']);

        $attendanceData = Attendance::where('class_id', $classId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Get unique dates in this month from the attendance records or just all days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = sprintf("%04d-%02d-%02d", $year, $month, $i);
        }

        return response()->json([
            'students' => $students,
            'dates' => $dates,
            'attendance' => $attendanceData
        ], 200);
    }

    public function GetAttendanceByClass(Request $request)
    {
        $classId = $request->query('class_id');
        $date = $request->query('date', date('Y-m-d'));

        $attendance = Attendance::with('student:id,surname,firstname,middlename,adNum')
            ->where('class_id', $classId)
            ->where('date', $date)
            ->get();

        return response()->json($attendance, 200);
    }

    public function GetAttendanceSummary(Request $request)
    {
        $classId = $request->query('class_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $summary = Attendance::where('class_id', $classId)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('student_id', 'status', DB::raw('count(*) as count'))
            ->groupBy('student_id', 'status')
            ->with('student:id,surname,firstname')
            ->get();

        return response()->json($summary, 200);
    }
}
