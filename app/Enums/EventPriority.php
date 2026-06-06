<?php

namespace App\Enums;

enum EventPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
}