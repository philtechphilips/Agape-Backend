<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Exam;
use App\Models\Admin\FirstTermResults;
use App\Models\Admin\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Examination extends Controller
{
    public function GetExam()
    {
        $exam = Exam::all();
        return response()->json($exam, 200);
    }

    public function calculateGradeAndRemarks($student_section, $total)
    {
        switch ($student_section) {
            case 'Junior Secondary School':
                if ($total > 79) {
                    return ['grade' => 'A', 'remarks' => 'DISTINCTION'];
                } elseif ($total > 59) {
                    return ['grade' => 'C', 'remarks' => 'CREDIT'];
                } elseif ($total > 49) {
                    return ['grade' => 'P', 'remarks' => 'PASS'];
                } elseif ($total >= 0) {
                    return ['grade' => 'F', 'remarks' => 'WEAK'];
                }
                break;

            case 'Senior Secondary School':
                if ($total >= 80) {
                    return ['grade' => 'A1', 'remarks' => 'EXCELLENT'];
                } elseif ($total > 74) {
                    return ['grade' => 'B2', 'remarks' => 'VERY GOOD'];
                } elseif ($total > 69) {
                    return ['grade' => 'B3', 'remarks' => 'VERY GOOD'];
                } elseif ($total > 64) {
                    return ['grade' => 'C4', 'remarks' => 'GOOD'];
                } elseif ($total > 60) {
                    return ['grade' => 'C5', 'remarks' => 'GOOD'];
                } elseif ($total > 54) {
                    return ['grade' => 'C6', 'remarks' => 'GOOD'];
                } elseif ($total > 49) {
                    return ['grade' => 'D7', 'remarks' => 'PASS'];
                } elseif ($total > 44) {
                    return ['grade' => 'E8', 'remarks' => 'PASS'];
                } elseif ($total >= 0) {
                    return ['grade' => 'F9', 'remarks' => 'WEAK'];
                }
                break;
        }

        return ['grade' => 'Unknown', 'remarks' => 'Unknown'];
    }

    public function FirstTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['caMarks'] + $result['examMarks'];
            $section = Section::where("id", "=", $result['section'])->first();
            $student_section = $section->section;
            $results = $this->calculateGradeAndRemarks($student_section, $total);
            $grade = $results['grade'];
            $remarks = $results['remarks'];

            $existingRecord = FirstTermResults::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new FirstTermResults();
            $first_term_result->stuId = $result['stuId'];
            $first_term_result->surname = $result['surname'];
            $first_term_result->firstname = $result['firstname'];
            $first_term_result->subject = $result['subject'];
            $first_term_result->classId = $result['classId'];
            $first_term_result->ca = $result['caMarks'];
            $first_term_result->exam_mark = $result['examMarks'];
            $first_term_result->session = $result['session'];
            $first_term_result->termId = $result['term']['id'];
            $first_term_result->term = $result['term']['term'];
            $first_term_result->examId = $result['exam'];
            $first_term_result->section = $result['section'];
            $first_term_result->grade = $grade;
            $first_term_result->remarks = $remarks;
            $first_term_result->total = $total;
            $first_term_result->save();
        }

        return response()->json(['message' => 'Results Uploaded Successfully!'], 200);
    }
}
