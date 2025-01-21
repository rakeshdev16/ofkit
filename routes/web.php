<?php

use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\ChildrenTableController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\FrameworkTableController;
use App\Http\Controllers\InterventionTableController;
use App\Http\Controllers\KindergartenController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffTableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ChildrenScheduleController;
use App\Http\Controllers\TherapyScheduleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use \Illuminate\Http\Request;
use App\Models\Children;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// $lang = App\Models\Setting::where('key', 'environment')->pluck('value')->First() == 'local' ? 'en' : 'hb';
// App::setLocale($lang);
Route::middleware(['lang'])->group(function () {
    Auth::routes();
    Route::controller(OtpController::class)->group(function () {
        Route::get('otp-verify', 'showVerifyForm')->name('otp.verify');
        Route::post('otp-verify', 'verifyOtp')->name('otp.verify.submit');
        Route::get('resend-otp', 'resendOtp')->name('resend.otp');
    });
});
Route::get('/page-expired', fn() => view('errors.419'))->name('page.expired');
Route::get('/check-session', function () {
    if (auth()->check() && Auth::user()->last_activity_at) {
        $lastActivityTime = Carbon::parse(Auth::user()->last_activity_at);
        $currentTime = Carbon::now();
        if ($lastActivityTime->diffInMinutes($currentTime) >= env('SESSION_EXPIRE_IN')) {
            Auth::logout();
            return response()->json(['isAuthenticated' => false]);
        } else {
            return response()->json(['isAuthenticated' => true]);
        }
    }
});

Route::controller(Controller::class)->group(function () {
    Route::post('active-inactive', 'activeInactive')->name('activeInactive.records');
});

Route::middleware(['auth', 'lang', 'last.activity', 'disableBackBtnAfterLogout'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('set-locale', 'setLocale')->name('set.locale');
    });
    // Route::get('/', fn() => redirect()->route('children.index'))->name('dashboard');
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('staff', StaffController::class);
    Route::resource('cluster', ClusterController::class);
    Route::resource('kindergarten', KindergartenController::class);
    Route::resource('children', ChildrenController::class);
    Route::resource('staff-table', StaffTableController::class);
    Route::resource('framework-table', FrameworkTableController::class);
    Route::resource('children-table', ChildrenTableController::class);
    Route::resource('intervention', InterventionTableController::class);

    Route::controller(KindergartenController::class)->group(function () {
        Route::get('get-cluster-manager', 'getClusterManager')->name('cluster-manager.name');
    });
    Route::controller(FrameworkTableController::class)->group(function () {
        Route::get('framework-table-tab', 'frameWorkTableTab')->name('framework-table.tab');
    });
    Route::controller(StaffController::class)->group(function () {
        Route::post('upload-staff-profile', 'uploadStaffProfile')->name('uploadStaffProfile');
        Route::get('selected-kindergarten', 'selectedKindergarten')->name('selected.kindergarten');
        Route::post('delete-document', 'deleteDocument')->name('document.delete');
        Route::post('delete-staff-kindergarten', 'deleteStaffKindergarten')->name('deleteStaffKindergarten');
        Route::get('send-message', 'sendMessage')->name('sendMessage');
        Route::get('schedule-time-row', 'scheduleTimeRow')->name('scheduleTimeRow');
        Route::post('validate-staff-field', 'validateField')->name('validate.staff.field');
    });
    Route::controller(ChildrenController::class)->group(function () {
        Route::post('upload-children-profile', 'uploadProfile')->name('uploadChildrenProfile');
        Route::post('delete-children-profile', 'deleteProfile')->name('deleteChildrenProfile');
        Route::get('children-documentations/{id}', 'documentations')->name('children-documentations.get');
        Route::get('children-documentation-detail/{childId}/{id}/{mailchildId?}', 'documentationDetail')->name('children-documentation.show');
        Route::get('children-documentation/{type}/{childId}/{id?}', 'documentation')->name('children-documentation.get');
        Route::post('children-documentation/{type}/{id}', 'saveDocumentation')->name('children-documentation.store');
        Route::post('delete-children-medicine', 'deleteChildrenMedicine')->name('childrenMedicine.delete');
        Route::get('get-kindergarten-manager', 'getKindergartenManager')->name('kindergarten-manager.get');
        Route::get('documents-approvals/{childId}/{docId?}', 'documentsAndApprovals')->name('documents-approvals.get');
        Route::get('documents-approvals-create/{childId}', 'documentsAndApprovalsCreate')->name('documents-approvals.create');
        Route::get('documents-approvals-edit/{docId}', 'documentsAndApprovalsEdit')->name('documents-approvals.edit');
        Route::post('documents-approvals', 'saveDocumentsAndApprovals')->name('documents-approvals.post');
        Route::delete('documents-approvals/{id}', 'deleteDocumentsAndApprovals')->name('documents-approvals.delete');
        Route::delete('documents/{id}', 'deleteDocuments')->name('documents.delete');
    });
    Route::controller(StaffTableController::class)->group(function () {
        Route::get('staff-table-tab', 'staffTableTab')->name('staff-table.tab');
    });
    Route::controller(ChildrenTableController::class)->group(function () {
        Route::get('children-table-tab', 'childrenTableTab')->name('children-table.tab');
    });
    Route::controller(InterventionTableController::class)->group(function () {
        Route::get('intervention-tab', 'interventionTableTab')->name('intervention.tab');
    });
    Route::controller(ScheduleController::class)->group(function (){
        Route::get('schedule', 'index')->name('schedule.index');
        Route::post('schedule', 'store')->name('schedule.store');
        Route::get('schedule/calendar', 'calendar')->name('schedule.calendar');
        Route::get('schedule/create','create')->name('schedule.create');
        Route::post('schedule/status', 'update')->name('schedule.update');
        Route::post('schedule/delete', 'delete')->name('schedule.delete');
        Route::post('schedule/filter-form-data', 'filterFormData')->name('schedule.filter-form-data');
        Route::post('schedule/time-slot', 'checkTimeSlot')->name('schedule.time-slot');
        Route::get('schedule/hour-summary', 'hourSummary')->name('schedule.hour-summary');
        Route::get('schedule/export', 'export')->name('schedule.export');

    });

    Route::controller(ChildrenScheduleController::class)->group(function (){
        Route::get('children-schedule/{id}', 'index')->name('children-schedule.index');
        Route::get('children-schedule', 'calendar')->name('children-schedule.calendar');
    });

    Route::controller(TherapyScheduleController::class)->group(function (){
        Route::get('therapy-schedule', 'index')->name('therapy-schedule.index');
        Route::get('therapy-schedule-calendar', 'calendar')->name('therapy-schedule.calendar');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::get('edit-profile', 'edit')->name('profile.edit');
        Route::post('profile', 'update')->name('profile.update');
        Route::get('change-password', 'changePasswordView')->name('change-password.index');
        Route::post('change-password', 'changePassword')->name('change-password.update');
        Route::post('upload-user-profile', 'uploadUserProfile')->name('userProfile.update');
        Route::post('delete-user-photo', 'deletePhoto')->name('delete.user-photo');
        Route::post('set-previous-route', 'setPreviousRoute')->name('setPreviousRoute');
    });
});

Route::get('seed',function(){ \Artisan::call("db:seed"); });
Route::get('migrate',function(){ \Artisan::call("migrate"); });
Route::get('migrate-fresh',function(){ \Artisan::call("migrate:fresh"); });

Route::get('migrate-refresh', function (Request $request) {
    $migrationName = $request->query('migration');
    if (!$migrationName) {
        return response()->json(['message' => 'Migration name is required.']);
    }
    $migrationPath = 'database/migrations/' . $migrationName . '.php';
    if (!file_exists(database_path('migrations/' . $migrationName . '.php'))) {
        return response()->json(['message' => 'Migration file does not exist.']);
    }
    Artisan::call('migrate:refresh', [
        '--path' => $migrationPath,
    ]);
    $output = Artisan::output();
    return response()->json([
        'message' => 'Migration refreshed successfully!',
        'output' => $output,
    ]);
});


Route::get('update-child-color', function (Request $request) {
    foreach (Children::get() as $children) {
        $backgroundColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $rgb = sscanf($backgroundColor, "#%02x%02x%02x");
        $luminance = (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;
        $textColor = $luminance > 0.5 ? '#000000' : '#FFFFFF';
        $children->update([
            'color' => json_encode([
                "background-color: $backgroundColor",
                "color: $textColor"
            ])
        ]);
    }
});
