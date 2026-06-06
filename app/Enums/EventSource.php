<?php

namespace App\Enums;

enum EventSource: string
{
    case Manual = 'manual';
    case SmartRequest = 'smart_request';
    case Suggestion = 'suggestion';
}
