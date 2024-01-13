<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::get('/', function () {
    return view('welcome');
});

/*
 * Admin ONLY
 * */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'admin'])
        ->name('admin.index');

    Route::get('/all/users', [AdminController::class, 'allUsers'])
        ->name('admin.allUsers');
    Route::get('/all/teachers', [AdminController::class, 'allTeachers'])
        ->name('admin.allTeachers');
    Route::get('/all/parents', [AdminController::class, 'allParents'])
        ->name('admin.allParents');
    Route::get('/all/students', [AdminController::class, 'allStudents'])
        ->name('admin.allStudents');

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users.index');

    Route::post('/all-users', [UserController::class, 'allUsers'])
        ->name('user.allUsers');

    Route::get('/teacher', [TeacherController::class, 'index'])
        ->name('teacher.index');
    Route::get('/parent', [ParentController::class, 'index'])
        ->name('parent.index');
    Route::get('/student', [StudentController::class, 'index'])
        ->name('student.index');

    /* ------------------------------------------------ */
    /*  ---------------- User Routes ------------------ */
    /*------------------------------------------------- */
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::patch('/user', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});


/*
 * Teachers ONLY
 * */

/*
 * Parents ONLY
 * */



/*
 * Authenticated Users
 * */
Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']) ->name('home.index');

    Route::get('/room', [App\Http\Controllers\RoomController::class, 'index']) ->name('room.index');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/scheduler', [SchedulerController::class, 'index']) ->name('scheduler.index');
    Route::get('/messages', [MessagesController::class, 'index']) ->name('messages.index');
    Route::get('/attendance', [AttendanceController::class, 'index']) ->name('attendance.index');

    /* ------------------------------------------------ */
    /*  ---------------- Instrument Routes ------------ */
    /*------------------------------------------------- */
    Route::get('/instruments', [InstrumentController::class, 'getAll'])->name('instruments.all');
    Route::post('/instrument', [InstrumentController::class, 'store'])->name('instrument.store');
    Route::patch('/instrument', [InstrumentController::class, 'update'])->name('instrument.update');
    Route::delete('/instrument/{id}', [InstrumentController::class, 'destroy'])->name('instrument.destroy');

    // Document routes
    Route::get('/doc', [DocsController::class, 'index'])->name('docs.index');
    Route::post('/save/doc', [DocsController::class, 'store'])->name('docs.store');
    Route::get('/doc/{id}', [DocsController::class, 'show'])->name('docs.show');
    Route::patch('/doc/{id}', [DocsController::class, 'update'])->name('docs.update');
    Route::delete('/doc/{id}', [DocsController::class, 'destroy'])->name('docs.destroy');
    // Get all docs for the user
    Route::get('/docs', [DocsController::class, 'getDocs'])->name('docs.getDocs');

    Route::post('/uploadFile', [DocsController::class, 'uploadFile'])->name('docs.uploadFile');
    Route::post('/fetchUrl', [DocsController::class, 'fetchUrl'])->name('docs.fetchUrl');
   

    /* ------------------------------------------------ */
    /*  ---------------- Profile Routes --------------- */
    /*------------------------------------------------- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ------------------------------------------------ */
    /*  ---------------- Menu Options --------------- */
    /*------------------------------------------------- */

    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings.index');

});

require __DIR__ . '/auth.php';
