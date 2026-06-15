<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventReminderController;
use App\Http\Controllers\EventSuggestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmartRequestController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/events', function () {
        return view('events.index');
    })->name('events.page');

    Route::get('/event-suggestions', function () {
        return view('event-suggestions.index');
    })->name('event-suggestions.page');

    Route::get('/event-reminders', function () {
        return view('event-reminders.index');
    })->name('event-reminders.page');

    Route::get('/contacts', function () {
        return view('contacts.index');
    })->name('contacts.page');

    Route::get('/event-participants', function () {
        return view('event-participants.index');
    })->name('event-participants.page');

    Route::get('/preferences', function () {
        return view('user-preferences.show.index');
    })->name('user-preferences.page');

    Route::get('/preferences/edit', function () {
        return view('user-preferences.edit.index');
    })->name('user-preferences.edit-page');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/calendars', function () {
        return view('calendars.index');
    })->name('calendars.index');

    Route::get('/calendars/{calendar}', [CalendarController::class, 'showCalendar'])
        ->name('calendars.show');

    Route::get('/smart-requests', function () {
        return view('smart-requests.index');
    })->name('smart-requests.index');

    Route::prefix('api')->group(function () {
        Route::get('/user-preferences', [UserPreferenceController::class, 'show'])
            ->name('user-preferences.show');

        Route::patch('/user-preferences', [UserPreferenceController::class, 'update'])
            ->name('user-preferences.update');

        Route::get('/calendars/{calendar}/events', [EventController::class, 'indexByCalendar']);

        Route::get('/smart-requests/status/{status}', [SmartRequestController::class, 'byStatus'])
            ->name('smart-requests.by-status');

        Route::post('/smart-requests', [SmartRequestController::class, 'store'])
            ->name('smart-requests.store');

        Route::put('/smart-requests/{smartRequest}', [SmartRequestController::class, 'update'])
            ->name('smart-requests.update');

        Route::get('/smart-requests/{smartRequest}', [SmartRequestController::class, 'show'])
            ->name('smart-requests.show');

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
