<?php

namespace App\Enums;

enum SmartRequestStatus: string
{
    case Pending = 'pending';
    case NeedsMoreInfo = 'needs_more_info';
    case NeedsConfirmation = 'needs_confirmation';
    case SuggestingTimes = 'suggesting_times';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Failed = 'failed';
}