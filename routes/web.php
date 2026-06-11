<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventReminderController;
use App\Http\Controllers\EventSuggestionController;
use App\Http\Controllers\SmartRequestController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/calendar-tester', function () {
        return view('calendar-tester');
    })->name('calendar-tester');

    Route::get('/smart-request-tester', function () {
        return view('smart-request-tester');
    })->name('smart-request-tester');

    Route::get('/event-tester', function () {
        return view('event-tester');
    })->name('event-tester');

    Route::get('/event-suggestion-tester', function () {
        return view('event-suggestion-tester');
    })->name('event-suggestion-tester');

    Route::get('/event-reminder-tester', function () {
        return view('event-reminder-tester');
    })->name('event-reminder-tester');

    Route::get('/contact-tester', function () {
        return view('contact-tester');
    })->name('contact-tester');

    Route::get('/event-participant-tester', function () {
        return view('event-participant-tester');
    })->name('event-participant-tester');

    Route::get('/user-preference-show-tester', function () {
        return view('user-preferences.show-tester');
    })->name('user-preferences.show-tester');

    Route::get('/user-preference-update-tester', function () {
        return view('user-preferences.update-tester');
    })->name('user-preferences.update-tester');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('api')->group(function () {
        Route::get('/user-preferences', [UserPreferenceController::class, 'show'])
            ->name('user-preferences.show');

        Route::patch('/user-preferences', [UserPreferenceController::class, 'update'])
            ->name('user-preferences.update');

        Route::get('/smart-requests/status/{status}', [SmartRequestController::class, 'byStatus'])
            ->name('smart-requests.by-status');

        Route::post('/smart-requests', [SmartRequestController::class, 'store'])
            ->name('smart-requests.store');

        Route::post('/smart-requests/{smartRequest}/confirm', [SmartRequestController::class, 'confirm'])
            ->name('smart-requests.confirm');

        Route::get('/smart-requests/{smartRequest}/suggestions', [EventSuggestionController::class, 'index'])
            ->name('event-suggestions.index');

        Route::post('/smart-requests/{smartRequest}/suggestions/generate', [EventSuggestionController::class, 'generate'])
            ->name('event-suggestions.generate');

        Route::post('/event-suggestions/{eventSuggestion}/select', [EventSuggestionController::class, 'select'])
            ->name('event-suggestions.select');

        Route::delete('/smart-requests/{smartRequest}', [SmartRequestController::class, 'destroy'])
            ->name('smart-requests.destroy');

        Route::post('/calendars/{calendar}/make-default', [CalendarController::class, 'makeDefault'])
            ->name('calendars.make-default');

        Route::resource('calendars', CalendarController::class)
            ->except(['create', 'edit']);

        Route::resource('contacts', ContactController::class)
            ->except(['create', 'edit']);

        Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])
            ->name('events.cancel');

        Route::get('/events/{event}/participants', [EventParticipantController::class, 'index'])
            ->name('event-participants.index');

        Route::post('/events/{event}/participants', [EventParticipantController::class, 'store'])
            ->name('event-participants.store');

        Route::get('/event-participants/{eventParticipant}', [EventParticipantController::class, 'show'])
            ->name('event-participants.show');

        Route::patch('/event-participants/{eventParticipant}', [EventParticipantController::class, 'update'])
            ->name('event-participants.update');

        Route::delete('/event-participants/{eventParticipant}', [EventParticipantController::class, 'destroy'])
            ->name('event-participants.destroy');

        Route::get('/events/{event}/reminders', [EventReminderController::class, 'index'])
            ->name('event-reminders.index');

        Route::post('/events/{event}/reminders', [EventReminderController::class, 'store'])
            ->name('event-reminders.store');

        Route::get('/event-reminders/{eventReminder}', [EventReminderController::class, 'show'])
            ->name('event-reminders.show');

        Route::patch('/event-reminders/{eventReminder}', [EventReminderController::class, 'update'])
            ->name('event-reminders.update');

        Route::post('/event-reminders/{eventReminder}/mark-as-sent', [EventReminderController::class, 'markAsSent'])
            ->name('event-reminders.mark-as-sent');

        Route::delete('/event-reminders/{eventReminder}', [EventReminderController::class, 'destroy'])
            ->name('event-reminders.destroy');

        Route::resource('events', EventController::class)
            ->except(['create', 'edit']);
    });
});

require __DIR__.'/auth.php';
