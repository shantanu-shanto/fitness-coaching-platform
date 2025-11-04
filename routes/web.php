<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\ProgressController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Profile routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Coach routes
Route::middleware(['auth', 'coach'])->prefix('coach')->name('coach.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CoachController::class, 'dashboard'])->name('dashboard');

    // Client management
    Route::get('/clients', [CoachController::class, 'listClients'])->name('clients.list');
    Route::get('/clients/{clientUserId}', [CoachController::class, 'viewClient'])->name('clients.view');
    Route::post('/profile/update', [CoachController::class, 'updateProfile'])->name('profile.update');
    Route::post('/clients/assign', [CoachController::class, 'assignClient'])->name('clients.assign');

    // Meal plans
    Route::get('/meal-plans/create', [MealPlanController::class, 'create'])->name('meal-plans.create');
    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::get('/meal-plans/{mealPlanId}/edit', [MealPlanController::class, 'edit'])->name('meal-plans.edit');
    Route::put('/meal-plans/{mealPlanId}', [MealPlanController::class, 'update'])->name('meal-plans.update');
    Route::delete('/meal-plans/{mealPlanId}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');

    // Workout plans
    Route::get('/workout-plans/create', [WorkoutPlanController::class, 'create'])->name('workout-plans.create');
    Route::post('/workout-plans', [WorkoutPlanController::class, 'store'])->name('workout-plans.store');
    Route::get('/workout-plans/{workoutPlanId}/edit', [WorkoutPlanController::class, 'edit'])->name('workout-plans.edit');
    Route::put('/workout-plans/{workoutPlanId}', [WorkoutPlanController::class, 'update'])->name('workout-plans.update');
    Route::delete('/workout-plans/{workoutPlanId}', [WorkoutPlanController::class, 'destroy'])->name('workout-plans.destroy');

    // Client progress
    Route::get('/clients/{clientUserId}/progress', [ProgressController::class, 'coachViewClientProgress'])->name('clients.progress');
});

// Client routes
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    // Meal plans
    Route::get('/meal-plans', [ClientController::class, 'viewMealPlans'])->name('meal-plans');
    Route::get('/meal-plans/{mealPlanId}', [ClientController::class, 'viewMealPlan'])->name('meal-plans.show');

    // Workout plans
    Route::get('/workout-plans', [ClientController::class, 'viewWorkoutPlans'])->name('workout-plans');
    Route::get('/workout-plans/{workoutPlanId}', [ClientController::class, 'viewWorkoutPlan'])->name('workout-plans.show');

    // Profile
    Route::post('/profile/update', [ClientController::class, 'updateProfile'])->name('profile.update');

    // Progress photos
    Route::get('/progress/upload-photo', [ProgressController::class, 'showUploadPhotoForm'])->name('progress.upload-form');
    Route::post('/progress/upload-photo', [ProgressController::class, 'storeProgressPhoto'])->name('progress.store-photo');
    Route::get('/progress/photos', [ProgressController::class, 'viewProgressPhotos'])->name('progress-photos');
    Route::delete('/progress/photos/{photoId}', [ProgressController::class, 'deleteProgressPhoto'])->name('progress.delete-photo');

    // Progress tracking
    Route::get('/progress/log', [ProgressController::class, 'showLogProgressForm'])->name('progress.log-form');
    Route::post('/progress/log', [ProgressController::class, 'storeProgressTracking'])->name('progress.store-tracking');
    Route::get('/progress/tracking', [ProgressController::class, 'viewProgressTracking'])->name('progress-tracking');
    Route::get('/progress/chart-data', [ProgressController::class, 'getProgressChartData'])->name('progress.chart-data');
});
