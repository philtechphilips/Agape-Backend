<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\FirstTermResults;
use App\Models\Admin\SecondTermResult;
use App\Models\Admin\ThirdTermResult;
use App\Models\Admin\Session;
use App\Models\Admin\Term;
use App\Models\Admin\ClassName;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function GetOverallPerformance(Request $request)
    {
        $sessionId = $request->query('session_id');
        $termId = $request->query('term_id'); // 1 for 1st, 2 for 2nd, 3 for 3rd

        if (!$sessionId) {
            $activeSession = Session::where('status', 'Active')->first();
            $sessionId = $activeSession ? $activeSession->id : null;
        }

        if (!$termId && $activeSession = Session::find($sessionId)) {
            $termId = $activeSession->term;
        }

        // 1. Overview Stats
        $totalStudents = Student::where('status', 'Active')->orWhere('status', 'active')->count();
        $totalClasses = ClassName::count();

        // Fetch results based on term
        $results = $this->getResultsByTerm($sessionId, $termId);

        $averageScore = $results->avg('total') ?? 0;
        $passRate = $results->count() > 0 
            ? ($results->where('total', '>=', 50)->count() / $results->count()) * 100 
            : 0;

        // 2. Class Performance
        $classPerformance = $results->groupBy('classId')->map(function ($group) {
            $class = ClassName::find($group->first()->classId);
            return [
                'class_name' => $class ? $class->classname : 'Unknown',
                'average' => round($group->avg('total'), 2),
                'student_count' => $group->unique('stuId')->count()
            ];
        })->values();

        // 3. Grade Distribution
        $gradeDistribution = $results->groupBy('grade')->map(function ($group) {
            return $group->count();
        });

        // 4. Top Students (based on average of their subjects)
        $topStudents = $results->groupBy('stuId')->map(function ($group) {
            return [
                'name' => $group->first()->surname . ' ' . $group->first()->firstname,
                'average' => round($group->avg('total'), 2),
                'class' => ClassName::find($group->first()->classId)->classname ?? 'N/A'
            ];
        })->sortByDesc('average')->take(5)->values();

        // 5. Historical Trend (Average score of current session's terms)
        $historicalTrend = [
            '1st Term' => round($this->getResultsByTerm($sessionId, 1)->avg('total') ?? 0, 2),
            '2nd Term' => round($this->getResultsByTerm($sessionId, 2)->avg('total') ?? 0, 2),
            '3rd Term' => round($this->getResultsByTerm($sessionId, 3)->avg('total') ?? 0, 2),
        ];

        return response()->json([
            'overview' => [
                'total_students' => $totalStudents,
                'total_classes' => $totalClasses,
                'average_score' => round($averageScore, 2),
                'pass_rate' => round($passRate, 2),
            ],
            'class_performance' => $classPerformance,
            'grade_distribution' => $gradeDistribution,
            'top_students' => $topStudents,
            'historical_trend' => $historicalTrend,
            'filters' => [
                'session_id' => $sessionId,
                'term_id' => $termId
            ]
        ], 200);
    }

    private function getResultsByTerm($sessionId, $termId)
    {
        if ($termId == 1) {
            return FirstTermResults::where('session', $sessionId)->get();
        } elseif ($termId == 2) {
            return SecondTermResult::where('session', $sessionId)->get();
        } elseif ($termId == 3) {
            return ThirdTermResult::where('session', $sessionId)->get();
        }
        return collect();
    }

    public function GetComparisonData(Request $request)
    {
        $sessionIds = $request->query('session_ids', []); // Array of session IDs to compare
        
        $comparison = [];
        foreach ($sessionIds as $id) {
            $session = Session::find($id);
            if ($session) {
                $avg = DB::table('first_term_results')->where('session', $id)->avg('total') 
                     + DB::table('second_term_results')->where('session', $id)->avg('total')
                     + DB::table('third_term_results')->where('session', $id)->avg('total');
                
                $comparison[] = [
                    'session_name' => $session->session,
                    'average' => round($avg / 3, 2)
                ];
            }
        }

        return response()->json($comparison, 200);
    }
}
