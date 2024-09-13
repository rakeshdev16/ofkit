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
use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::get('/check-session', function () {
    return response()->json(['isAuthenticated' => auth()->check()]);
});
Route::controller(UserController::class)->group(function () {
    Route::post('set-locale', 'setLocale')->name('set.locale');
});

Route::middleware(['auth', 'lang'])->group(function () {
    Route::get('/', fn() => redirect()->route('children.index'))->name('dashboard');
    Route::get('therapy-schedule', fn() => view('dashboard.index'))->name('therapy-schedule.index');
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
        Route::get('documents-approvals/{childId}', 'documentsAndApprovals')->name('documents-approvals.get');
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

Route::get('migrate-refresh', function (\Illuminate\Http\Request $request) {
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
