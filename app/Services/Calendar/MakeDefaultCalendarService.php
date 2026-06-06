<?php

namespace App\Services\Calendar;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MakeDefaultCalendarService
{
    public function handle(Calendar $calendar): Calendar
    {
        return DB::transaction(function () use ($calendar): Calendar {
            User::query()
                ->whereKey($calendar->userId)
                ->lockForUpdate()
                ->firstOrFail();

            Calendar::query()
                ->where('userId', $calendar->userId)
                ->update([
                    'isDefault' => false,
                ]);

            $calendar->update([
                'isDefault' => true,
                'isActive' => true,
            ]);

            return $calendar->refresh();
        });
    }
}