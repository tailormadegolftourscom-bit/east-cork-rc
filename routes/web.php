<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SupporterController as AdminSupporterController;
use App\Http\Controllers\Admin\CommitteeController as AdminCommitteeController;
use App\Http\Controllers\SchoolRegistrationRequestController;
use App\Http\Controllers\Admin\SchoolRegistrationRequestController as AdminSchoolRegistrationRequestController;
use App\Http\Controllers\SchoolPortalController;
use App\Http\Controllers\SchoolClassPortalController;
use App\Http\Controllers\PublicSchoolController;
use App\Http\Controllers\ParentSignUpController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\CoParentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriberController;


Route::middleware(['auth', 'school'])->prefix('school')->group(function () {
    Route::get('/', [SchoolPortalController::class, 'dashboard'])->name('school.dashboard');
    Route::get('/profile', [SchoolPortalController::class, 'edit'])->name('school.profile.edit');
    Route::put('/profile', [SchoolPortalController::class, 'update'])->name('school.profile.update');

    Route::get('/classes', [SchoolClassPortalController::class, 'index'])->name('school.classes.index');
    Route::get('/classes/create', [SchoolClassPortalController::class, 'create'])->name('school.classes.create');
    Route::post('/classes', [SchoolClassPortalController::class, 'store'])->name('school.classes.store');
    Route::get('/classes/{schoolClass}/edit', [SchoolClassPortalController::class, 'edit'])->name('school.classes.edit');
    Route::put('/classes/{schoolClass}', [SchoolClassPortalController::class, 'update'])->name('school.classes.update');
    Route::post('/classes/confirm', [SchoolClassPortalController::class, 'confirm'])->name('school.classes.confirm');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/schools', [SchoolController::class, 'index'])->name('admin.schools.index');
    Route::get('/schools/create', [SchoolController::class, 'create'])->name('admin.schools.create');
    Route::post('/schools', [SchoolController::class, 'store'])->name('admin.schools.store');

    Route::get('/schools/{school}/edit', [SchoolController::class, 'edit'])->name('admin.schools.edit');
    Route::put('/schools/{school}', [SchoolController::class, 'update'])->name('admin.schools.update');
    Route::delete('/schools/{school}', [SchoolController::class, 'destroy'])->name('admin.schools.destroy');


    Route::get('/schools/{school}/classes', [SchoolClassController::class, 'index'])->name('admin.schools.classes.index');
    Route::get('/schools/{school}/classes/create', [SchoolClassController::class, 'create'])->name('admin.schools.classes.create');
    Route::post('/schools/{school}/classes', [SchoolClassController::class, 'store'])->name('admin.schools.classes.store');
    Route::get('/schools/{school}/classes/{schoolClass}/edit', [SchoolClassController::class, 'edit'])
        ->name('admin.schools.classes.edit');

    Route::put('/schools/{school}/classes/{schoolClass}', [SchoolClassController::class, 'update'])
        ->name('admin.schools.classes.update');

    Route::delete('/schools/{school}/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])
        ->name('admin.schools.classes.destroy');

    Route::get('/school-requests', [AdminSchoolRegistrationRequestController::class, 'index'])
        ->name('admin.school-requests.index');

    Route::get('/school-requests/{registrationRequest}', [AdminSchoolRegistrationRequestController::class, 'show'])
        ->name('admin.school-requests.show');

    Route::patch('/school-requests/{registrationRequest}/status', [AdminSchoolRegistrationRequestController::class, 'updateStatus'])
        ->name('admin.school-requests.update-status');

    Route::post('/school-requests/{registrationRequest}/create-school', [AdminSchoolRegistrationRequestController::class, 'createSchool'])
        ->name('admin.school-requests.create-school');

    Route::post('/schools/{school}/send-invite', [SchoolController::class, 'sendInvite'])
        ->name('admin.schools.send-invite');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{user}/reactivate', [AdminUserController::class, 'reactivate'])->name('admin.users.reactivate');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/supporters', [AdminSupporterController::class, 'index'])->name('admin.supporters.index');
    Route::get('/supporters/{person}', [AdminSupporterController::class, 'show'])->name('admin.supporters.show');
    Route::patch('/children/{child}/audit-status', [AdminSupporterController::class, 'updateChildAuditStatus'])
        ->name('admin.children.update-audit-status');
    Route::delete('/children/{child}', [AdminSupporterController::class, 'destroyChild'])
        ->name('admin.children.destroy');

    Route::get('/committees', [AdminCommitteeController::class, 'index'])->name('admin.committees.index');
});

Route::get('/add-my-school', [SchoolRegistrationRequestController::class, 'create'])
    ->name('school-registration.create');

Route::post('/add-my-school', [SchoolRegistrationRequestController::class, 'store'])
    ->name('school-registration.store');

Route::get('/schools', [PublicSchoolController::class, 'index'])->name('schools.index');

Route::view('/for-kids', 'public.for-kids')->name('for-kids');
Route::get('/parents', [PublicSchoolController::class, 'forParents'])->name('parents');
Route::view('/the-issue', 'public.the-issue')->name('the-issue');
Route::view('/activities', 'public.activities')->name('activities');
Route::view('/resources', 'public.resources')->name('resources');
Route::view('/faqs', 'public.faqs')->name('faqs');
Route::view('/about', 'public.about')->name('about');

Route::get('/updates', [SubscriberController::class, 'create'])->name('updates.create');
Route::post('/updates', [SubscriberController::class, 'store'])->name('updates.store');


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/parent/start');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified', 'parent'])->group(function () {
    Route::get('/parent/start', [ParentSignUpController::class, 'edit'])->name('parent.start');
    Route::post('/parent/start', [ParentSignUpController::class, 'update'])->name('parent.start.update');
    Route::get('/parent/welcome', [ParentSignUpController::class, 'welcome'])->name('parent.welcome');
});

Route::middleware(['auth', 'verified', 'parent', 'parent.onboarded'])->group(function () {
    Route::get('/parent/dashboard', [ParentSignUpController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/parent/profile', [ParentSignUpController::class, 'editProfile'])->name('parent.profile.edit');
    Route::put('/parent/profile', [ParentSignUpController::class, 'updateProfile'])->name('parent.profile.update');

    Route::get('/parent/children/create', [ChildController::class, 'create'])->name('parent.children.create');
    Route::post('/parent/children', [ChildController::class, 'store'])->name('parent.children.store');
    Route::get('/parent/children/{child}/edit', [ChildController::class, 'edit'])->name('parent.children.edit');
    Route::put('/parent/children/{child}', [ChildController::class, 'update'])->name('parent.children.update');
    Route::delete('/parent/children/{child}', [ChildController::class, 'destroy'])->name('parent.children.destroy');

    Route::get('/parent/co-parent/invite', [CoParentController::class, 'create'])->name('parent.co-parent.create');
    Route::post('/parent/co-parent/invite', [CoParentController::class, 'store'])->name('parent.co-parent.store');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
