<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Appraisal;
use App\Models\Admin\Comment;
use App\Models\Admin\Exam;
use App\Models\Admin\FirstTermResults;
use App\Models\Admin\Result;
use App\Models\Admin\Section;
use App\Models\Admin\Session;
use App\Models\Admin\Student;
use App\Models\Admin\Term;
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

            $existingResultForTerm = Result::where([
                'stuId' => $result['stuId'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'session' => $result['session'],
                'classId' => $result['classId'],
            ])->first();

            if (!$existingResultForTerm) {
                $term_result = new Result();
                $term_result->stuId = $result['stuId'];
                $term_result->termId = $result['term']['id'];
                $term_result->examId = $result['exam'];
                $term_result->session = $result['session'];
                $term_result->classId = $result['classId'];
                $term_result->save();
            }

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

    public function FetchResultData($session, $class, $exam)
    {
        $examResults = Result::with(['exam', 'session', 'class', 'student', 'term'])
            ->where('session', $session)
            ->where('classId', $class)
            ->where('examId', $exam)
            ->get();
        return response()->json($examResults, 200);
    }

    public function FetchResultToEdit($session, $class, $exam, $subject)
    {
        $examResults = FirstTermResults::with(['exam', 'session', 'class', 'student', 'term'])
            ->where('session', $session)
            ->where('classId', $class)
            ->where('examId', $exam)
            ->where('subject', $subject)
            ->get();
        return response()->json($examResults, 200);
    }

    public function FetchResult($stuId)
    {
        $result = Result::where("stuId", "=", $stuId)->with(["session",  "term", "exam", "students.className", "students.section"])->get();
        return response()->json($result, 200);
    }

    public function UpdateResultStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $result = Result::find($id);

        if (!$result) {
            return response()->json(['message' => 'Result not found'], 404);
        }

        $result->update([
            'is_released' => $request->status,
        ]);

        return response()->json(['message' => 'Results Updated Successfully!'], 200);
    }


    public function BulkUpdateResultStatus($session, $class, $exam)
    {

        $results = Result::where('session', $session)
            ->where('classId', $class)
            ->where('examId', $exam)
            ->get();

        foreach ($results as $result) {
            $result->update(['is_released' => !$result->is_released]);
        }

        return response()->json(['message' => 'Results Updated Successfully!'], 200);
    }

    public function ReleaseSingleReportCard(Request $request, $id)
    {
        $report_card = FirstTermResults::find($id);

        if (!$report_card) {
            return response()->json(['message' => 'Report card not found'], 404);
        }

        $report_card->update([
            'is_result_released' => $request->is_result_released,
        ]);

        return response()->json(['message' => 'Report Card Updated Suessfully!'], 200);
    }

    public function UpdateFirstTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['caMarks'] + $result['examMarks'];


            $section = Section::find($result['section']);
            $student_section = $section->section;

            $results = $this->calculateGradeAndRemarks($student_section, $total);
            $grade = $results['grade'];
            $remarks = $results['remarks'];

            $existingRecord = FirstTermResults::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if (!$existingRecord) {
                return response()->json(['message' => 'Record not found!'], 404);
            }

            $existingRecord->update([
                'ca' => $result['caMarks'],
                'exam_mark' => $result['examMarks'],
                'grade' => $grade,
                'remarks' => $remarks,
                'total' => $total,
            ]);
        }

        return response()->json(['message' => 'Results Updated Successfully!'], 200);
    }

    public function CreateComment(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $session = Session::where("id", "=", $result['session'])->first();
            $term = Term::where("id", "=", $session->term)->first();

            $existingRecord = Comment::where([
                'stuId' => $result['stuId'],
                'termId' => $session->term,
                'examId' => $result['exam'],
                'session' => $result['session'],
                'comment_type' => $result['comment_type'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'One of the comment exist!'], 400);
            }

            $comment = new Comment();
            $comment->stuId = $result['stuId'];
            $comment->surname = $result['surname'];
            $comment->firstname = $result['firstname'];
            $comment->comment = $result['comment'];
            $comment->comment_type = $result['comment_type'];
            $comment->classId = $result['classId'];
            $comment->session = $result['session'];
            $comment->termId = $session->term;
            $comment->term = $term->term;
            $comment->examId = $result['exam'];
            $comment->save();
        }

        return response()->json(['message' => 'Comment Uploaded Successfully!'], 200);
    }

    public function UpdateTeachersComment(Request $request)
    {

        foreach ($request->selectedData as $result) {
            $session = Session::where("id", "=", $result['session'])->first();
            $term = Term::where("id", "=", $session->term)->first();

            $existingRecord = Comment::where([
                'stuId' => $result['stuId'],
                'termId' => $session->term,
                'examId' => $result['exam'],
                'session' => $result['session'],
                'comment_type' => $result['comment_type'],
            ])->first();

            if (!$existingRecord) {
                return response()->json(['message' => 'Comment not found!'], 400);
            }

            $existingRecord->update([
                'stuId' => $result['stuId'],
                'termId' => $session->term,
                'examId' => $result['exam'],
                'session' => $result['session'],
                'comment_type' => $result['comment_type'],
                'comment' => $result['comment'],
            ]);
        }

        return response()->json(['message' => 'Comment Updated Successfully!'], 200);
    }

    public function FetchTeachersCommentToEdit($session, $class, $exam)
    {
        $teachersComment = Comment::with(['exam', 'session', 'class', 'student', 'term'])
            ->where('session', $session)
            ->where('classId', $class)
            ->where('examId', $exam)
            ->where('comment_type', "teacher")
            ->get();
        return response()->json($teachersComment, 200);
    }

    public function FetchPrincipalsCommentToEdit($session, $class, $exam)
    {
        $teachersComment = Comment::with(['exam', 'session', 'class', 'student', 'term'])
            ->where('session', $session)
            ->where('classId', $class)
            ->where('examId', $exam)
            ->where('comment_type', "principal")
            ->get();
        return response()->json($teachersComment, 200);
    }

    public function CreateAppraisal(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $session = Session::where("id", "=", $result['session'])->first();
            $term = Term::where("id", "=", $session->term)->first();

            $existingRecord = Appraisal::where([
                'stuId' => $result['stuId'],
                'termId' => $session->term,
                'examId' => $result['exam'],
                'session' => $result['session'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'One of the appraisal exist!'], 400);
            }

            $appraisal = new Appraisal();
            $appraisal->stuId = $result['stuId'];
            $appraisal->surname = $result['surname'];
            $appraisal->firstname = $result['firstname'];
            $appraisal->punctuality = $result['punctuality'];
            $appraisal->neatness = $result['neatness'];
            $appraisal->respect = $result['respect'];
            $appraisal->interractions = $result['interractions'];
            $appraisal->sport = $result['sport'];
            $appraisal->initiative = $result['initiative'];
            $appraisal->classId = $result['classId'];
            $appraisal->session = $result['session'];
            $appraisal->termId = $session->term;
            $appraisal->term = $term->term;
            $appraisal->examId = $result['exam'];
            $appraisal->save();
        }

        return response()->json(['message' => 'Appraisal Uploaded Successfully!'], 200);
    }

    public function GetReportCard(Request $request)
    {
        $session = Session::where('id', $request->session)->with("term")->first();

        $result = FirstTermResults::where([
            ['examId', $request->exam],
            ['classId', $request->classes],
            ['stuId', $request->student],
            ['session', $request->session],
        ])->get();

        $appraisal = Appraisal::where([
            ['examId', $request->exam],
            ['classId', $request->classes],
            ['stuId', $request->student],
            ['session', $request->session],
        ])->first();

        $teachersComment = Comment::where([
            ['examId', $request->exam],
            ['classId', $request->classes],
            ['stuId', $request->student],
            ['session', $request->session],
            ['comment_type', "teacher"],
        ])->first();

        $principalsComment = Comment::where([
            ['examId', $request->exam],
            ['classId', $request->classes],
            ['stuId', $request->student],
            ['session', $request->session],
            ['comment_type', "principal"],
        ])->first();

        $StudentsInClass = Student::where([
            ['class_name_id', $request->classes]
        ])->count();

        return response()->json(['session' => $session, 'result' => $result, 'student' => $StudentsInClass, "t_comment" => $teachersComment, "p_comment" => $principalsComment, "appraisal" => $appraisal], 200);
    }
}
