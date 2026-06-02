<?php

use App\Http\Controllers\AcademicSettingController;
use App\Http\Controllers\AssignedTeacherController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamRuleController;
use App\Http\Controllers\GradeRuleController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\MarkImportController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\RoutineImportController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Finance\FinanceDashboardController;
use App\Http\Controllers\Finance\FeeTypeController;
use App\Http\Controllers\Finance\FeeStructureController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\DiscountController;
use App\Http\Controllers\Finance\ScholarshipController;
use App\Http\Controllers\Finance\ExpenseController;
use App\Http\Controllers\Finance\VendorController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::prefix('school')->name('school.')->group(function () {
        Route::post('session/create', [SchoolSessionController::class, 'store'])->name('session.store');
        Route::post('session/browse', [SchoolSessionController::class, 'browse'])->name('session.browse');

        Route::post('semester/create', [SemesterController::class, 'store'])->name('semester.create');
        Route::post('final-marks-submission-status/update', [AcademicSettingController::class, 'updateFinalMarksSubmissionStatus'])->name('final.marks.submission.status.update');
        Route::post('active-semester/update', [AcademicSettingController::class, 'updateActiveSemester'])->name(
            'active.semester.update'
        );
        Route::post('active-session/update', [AcademicSettingController::class, 'updateActiveSession'])->name(
            'active.session.update'
        );

        Route::post('attendance/type/update', [AcademicSettingController::class, 'updateAttendanceType'])->name('attendance.type.update');
        Route::post('general/update', [AcademicSettingController::class, 'updateGeneralSettings'])->name(
            'general.settings.update'
        );

        // Class
        Route::post('class/create', [SchoolClassController::class, 'store'])->name('class.create');
        Route::post('class/update', [SchoolClassController::class, 'update'])->name('class.update');

        // Sections
        Route::post('section/create', [SectionController::class, 'store'])->name('section.create');
        Route::post('section/update', [SectionController::class, 'update'])->name('section.update');

        // Courses
        Route::post('course/create', [CourseController::class, 'store'])->name('course.create');
        Route::post('course/update', [CourseController::class, 'update'])->name('course.update');

        // Teacher
        Route::post('teacher/create', [UserController::class, 'storeTeacher'])->name('teacher.create');
        Route::post('teacher/update', [UserController::class, 'updateTeacher'])->name('teacher.update');
        Route::post('teacher/assign', [AssignedTeacherController::class, 'store'])->name('teacher.assign');

        // Student
        Route::post('student/create', [UserController::class, 'storeStudent'])->name('student.create');
        Route::post('student/update', [UserController::class, 'updateStudent'])->name('student.update');
    });


    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Attendance
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendances/view', [AttendanceController::class, 'show'])->name('attendance.list.show');
    Route::get('/attendances/take', [AttendanceController::class, 'create'])->name('attendance.create.show');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');

    // Classes and sections
    Route::get('/classes', [SchoolClassController::class, 'index']);
    Route::get('/class/edit/{id}', [SchoolClassController::class, 'edit'])->name('class.edit');
    Route::get('/sections', [SectionController::class, 'getByClassId'])->name('get.sections.courses.by.classId');
    Route::get('/section/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');

    // Teachers
    Route::get('/teachers/add', function () {
        return view('teachers.add');
    })->name('teacher.create.show');
    Route::get('/teachers/edit/{id}', [UserController::class, 'editTeacher'])->name('teacher.edit.show');
    Route::get('/teachers/view/list', [UserController::class, 'getTeacherList'])->name('teacher.list.show');
    Route::get('/ajax/courses-by-class-semester', [UserController::class, 'getCoursesByClassAndSemester'])->name('ajax.courses.by.class.semester');
    Route::get('/teachers/view/profile/{id}', [UserController::class, 'showTeacherProfile'])->name('teacher.profile.show');

    //Students
    Route::get('/students/add', [UserController::class, 'createStudent'])->name('student.create.show');
    Route::get('/students/import/template', [StudentImportController::class, 'downloadTemplate'])->name(
        'student.import.template'
    );
    Route::post('/students/import', [StudentImportController::class, 'import'])->name('student.import');
    Route::get('/students/edit/{id}', [UserController::class, 'editStudent'])->name('student.edit.show');
    Route::get('/students/view/list', [UserController::class, 'getStudentList'])->name('student.list.show');
    Route::get('/students/view/profile/{id}', [UserController::class, 'showStudentProfile'])->name('student.profile.show');
    Route::get('/students/view/attendance/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('student.attendance.show');

    // Marks
    Route::get('/marks/create', [MarkController::class, 'create'])->name('course.mark.create');
    Route::post('/marks/store', [MarkController::class, 'store'])->name('course.mark.store');
    Route::get('/marks/results', [MarkController::class, 'index'])->name('course.mark.list.show');
    Route::get('/marks/template/download', [MarkImportController::class, 'downloadTemplate'])->name('course.mark.template.download');
    Route::post('/marks/import', [MarkImportController::class, 'import'])->name('course.mark.import');
    // Route::get('/marks/view', function () {
    //     return view('marks.view');
    // });
    Route::get('/marks/view', [MarkController::class, 'showCourseMark'])->name('course.mark.show');
    Route::get('/marks/final/submit', [MarkController::class, 'showFinalMark'])->name('course.final.mark.submit.show');
    Route::post('/marks/final/submit', [MarkController::class, 'storeFinalMark'])->name('course.final.mark.submit.store');

    // Exams
    Route::get('/exams/view', [ExamController::class, 'index'])->name('exam.list.show');
    // Route::get('/exams/view/history', function () {
    //     return view('exams.history');
    // });
    Route::post('/exams/create', [ExamController::class, 'store'])->name('exam.create');
    // Route::post('/exams/delete', [ExamController::class, 'delete'])->name('exam.delete');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exam.create.show');
    Route::get('/exams/add-rule', [ExamRuleController::class, 'create'])->name('exam.rule.create');
    Route::post('/exams/add-rule', [ExamRuleController::class, 'store'])->name('exam.rule.store');
    Route::get('/exams/edit-rule', [ExamRuleController::class, 'edit'])->name('exam.rule.edit');
    Route::post('/exams/edit-rule', [ExamRuleController::class, 'update'])->name('exam.rule.update');
    Route::get('/exams/view-rule', [ExamRuleController::class, 'index'])->name('exam.rule.show');
    Route::get('/exams/grade/create', [GradingSystemController::class, 'create'])->name('exam.grade.system.create');
    Route::post('/exams/grade/create', [GradingSystemController::class, 'store'])->name('exam.grade.system.store');
    Route::get('/exams/grade/view', [GradingSystemController::class, 'index'])->name('exam.grade.system.index');
    Route::get('/exams/grade/add-rule', [GradeRuleController::class, 'create'])->name('exam.grade.system.rule.create');
    Route::post('/exams/grade/add-rule', [GradeRuleController::class, 'store'])->name('exam.grade.system.rule.store');
    Route::get('/exams/grade/view-rules', [GradeRuleController::class, 'index'])->name('exam.grade.system.rule.show');
    Route::post('/exams/grade/delete-rule', [GradeRuleController::class, 'destroy'])->name('exam.grade.system.rule.delete');

    // Promotions
    Route::get('/promotions/index', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/promote', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions/promote', [PromotionController::class, 'store'])->name('promotions.store');

    // Academic settings
    Route::get('/academics/settings', [AcademicSettingController::class, 'index'])->name('academic.settings.show');

    // Calendar events
    Route::get('calendar-event', [EventController::class, 'index'])->name('events.show');
    Route::post('calendar-crud-ajax', [EventController::class, 'calendarEvents'])->name('events.crud');

    // Routines
    Route::get('/routine/import/template', [RoutineImportController::class, 'downloadTemplate'])->name(
        'routine.import.template'
    );
    Route::post('/routine/import', [RoutineImportController::class, 'import'])->name('routine.import');
    Route::get('/routine', [RoutineController::class, 'index'])->name('routine.index');
    Route::get('/routine/create', [RoutineController::class, 'create'])->name('section.routine.create');
    Route::get('/routine/view', [RoutineController::class, 'show'])->name('section.routine.show');
    Route::post('/routine/store', [RoutineController::class, 'store'])->name('section.routine.store');

    // Syllabus
    Route::get('/syllabus/create', [SyllabusController::class, 'create'])->name('class.syllabus.create');
    Route::post('/syllabus/create', [SyllabusController::class, 'store'])->name('syllabus.store');
    Route::get('/syllabus/index', [SyllabusController::class, 'index'])->name('course.syllabus.index');

    // Notices
    Route::get('/notice/create', [NoticeController::class, 'create'])->name('notice.create');
    Route::post('/notice/create', [NoticeController::class, 'store'])->name('notice.store');

    // Courses
    Route::get('courses/teacher/index', [AssignedTeacherController::class, 'getTeacherCourses'])->name('course.teacher.list.show');
    Route::get('courses/student/index/{student_id}', [CourseController::class, 'getStudentCourses'])->name('course.student.list.show');
    Route::get('course/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');

    // Assignment
    Route::get('courses/assignments/index', [AssignmentController::class, 'getCourseAssignments'])->name('assignment.list.show');
    Route::get('courses/assignments/create', [AssignmentController::class, 'create'])->name('assignment.create');
    Route::post('courses/assignments/create', [AssignmentController::class, 'store'])->name('assignment.store');

    // Update password
    Route::get('password/edit', [UpdatePasswordController::class, 'edit'])->name('password.change.edit');
    Route::post('password/edit', [UpdatePasswordController::class, 'update'])->name('password.change.update');

    // Finance
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/dashboard', [FinanceDashboardController::class, 'index'])->name('dashboard');

        Route::resource('fee-types', FeeTypeController::class)->except(['show', 'destroy']);
        Route::resource('fee-structures', FeeStructureController::class)->except(['destroy']);

        Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
        Route::post('invoices/{invoice}/apply-discount', [InvoiceController::class, 'applyDiscount'])->name(
            'invoices.apply-discount'
        );
        Route::post('invoices/{invoice}/remove-discount', [InvoiceController::class, 'removeDiscount'])->name(
            'invoices.remove-discount'
        );
        Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
        Route::resource('invoices', InvoiceController::class)->except(['destroy']);

        Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('payments/ajax/student-invoices', [PaymentController::class, 'getStudentInvoices'])->name(
            'payments.student-invoices'
        );
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);

        Route::resource('discounts', DiscountController::class)->except(['show', 'destroy']);
        Route::get('scholarships/assign', [ScholarshipController::class, 'assignCreate'])->name('scholarships.assign');
        Route::post('scholarships/assign', [ScholarshipController::class, 'assignStore'])->name(
            'scholarships.assign.store'
        );
        Route::resource('scholarships', ScholarshipController::class)->except(['show', 'destroy']);

        Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
        Route::resource('expenses', ExpenseController::class)->except(['destroy']);

        Route::resource('vendors', VendorController::class)->except(['show', 'destroy']);
        Route::post('budgets/{budget}/department', [BudgetController::class, 'addDepartment'])->name(
            'budgets.department.add'
        );
        Route::post('budgets/department/{department}/category', [BudgetController::class, 'addCategory'])->name(
            'budgets.category.add'
        );
        Route::resource('budgets', BudgetController::class)->except(['destroy']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/fee-collection', [ReportController::class, 'feeCollection'])->name(
            'reports.fee-collection'
        );
        Route::get('reports/outstanding', [ReportController::class, 'outstanding'])->name('reports.outstanding');
        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/expense', [ReportController::class, 'expenseReport'])->name('reports.expense');
        Route::get('reports/student-ledger', [ReportController::class, 'studentLedger'])->name(
            'reports.student-ledger'
        );
        Route::get('reports/audit-trail', [ReportController::class, 'auditTrail'])->name('reports.audit-trail');
    });
});
