<?php

namespace App\Enums;

enum EventParticipantRole: string
{
    case Organizer = 'organizer';
    case Attendee = 'attendee';
}
