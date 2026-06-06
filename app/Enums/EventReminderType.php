<?php

namespace App\Enums;

enum EventReminderType: string
{
    case notification = 'notification';
    case email = 'email';
    case whatsapp = 'whatsapp';
}