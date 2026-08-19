<?php

use App\Http\Controllers\Admin\Academics;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Admin\Examination;
use App\Http\Controllers\Admin\Profiles;
use App\Http\Controllers\Admin\Schedule;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TimetablePeriodController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Main\MainFunctions;
use App\Http\Controllers\Main\StudentApplication;
use App\Http\Controllers\MobileAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/create-staff-profile', [Profiles::class, 'AddStaff']);
    Route::post('/create-parent-profile', [Profiles::class, 'AddParent']);
    Route::post('/create-admin-profile', [Profiles::class, 'AddAdmin']);
    Route::post('/create-student-profile', [Profiles::class, 'AddStudent']);
    Route::patch('/student-passport/{id}', [Profiles::class, 'UploadStudentPassport']);
    Route::patch('/update-student/{id}', [Profiles::class, 'UpdateStudent']);
    Route::patch('/update-admin/{id}', [Profiles::class, 'UpdateAdmin']);

    Route::get('/all-staff', [Profiles::class, 'AllStaff']);
    Route::patch('/update-staff/{id}', [Profiles::class, 'UpdateStaff']);
    Route::get('/all-parent', [Profiles::class, 'AllParent']);
    Route::patch('/update-parent/{id}', [Profiles::class, 'UpdateParent']);
    Route::get('/all-admin', [Profiles::class, 'AllAdmin']);

    Route::get('/view-students/graduated', [Profiles::class, 'GetGraduatedStudents']);
    Route::get('/view-students/left', [Profiles::class, 'GetWithdrawnStudents']);
    Route::patch('/students-status/{classes}', [Profiles::class, 'UpdateAllStudentStatus']);
    Route::patch('/student-status/{id}', [Profiles::class, 'UpdateAStudentStatus']);

    Route::delete('/delete-parent/{id}', [Profiles::class, 'DeleteParent']);
    Route::delete('/delete-admin/{id}', [Profiles::class, 'DeleteAdmin']);
    Route::delete('/delete-staff/{id}', [Profiles::class, 'DeleteStaff']);

    Route::post('/create-event', [Schedule::class, 'AddEvent']);

    Route::post('/add-section', [Academics::class, 'AddSection']);

    Route::post('/add-class', [Academics::class, 'AddClass']);
    Route::patch('/update-class/{id}', [Academics::class, 'UpdateClass']);

    Route::post('/add-subject', [Academics::class, 'AddSubject']);

    Route::delete('/subjects/{id}', [Academics::class, 'DeleteSubject']);
    Route::delete('/delete-section/{id}', [Academics::class, 'DeleteSection']);
    Route::delete('/delete-class/{id}', [Academics::class, 'DeleteClass']);
    Route::delete('/session/{id}', [Academics::class, 'DeleteSession']);
    Route::patch('/activate-session/{id}', [Academics::class, 'ActivateSession']);

    Route::post('/promote-student', [Academics::class, 'PromoteStudent']);

    Route::post('/session', [Academics::class, 'AddSession']);

    Route::get('/timetable', [TimetableController::class, 'index']);
    Route::post('/timetable', [TimetableController::class, 'store']);
    Route::get('/timetable-periods', [TimetablePeriodController::class, 'index']);
    Route::post('/timetable-periods', [TimetablePeriodController::class, 'store']);
    Route::delete('/timetable-periods/{id}', [TimetablePeriodController::class, 'destroy']);


    // Online Student application
    Route::get('/applications', [StudentApplication::class, 'FetchAllApplications']);
    Route::get('/applications/export', [StudentApplication::class, 'ExportApplications']);
    Route::delete('/delete-application/{app_num}', [StudentApplication::class, 'DeleteApplication']);

    // Analytics & Performance
    Route::get('/analytics/overall-performance', [AnalyticsController::class, 'GetOverallPerformance']);
    Route::get('/analytics/comparison', [AnalyticsController::class, 'GetComparisonData']);

    // Attendance
    Route::get('/attendance/students/{classId}', [AttendanceController::class, 'GetStudentsByClass']);
    Route::post('/attendance/mark', [AttendanceController::class, 'MarkAttendance']);
    Route::get('/attendance/view', [AttendanceController::class, 'GetAttendanceByClass']);
    Route::get('/attendance/sheet', [AttendanceController::class, 'GetAttendanceSheet']);
    Route::get('/attendance/summary', [AttendanceController::class, 'GetAttendanceSummary']);

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::delete('/audit-logs/{id}', [AuditLogController::class, 'destroy']);
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});


Route::middleware(['auth:sanctum', 'role:admin,teacher'])->group(function () {
    Route::post('/first-term-exam', [Examination::class, 'FirstTermResult']);
    Route::post('/mock-exam', [Examination::class, 'MockResult']);


    Route::patch('/result/{session}/{class}/{exam}', [Examination::class, 'BulkUpdateResultStatus']);
    Route::patch('/release-single-report-card/{id}', [Examination::class, 'ReleaseSingleReportCard']);

    Route::get('/students/{classes}', [Profiles::class, 'GetStudents']);

    Route::post('/midterm-result', [Examination::class, 'MidTermResult']);
    Route::patch('/midterm-result', [Examination::class, 'UpdateMidTermResult']);

    Route::post('/continuous-assessment', [Examination::class, 'ContinousAssessment']);
    Route::get('/continuous-assessment', [Examination::class, 'FetchContinousAssessment']);

    Route::post('/junior-mock-exam', [Examination::class, 'JuniorMockResult']);
    Route::post('/second-term-exam', [Examination::class, 'SecondTermResult']);
    Route::post('/third-term-exam', [Examination::class, 'ThirdTermResult']);


    Route::patch('/first-term-result', [Examination::class, 'UpdateFirstTermResult']);
    Route::patch('/mock-result', [Examination::class, 'UpdateMockResult']);
    Route::patch('/second-term-result', [Examination::class, 'UpdateSecondTermResult']);
    Route::patch('/third-term-result', [Examination::class, 'UpdateThirdTermResult']);

    Route::patch('/result/{id}', [Examination::class, 'UpdateResultStatus']);
    Route::delete('/delete-result/{id}/{exam}/{session}', [Examination::class, 'DeleteResult']);
    Route::post('/comment', [Examination::class, 'CreateComment']);

    Route::patch('/teachers-comment', [Examination::class, 'UpdateTeachersComment']);
    // Comment

    // Appraisal
    Route::post('/appraisal', [Examination::class, 'CreateAppraisal']);
    // Appraisal

    // Dashboard
    Route::get('/dashboard', [Dashboard::class, 'Index']);
    // Dashboard
});


Route::middleware(['auth:sanctum'])->group(
    function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::delete('/auth/token', [MobileAuth::class, 'destroy']);

        // Profile
        Route::get('/student/{id}', [Profiles::class, 'GetStudentById']);
        Route::patch('/update-my-profile', [Profiles::class, 'UpdateMyProfile']);
        Route::patch('/change-my-password', [Profiles::class, 'ChangeMyPassword']);

        // Profile

        //Schedules Routes
        Route::get('/events', [Schedule::class, 'Events']);
        // Schedules Route

        // Academics

        Route::get('/sections', [Academics::class, 'GetSection']);

        Route::get('/classes', [Academics::class, 'GetClass']);
        Route::get('/classes/{id}', [Academics::class, 'GetClassById']);


        Route::get('/subjects', [Academics::class, 'GetSubject']);
        Route::get('/section-subjects/{section}', [Academics::class, 'GetSubjectBySection']);
        Route::get('/subjects/{id}', [Academics::class, 'GetSubjectById']);


        Route::get('/term', [Academics::class, 'GetTerm']);

        Route::get('/session', [Academics::class, 'GetSession']);
        Route::get('/session/{id}', [Academics::class, 'GetSessionById']);

        // Academics


        // Examination
        Route::get('/exam', [Examination::class, 'GetExam']);
        Route::get('/exam/{id}', [Examination::class, 'GetExamById']);

        Route::get('/get-result/{session}/{class}/{exam}/{subject}', [Examination::class, 'FetchResultToEdit']);
        Route::get('/get-old-result-for-second-term-report/{class}/{exam}/{subject}/{session?}', [Examination::class, 'FetchFirstTermResultForSecondReport']);
        Route::get('/get-ca/{class}/{exam}/{subject}', [Examination::class, 'FetchCAForReport']);
        Route::get('/get-old-result-for-third-term-report/{class}/{exam}/{subject}/{session?}', [Examination::class, 'FetchSecondTermResultForThirdReport']);
        Route::get('/get-result/{session}/{class}/{exam}', [Examination::class, 'FetchResultData']);

        Route::get('/fetch-report-card', [Examination::class, 'GetReportCard']);
        Route::get('/fetch-result/{stuId}', [Examination::class, 'FetchResult']);

        // Examination

        // Comment

        Route::get('/get-principals-comments/{session}/{class}/{exam}', [Examination::class, 'FetchPrincipalsCommentToEdit']);
        Route::get('/get-teachers-comments/{session}/{class}/{exam}', [Examination::class, 'FetchTeachersCommentToEdit']);
        Route::get('/get-principals-comments/{session}/{class}/{exam}', [Examination::class, 'FetchPrincipalsCommentToEdit']);
        Route::get('/get-appraisals/{session}/{class}/{exam}', [Examination::class, 'FetchAppraisals']);
    }
);

// Auth
Route::post('/auth/token', [MobileAuth::class, 'store']);
Route::post('/register', [MobileAuth::class, 'register']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});


// Student Application
Route::post('/submit-application', [StudentApplication::class, 'SubmitApplication']);
Route::get('/application/{app_num}', [StudentApplication::class, 'FetchApplication']);
Route::patch('/app-student-passport/{app_num}', [StudentApplication::class, 'UploadStudentPassport']);
// --path=database/migrations/2024_02_16_192835_create_appliactions_table.php
