<?php
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SmartRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/calendar-tester', function () {
        return view('calendar-tester');
    })->name('calendar-tester');

    Route::get('/smart-request-tester', function () {
        return view('smart-request-tester');
    })->name('smart-request-tester');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendars', function () {
        return view('calendars.index');
    })->name('calendars.index');

    Route::get('/calendars/{calendar}', [CalendarController::class, 'show'])
    ->name('calendars.show');


    Route::get('/smart-requests', function () {
        return view('smart-requests.index');
    })->name('smart-requests.index');

    Route::prefix('api')->group(function () {

        Route::get('/calendars/{calendar}', [CalendarController::class, 'showCalendar'])->name('calendars.show');
        Route::get('/calendars/{calendar}/events', [EventController::class, 'indexByCalendar']);
        Route::apiResource('events', EventController::class)->except(['create', 'edit']);
        Route::get('/smart-requests/status/{status}', [SmartRequestController::class, 'byStatus'])
            ->name('smart-requests.by-status');

        Route::post('/smart-requests', [SmartRequestController::class, 'store'])
            ->name('smart-requests.store');
        
        Route::put('/smart-requests/{smartRequest}', [SmartRequestController::class, 'update'])
            ->name('smart-requests.update');
        Route::get('/smart-requests/{smartRequest}', [SmartRequestController::class, 'show'])->name('smart-requests.show');    

        Route::post('/smart-requests/{smartRequest}/confirm', [SmartRequestController::class, 'confirm'])
            ->name('smart-requests.confirm');

        Route::delete('/smart-requests/{smartRequest}', [SmartRequestController::class, 'destroy'])
            ->name('smart-requests.destroy');

        Route::post('/calendars/{calendar}/make-default', [CalendarController::class, 'makeDefault'])
            ->name('calendars.make-default');

        Route::resource('calendars', CalendarController::class)
            ->except(['create', 'edit']);
    });
});

require __DIR__.'/auth.php';