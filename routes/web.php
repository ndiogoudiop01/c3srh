<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FeuillesAbsenceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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

/*Route::get('/', function () {
    return view('welcome');
});
*/
/** for side bar menu active */
function set_active( $route ) {
    if( is_array( $route ) ){

        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
    Route::get('home',function()
    {
        return view('home');
    });
});


Auth::routes();
// ----------------------------- main dashboard ------------------------------//
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
});
// ----------------------------- forget password ----------------------------//
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'getEmail')->name('forget-password');
    Route::post('forget-password', 'postEmail')->name('forget-password');    
});

// -----------------------------login----------------------------------------//
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});
// ------------------------------ register ---------------------------------//
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register','storeUser')->name('register');    
});
// ----------------------------- user profile ------------------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('profile_user', 'profile')->middleware('auth')->name('profile_user');
    Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');    
});
// ----------------------------- user userManagement -----------------------//
Route::controller(UserManagementController::class)->group(function () {
    Route::get('userManagement', 'index')->middleware('auth')->name('userManagement');
    Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
    Route::post('search/user/list', 'searchUser')->name('search/user/list');
    Route::post('update', 'update')->name('update');
    Route::post('user/delete', 'delete')->middleware('auth')->name('user/delete');
    Route::get('activity/log', 'activityLog')->middleware('auth')->name('activity/log');
    Route::get('activity/login/logout', 'activityLogInLogOut')->middleware('auth')->name('activity/login/logout');    
});
// -----------------------------settings----------------------------------------//
Route::controller(SettingController::class)->group(function () {
    Route::get('company/settings/page', 'companySettings')->middleware('auth')->name('company/settings/page');
    Route::get('roles/permissions/page', 'rolesPermissions')->middleware('auth')->name('roles/permissions/page');
    Route::post('roles/permissions/save', 'addRecord')->middleware('auth')->name('roles/permissions/save');
    Route::post('roles/permissions/update', 'editRolesPermissions')->middleware('auth')->name('roles/permissions/update');
    Route::post('roles/permissions/delete', 'deleteRolesPermissions')->middleware('auth')->name('roles/permissions/delete');
});
// ----------------------------- profile employee ------------------------------//
Route::controller(EmployeController::class)->group(function () {
    Route::get('employee/profile/{matricule}', 'profileEmployee')->middleware('auth');
});

// ----------------------------- form employee ------------------------------//
Route::controller(EmployeController::class)->group(function () {
    Route::get('all/employee/card', 'cardAllEmploye')->middleware('auth')->name('all/employee/card');
    Route::get('all/employee/list', 'listAllEmploye')->middleware('auth')->name('all/employee/list');
    Route::post('all/employee/save', 'addAttendanceLogs')->middleware('auth')->name('all/employee/save');
    Route::post('all/employee/saveEmp', 'addEmploye')->middleware('auth')->name('all/employee/saveEmp');
    Route::get('all/employee/view/edit/{id}', 'showRecord')->middleware('auth');
    Route::post('all/employee/update', 'updateRecord')->middleware('auth')->name('all/employee/update');
    Route::get('all/employee/delete/{id}', 'destroyEmp')->middleware('auth');
    Route::post('all/employee/search', 'employeeSearch')->name('all/employee/search');
    Route::post('all/employee/list/search', 'employeeListSearch')->name('all/employee/list/search');

    Route::get('form/departments/page', 'index')->middleware('auth')->name('form/departments/page');    
    Route::post('form/departments/save', 'saveRecordDepartment')->middleware('auth')->name('form/departments/save');    
    Route::post('form/department/update', 'updateRecordDepartment')->middleware('auth')->name('form/department/update');    
    Route::post('form/department/delete', 'deleteRecordDepartment')->middleware('auth')->name('form/department/delete');  
    
    Route::get('form/designations/page', 'designationsIndex')->middleware('auth')->name('form/designations/page');    
    Route::post('form/designations/save', 'saveRecordDesignations')->middleware('auth')->name('form/designations/save');    
    Route::post('form/designations/update', 'updateRecordDesignations')->middleware('auth')->name('form/designations/update');    
    Route::post('form/designations/delete', 'deleteRecordDesignations')->middleware('auth')->name('form/designations/delete');
    
    Route::get('form/timesheet/page', 'timeSheetIndex')->middleware('auth')->name('form/timesheet/page');    
    Route::post('form/timesheet/save', 'saveRecordTimeSheets')->middleware('auth')->name('form/timesheet/save');    
    Route::post('form/timesheet/update', 'updateRecordTimeSheets')->middleware('auth')->name('form/timesheet/update');    
    Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->middleware('auth')->name('form/timesheet/delete');
    
    Route::get('form/overtime/page', 'overTimeIndex')->middleware('auth')->name('form/overtime/page');    
    Route::post('form/overtime/save', 'saveRecordOverTime')->middleware('auth')->name('form/overtime/save');    
    Route::post('form/overtime/update', 'updateRecordOverTime')->middleware('auth')->name('form/overtime/update');    
    Route::post('form/overtime/delete', 'deleteRecordOverTime')->middleware('auth')->name('form/overtime/delete');  
});
// ----------------------------- form holiday ------------------------------//
Route::controller(HolidayController::class)->group(function () {
    Route::get('form/holidays/new', 'holiday')->middleware('auth')->name('form/holidays/new');
    Route::post('form/holidays/save', 'saveRecord')->middleware('auth')->name('form/holidays/save');
    Route::post('form/holidays/update', 'updateRecord')->middleware('auth')->name('form/holidays/update');    
});
// ----------------------------- form leaves ------------------------------//
Route::controller(FeuillesAbsenceController::class)->group(function () {
    Route::get('form/leaves/new', 'liste_absences')->middleware('auth')->name('form/leaves/new');
    Route::post('form/leave/search', 'absenceSearch')->name('form/leave/search');
    Route::get('form/leavesemployee/new', 'leavesEmployee')->middleware('auth')->name('form/leavesemployee/new');
    Route::post('form/leaves/save', 'saveRecord')->middleware('auth')->name('form/leaves/save');
    Route::post('form/leaves/edit', 'editRecordLeave')->middleware('auth')->name('form/leaves/edit');
    Route::post('form/leaves/edit/delete','deleteLeave')->middleware('auth')->name('form/leaves/edit/delete');    
});
// ----------------------------- form attendance  ------------------------------//
Route::controller(LeavesController::class)->group(function () {
    Route::get('form/leavesettings/page', 'leaveSettings')->middleware('auth')->name('form/leavesettings/page');
    Route::get('attendance/page', 'attendanceIndex')->middleware('auth')->name('attendance/page');
    Route::get('attendance/employee/page', 'AttendanceEmployee')->middleware('auth')->name('attendance/employee/page');
    Route::get('form/shiftscheduling/page', 'shiftScheduLing')->middleware('auth')->name('form/shiftscheduling/page');
    Route::get('form/shiftlist/page', 'shiftList')->middleware('auth')->name('form/shiftlist/page');    
});
// ----------------------------- job ------------------------------//
Route::controller(JobController::class)->group(function () {
    Route::get('form/job/list','jobList')->name('form/job/list');
    Route::get('form/job/view/{id}', 'jobView');
    Route::get('user/dashboard/index', 'userDashboard')->middleware('auth')->name('user/dashboard/index');    
    Route::get('jobs/dashboard/index', 'jobsDashboard')->middleware('auth')->name('jobs/dashboard/index');    
    Route::get('user/dashboard/all', 'userDashboardAll')->middleware('auth')->name('user/dashboard/all');    
    Route::get('user/dashboard/save', 'userDashboardSave')->middleware('auth')->name('user/dashboard/save');
    Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->middleware('auth')->name('user/dashboard/applied/jobs');
    Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->middleware('auth')->name('user/dashboard/interviewing');
    Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->middleware('auth')->name('user/dashboard/offered/jobs');
    Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->middleware('auth')->name('user/dashboard/visited/jobs');
    Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->middleware('auth')->name('user/dashboard/archived/jobs');
    Route::get('jobs', 'Jobs')->middleware('auth')->name('jobs');
    Route::get('job/applicants/{job_title}', 'jobApplicants')->middleware('auth');
    Route::get('job/details/{id}', 'jobDetails')->middleware('auth');
    Route::get('cv/download/{id}', 'downloadCV')->middleware('auth');
    
    Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
    Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
    Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');

    Route::get('page/manage/resumes', 'manageResumesIndex')->middleware('auth')->name('page/manage/resumes');
    Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->middleware('auth')->name('page/shortlist/candidates');
    Route::get('page/interview/questions', 'interviewQuestionsIndex')->middleware('auth')->name('page/interview/questions');
    Route::get('page/offer/approvals', 'offerApprovalsIndex')->middleware('auth')->name('page/offer/approvals');
    Route::get('page/experience/level', 'experienceLevelIndex')->middleware('auth')->name('page/experience/level');
    Route::get('page/candidates', 'candidatesIndex')->middleware('auth')->name('page/candidates');
    Route::get('page/schedule/timing', 'scheduleTimingIndex')->middleware('auth')->name('page/schedule/timing');

});
// ----------------------------- training type  ------------------------------//
Route::controller(PersonalInformationController::class)->group(function () {
    Route::post('user/information/save', 'saveRecord')->middleware('auth')->name('user/information/save');
});

