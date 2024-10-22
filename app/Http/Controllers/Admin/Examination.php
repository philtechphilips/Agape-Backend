<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Appraisal;
use App\Models\Admin\Comment;
use App\Models\Admin\Exam;
use App\Models\Admin\FirstTermResults;
use App\Models\Admin\MidtermResult;
use App\Models\Admin\MockResult;
use App\Models\Admin\Result;
use App\Models\Admin\SecondTermResult;
use App\Models\Admin\Section;
use App\Models\Admin\Session;
use App\Models\Admin\Student;
use App\Models\Admin\Subject;
use App\Models\Admin\Term;
use App\Models\Admin\ThirdTermResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Examination extends Controller
{
    public function GetExam()
    {
        $exam = Exam::all();
        return response()->json($exam, 200);
    }


    public function GetExamById($id)
    {
        $exam = Exam::where("id", "=", $id)->first();
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

    public function calculateGradeAndRemarksForMock($student_section, $total)
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
                if ($total >= 75) {
                    return ['grade' => 'A1', 'remarks' => 'EXCELLENT'];
                } elseif ($total > 69) {
                    return ['grade' => 'B2', 'remarks' => 'VERY GOOD'];
                } elseif ($total > 64) {
                    return ['grade' => 'B3', 'remarks' => 'GOOD'];
                } elseif ($total > 59) {
                    return ['grade' => 'C4', 'remarks' => 'CREDIT'];
                } elseif ($total > 54) {
                    return ['grade' => 'C5', 'remarks' => 'CREDIT'];
                } elseif ($total > 49) {
                    return ['grade' => 'C6', 'remarks' => 'CREDIT'];
                } elseif ($total > 44) {
                    return ['grade' => 'D7', 'remarks' => 'PASS'];
                } elseif ($total > 39) {
                    return ['grade' => 'E8', 'remarks' => 'PASS'];
                } elseif ($total >= 0) {
                    return ['grade' => 'F9', 'remarks' => 'FAIL'];
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


    public function MockResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['examMarks'];
            $section = Section::where("id", "=", $result['section'])->first();
            $student_section = $section->section;
            $results = $this->calculateGradeAndRemarksForMock($student_section, $total);
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

            $existingRecord = MockResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new MockResult();
            $first_term_result->stuId = $result['stuId'];
            $first_term_result->surname = $result['surname'];
            $first_term_result->firstname = $result['firstname'];
            $first_term_result->subject = $result['subject'];
            $first_term_result->classId = $result['classId'];
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

        return response()->json(['message' => 'Mock Results Uploaded Successfully!'], 200);
    }

    public function MidTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['examMarks'];

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

            $existingRecord = MidtermResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new MidtermResult();
            $first_term_result->stuId = $result['stuId'];
            $first_term_result->surname = $result['surname'];
            $first_term_result->firstname = $result['firstname'];
            $first_term_result->subject = $result['subject'];
            $first_term_result->classId = $result['classId'];
            $first_term_result->exam_mark = $result['examMarks'];
            $first_term_result->session = $result['session'];
            $first_term_result->termId = $result['term']['id'];
            $first_term_result->term = $result['term']['term'];
            $first_term_result->examId = $result['exam'];
            $first_term_result->section = $result['section'];
            $first_term_result->total = $total;
            $first_term_result->save();
        }

        return response()->json(['message' => 'Midterm Results Uploaded Successfully!'], 200);
    }

    public function JuniorMockResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['examMarks'];
            $section = Section::where("id", "=", $result['section'])->first();
            $student_section = $section->section;
            $results = $this->calculateGradeAndRemarksForMock($student_section, $total);
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

            $existingRecord = MockResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new MockResult();
            $first_term_result->stuId = $result['stuId'];
            $first_term_result->surname = $result['surname'];
            $first_term_result->firstname = $result['firstname'];
            $first_term_result->subject = $result['subject'];
            $first_term_result->classId = $result['classId'];
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

        return response()->json(['message' => 'Mock Result(s) Uploaded Successfully!'], 200);
    }

    public function UpdateMockResult(Request $request)
    {
        Log::info($request);
        foreach ($request->selectedData as $result) {
            $total = $result['examMarks'];
            $section = Section::where("id", "=", $result['section'])->first();
            $student_section = $section->section;
            $results = $this->calculateGradeAndRemarksForMock($student_section, $total);
            $grade = $results['grade'];
            $remarks = $results['remarks'];

            $existingResultForTerm = Result::where([
                'stuId' => $result['stuId'],
                'examId' => $result['exam'],
                'session' => $result['session'],
                'classId' => $result['classId'],
            ])->first();

            if (!$existingResultForTerm) {
                return response()->json(['message' => 'Record not found!'], 404);
            }

            $existingRecord = MockResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if (!$existingRecord) {
                return response()->json(['message' => 'Record not found!'], 404);
            }

            $existingRecord->update([
                'exam_mark' => $result['examMarks'],
                'grade' => $grade,
                'remarks' => $remarks,
                'total' => $total,
            ]);
        }

        return response()->json(['message' => 'Mock Results Updated Successfully!'], 200);
    }

    public function SecondTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['caMarks'] + $result['examMarks'];
            $firstTerm = $result['firstTerm'];
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

            $existingRecord = SecondTermResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new SecondTermResult();
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
            $first_term_result->firstTerm = $result['firstTerm'];
            $first_term_result->total = $total;
            $first_term_result->save();
        }

        return response()->json(['message' => 'Results Uploaded Successfully!'], 200);
    }

    public function ThirdTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $firstTerm = $result['firstTerm'];
            $secondTerm = $result['secondTerm'];
            $total = $result['caMarks'] + $result['examMarks'];


            if ($firstTerm && $secondTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $firstTerm + $secondTerm) / 3;
            } else if (!$firstTerm && $secondTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $secondTerm) / 2;
            } else if (!$secondTerm && $firstTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $firstTerm) / 2;
            } else {
                $percentage = $result['caMarks'] + $result['examMarks'];
            }

            $section = Section::where("id", "=", $result['section'])->first();
            $student_section = $section->section;
            $results = $this->calculateGradeAndRemarks($student_section, $percentage);
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

            $existingRecord = ThirdTermResult::where([
                'stuId' => $result['stuId'],
                'subject' => $result['subject'],
                'termId' => $result['term']['id'],
                'examId' => $result['exam'],
                'section' => $result['section'],
            ])->first();

            if ($existingRecord) {
                return response()->json(['message' => 'Result Exist!'], 400);
            }

            $first_term_result = new ThirdTermResult();
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
            $first_term_result->firstTerm = $result['firstTerm'];
            $first_term_result->secondTerm = $result['secondTerm'];
            $first_term_result->total = $total;
            $first_term_result->percentage = $percentage;
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

        $session_data = Session::where("id", "=", $session)->first();

        if ($exam == "1" && $session_data->term == "1") {
            $examResults = FirstTermResults::with(['exam', 'session', 'class', 'student', 'term'])
                ->where('session', $session)
                ->where('classId', $class)
                ->where('examId', $exam)
                ->where('subject', $subject)
                ->get();
        } else if ($exam == "1" && $session_data->term == "2") {
            $examResults = SecondTermResult::with(['exam', 'session', 'class', 'student', 'term'])
                ->where('session', $session)
                ->where('classId', $class)
                ->where('examId', $exam)
                ->where('subject', $subject)
                ->get();
        } else if ($exam == "1" && $session_data->term == "3") {
            $examResults = ThirdTermResult::with(['exam', 'session', 'class', 'student', 'term'])
                ->where('session', $session)
                ->where('classId', $class)
                ->where('examId', $exam)
                ->where('subject', $subject)
                ->get();
        } else if ($exam == "2") {
            $examResults = MockResult::with(['exam', 'session', 'class', 'student', 'term'])
                ->where('session', $session)
                ->where('classId', $class)
                ->where('examId', $exam)
                ->where('subject', $subject)
                ->get();
        }
        return response()->json($examResults, 200);
    }


    public function FetchFirstTermResultForSecondReport($class, $exam, $subject)
    {
        $subject = Subject::find($subject);
        $examResults = FirstTermResults::with(['exam', 'class', 'student', 'term'])
            ->where('term', "1st Term")
            ->where('classId', $class)
            ->where('examId', $exam)
            ->where('subject', $subject->subject)
            ->get();
        return response()->json($examResults, 200);
    }

    public function FetchSecondTermResultForThirdReport($class, $exam, $subject)
    {
        $subject = Subject::find($subject);
        $examResults = SecondTermResult::with(['exam', 'class', 'student', 'term'])
            ->where('term', "2nd Term")
            ->where('classId', $class)
            ->where('examId', $exam)
            ->where('subject', $subject->subject)
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

    public function UpdateSecondTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $total = $result['caMarks'] + $result['examMarks'];

            $section = Section::find($result['section']);
            $student_section = $section->section;

            $results = $this->calculateGradeAndRemarks($student_section, $total);
            $grade = $results['grade'];
            $remarks = $results['remarks'];

            $existingRecord = SecondTermResult::where([
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

    public function UpdateThirdTermResult(Request $request)
    {
        foreach ($request->selectedData as $result) {
            $firstTerm = $result['firstTerm'];
            $secondTerm = $result['secondTerm'];
            $total = $result['caMarks'] + $result['examMarks'];

            if ($firstTerm && $secondTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $firstTerm + $secondTerm) / 3;
            } else if (!$firstTerm && $secondTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $secondTerm) / 2;
            } else if (!$secondTerm && $firstTerm) {
                $percentage = ($result['caMarks'] + $result['examMarks'] + $firstTerm) / 2;
            } else {
                $percentage = $result['caMarks'] + $result['examMarks'];
            }


            $section = Section::find($result['section']);
            $student_section = $section->section;

            $results = $this->calculateGradeAndRemarks($student_section, $percentage);
            $grade = $results['grade'];
            $remarks = $results['remarks'];

            Log::info($results);

            $existingRecord = ThirdTermResult::where([
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
                'grade' => $results['grade'],
                'remarks' => $results['remarks'],
                'total' => $total,
                'percentage' => $percentage,
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
        if ($request->exam == 2) {
            $session = Session::where('id', $request->session)->with("term")->first();
            $result = MockResult::where([
                ['examId', $request->exam],
                ['classId', $request->classes],
                ['stuId', $request->student],
                ['session', $request->session],
            ])->get();
            return response()->json(['result' => $result, 'session' => $session], 200);
        } else if ($request->exam == 3) {
            $session = Session::where('id', $request->session)->with("term")->first();
            $result = MidtermResult::where([
                ['examId', $request->exam],
                ['classId', $request->classes],
                ['stuId', $request->student],
                ['session', $request->session],
            ])->get();
            return response()->json(['result' => $result, 'session' => $session], 200);
        } else {
            $session = Session::where('id', $request->session)->with("term")->first();
            $find_session = Session::where('id', $request->session)->with("term")->first();
            if ($find_session) {
                if ($find_session) {
                    if ($find_session->term == 1) {
                        $result = FirstTermResults::where([
                            ['examId', $request->exam],
                            ['classId', $request->classes],
                            ['stuId', $request->student],
                            ['session', $request->session],
                        ])->get();
                    } else if ($find_session->term == 2) {
                        $result = SecondTermResult::where([
                            ['examId', $request->exam],
                            ['classId', $request->classes],
                            ['stuId', $request->student],
                            ['session', $request->session],
                        ])->get();
                    } else {
                        $result = ThirdTermResult::where([
                            ['examId', $request->exam],
                            ['classId', $request->classes],
                            ['stuId', $request->student],
                            ['session', $request->session],
                        ])->get();
                    }
                }
            }


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
}
