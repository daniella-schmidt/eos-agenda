<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function indexByCalendar(Calendar $calendar)
    {
        Gate::authorize('view', $calendar);
        // Retorna array vazio se a tabela ainda não existir ou não houver eventos
        return response()->json(['data' => []]);
    }
    
}