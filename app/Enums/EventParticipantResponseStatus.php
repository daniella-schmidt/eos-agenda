<?php

namespace App\Enums;

enum EventParticipantResponseStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Tentative = 'tentative';
}
