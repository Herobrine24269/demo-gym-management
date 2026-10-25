<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MemberController as AdminMember;
use App\Http\Controllers\Admin\TrainerController as AdminTrainer;
use App\Http\Controllers\Admin\PlanController as AdminPlan;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\RenewalController as AdminRenewal;
use App\Http\Controllers\Admin\InactiveController as AdminInactive;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendance;
use App\Http\Controllers\Admin\PostController as AdminPost;
use App\Http\Controllers\Admin\DietPlanController as AdminDietPlan;
use App\Http\Controllers\Admin\AddonController as AdminAddon;
use App\Http\Controllers\Admin\ExpenseController as AdminExpense;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLog;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Admin\PTSessionController as AdminPT;
use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboard;
use App\Http\Controllers\Receptionist\AttendanceController as ReceptionistAttendance;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboard;
use App\Http\Controllers\Trainer\MemberController as TrainerMember;
use App\Http\Controllers\Trainer\WorkoutPlanController as TrainerWorkout;
use App\Http\Controllers\Trainer\PostController as TrainerPost;
use App\Http\Controllers\Trainer\ProfileController as TrainerProfile;
use App\Http\Controllers\Frontend\PostController as PublicPostController;
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\ProfileController as MemberProfile;
use App\Http\Controllers\Member\AttendanceController as MemberAttendance;

/* ============================================================
   PUBLIC ROUTES
   ============================================================ */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PublicPostController::class, 'index'])->name('posts.index');

use App\Http\Controllers\DietPlanController;
Route::get('/diet-plans', [DietPlanController::class, 'index'])->name('diet-plans.index');
Route::get('/diet-plans/{dietPlan}', [DietPlanController::class, 'show'])->name('diet-plans.show');
Route::get('/posts/{post:slug}', [PublicPostController::class, 'show'])->name('posts.show');

/* ── Auth ──────────────────────────────────────────────────── */
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/* ============================================================
   ADMIN ROUTES
   ============================================================ */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    /* Members */
    Route::resource('members', AdminMember::class);
    Route::get('members/{member}/payments', [AdminMember::class, 'payments'])->name('members.payments');
    Route::post('members/{member}/call-log', [AdminMember::class, 'callLog'])->name('members.call-log');

    /* Trainers */
    Route::resource('trainers', AdminTrainer::class);

    /* Plans */
    Route::resource('plans', AdminPlan::class);

    /* Payments */
    Route::get('payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPayment::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/refund', [AdminPayment::class, 'refund'])->name('payments.refund');

    /* Renewals */
    Route::get('renewals', [AdminRenewal::class, 'index'])->name('renewals.index');
    Route::post('renewals', [AdminRenewal::class, 'store'])->name('renewals.store');

    /* Inactive / Retention */
    Route::get('inactive', [AdminInactive::class, 'index'])->name('inactive.index');
    Route::post('inactive/{member}/follow-up', [AdminInactive::class, 'followUp'])->name('inactive.followup');
    Route::post('inactive/{inactiveCase}/resolve', [AdminInactive::class, 'resolve'])->name('inactive.resolve');

    /* Attendance (admin can correct) */
    Route::get('attendance', [AdminAttendance::class, 'index'])->name('attendance.index');
    Route::put('attendance/{attendance}/correct', [AdminAttendance::class, 'correct'])->name('attendance.correct');
    Route::delete('attendance/{attendance}', [AdminAttendance::class, 'destroy'])->name('attendance.destroy');

    /* PT Sessions */
    Route::resource('pt-sessions', AdminPT::class);

    /* Add-ons / Shop */
    Route::resource('addons', AdminAddon::class);

    /* Posts */
    Route::resource('posts', AdminPost::class);
    Route::post('posts/{post}/publish', [AdminPost::class, 'publish'])->name('posts.publish');

    /* Diet Plans */
    Route::resource('diet-plans', AdminDietPlan::class);

    /* Expenses */
    Route::get('expenses', [AdminExpense::class, 'index'])->name('expenses.index');
    Route::post('expenses', [AdminExpense::class, 'store'])->name('expenses.store');

    /* Reports */
    Route::get('reports', [AdminReport::class, 'index'])->name('reports.index');

    /* Audit Logs */
    Route::get('audit-logs', [AdminAuditLog::class, 'index'])->name('audit-logs.index');

    /* Settings */
    Route::get('settings', [AdminSetting::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSetting::class, 'update'])->name('settings.update');

    /* Admin Profile */
    Route::get('profile', [AdminProfile::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfile::class, 'update'])->name('profile.update');
});

/* ============================================================
   RECEPTIONIST ROUTES
   ============================================================ */
Route::prefix('receptionist')->name('receptionist.')->middleware(['auth', 'role:admin,receptionist'])->group(function () {
    Route::get('/dashboard', [ReceptionistDashboard::class, 'index'])->name('dashboard');

    Route::get('/attendance', [ReceptionistAttendance::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [ReceptionistAttendance::class, 'store'])->name('attendance.store');
    Route::get('/attendance/search', [ReceptionistAttendance::class, 'search'])->name('attendance.search');
    Route::delete('/attendance/{attendance}', [ReceptionistAttendance::class, 'destroy'])->name('attendance.destroy');
});

/* ============================================================
   TRAINER ROUTES
   ============================================================ */
Route::prefix('trainer')->name('trainer.')->middleware(['auth', 'role:admin,trainer'])->group(function () {
    Route::get('/dashboard', [TrainerDashboard::class, 'index'])->name('dashboard');
    Route::get('/members', [TrainerMember::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [TrainerMember::class, 'show'])->name('members.show');
    Route::post('/members/{member}/call-log', [TrainerMember::class, 'callLog'])->name('members.call-log');
    Route::resource('workout-plans', TrainerWorkout::class);
    Route::resource('posts', TrainerPost::class);
    Route::post('posts/{post}/publish', [TrainerPost::class, 'publish'])->name('posts.publish');
    /* Trainer Profile */
    Route::get('/profile', [TrainerProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [TrainerProfile::class, 'update'])->name('profile.update');
});

/* ============================================================
   MEMBER ROUTES
   ============================================================ */
Route::prefix('member')->name('member.')->middleware(['auth', 'role:member,admin'])->group(function () {
    Route::get('/dashboard', [MemberDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [MemberProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [MemberProfile::class, 'update'])->name('profile.update');
    Route::get('/attendance', [MemberAttendance::class, 'index'])->name('attendance.index');
});
