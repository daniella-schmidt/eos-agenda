@php
    use Carbon\CarbonImmutable;
    use Carbon\CarbonInterface;
    use Illuminate\Support\Str;

    $statusLabels = [
        'draft' => 'Pendente',
        'confirmed' => 'Confirmado',
        'cancelled' => 'Cancelado',
    ];

    $priorityLabels = [
        'low' => 'Baixa',
        'medium' => 'Média',
        'high' => 'Alta',
    ];

    $defaultCalendar = $calendars->firstWhere('isDefault', true) ?? $calendars->first();

    $calendarEvents = $events->filter(function ($event) use ($defaultCalendar) {
        if (! $defaultCalendar) {
            return false;
        }

        $eventCalendarId = $event->calendarId ?? $event->calendar_id ?? $event->calendar?->id;

        return (int) $eventCalendarId === (int) $defaultCalendar->id;
    });

    $viewMode = request('view', 'month');

    if (! in_array($viewMode, ['day', 'week', 'month', 'list'], true)) {
        $viewMode = 'month';
    }

    try {
        $anchorDate = CarbonImmutable::parse(request('date', now()->toDateString()))->locale('pt_BR');
    } catch (\Throwable) {
        $anchorDate = CarbonImmutable::now()->locale('pt_BR');
    }

    if ($viewMode === 'day') {
        $periodStart = $anchorDate->startOfDay();
        $periodEnd = $anchorDate->endOfDay();
        $periodTitle = $anchorDate->translatedFormat('d \d\e F \d\e Y');
    } elseif ($viewMode === 'week') {
        $periodStart = $anchorDate->startOfWeek(CarbonInterface::MONDAY);
        $periodEnd = $anchorDate->endOfWeek(CarbonInterface::SUNDAY);
        $periodTitle = $periodStart->format('d/m') . ' - ' . $periodEnd->format('d/m/Y');
    } else {
        $periodStart = $anchorDate->startOfMonth()->startOfWeek(CarbonInterface::MONDAY);
        $periodEnd = $anchorDate->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);
        $periodTitle = $anchorDate->translatedFormat('F \d\e Y');
    }

    $days = collect();
    $cursor = $periodStart;

    while ($cursor->lte($periodEnd)) {
        $days->push($cursor);
        $cursor = $cursor->addDay();
    }

    $eventsByDay = $calendarEvents->groupBy(fn ($event) => $event->startAt?->format('Y-m-d'));

    $periodEvents = $calendarEvents
        ->filter(fn ($event) => $event->startAt && $event->startAt->between($periodStart, $periodEnd))
        ->sortBy('startAt');

    $previousDate = match ($viewMode) {
        'day' => $anchorDate->subDay(),
        'week' => $anchorDate->subWeek(),
        default => $anchorDate->subMonth(),
    };

    $nextDate = match ($viewMode) {
        'day' => $anchorDate->addDay(),
        'week' => $anchorDate->addWeek(),
        default => $anchorDate->addMonth(),
    };

    $createEventUrl = function ($day) use ($defaultCalendar) {
        return route('events.create', [
            'date'        => $day->toDateString(),
            'calendar_id' => $defaultCalendar?->id,
        ]);
    };
@endphp

<x-app-layout>
    {{-- Header intentionally omitted for the calendar page.
        <div class="hidden">
            <div class="eos-page-header__left">
                @if ($defaultCalendar)
                    <p class="mt-1 text-sm font-bold text-gray-500">
                        {{ $defaultCalendar->name }}
                    </p>
                @endif
            </div>

            @if ($defaultCalendar)
                <a href="{{ $createEventUrl(now()) }}" class="eos-btn eos-btn--primary">
                    <span>＋</span> Novo evento
                </a>
            @endif
        </div>
    --}}

    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .calendar-page {
                max-width: 1440px;
                margin: 0 auto;
                padding: 24px;
            }

            .calendar-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .calendar-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .calendar-title {
                font-size: 1.35rem;
                font-weight: 900;
                color: #0d2b2b;
                text-transform: capitalize;
            }

            .calendar-subtitle {
                font-size: .8rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                letter-spacing: .18em;
            }

            .calendar-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 8px;
            }

            .calendar-nav,
            .view-tab {
                border: 1px solid #cfe0e0;
                background: #ffffff;
                color: #365050;
                padding: 8px 12px;
                font-size: .85rem;
                font-weight: 800;
                border-radius: 8px;
                text-decoration: none;
            }

            .view-tab.is-active {
                background: #0d2b2b;
                border-color: #0d2b2b;
                color: #ffffff;
            }

            .calendar-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 360px;
                gap: 18px;
                padding: 18px;
            }

            .week-header {
                display: grid;
                grid-template-columns: repeat(7, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 10px;
            }

            .week-header span {
                font-size: .72rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                text-align: center;
                letter-spacing: .12em;
            }

            .calendar-grid {
                display: grid;
                gap: 10px;
            }

            .calendar-grid--month {
                grid-template-columns: repeat(7, minmax(120px, 1fr));
            }

            .calendar-grid--week {
                grid-template-columns: repeat(7, minmax(150px, 1fr));
                overflow-x: auto;
            }

            .calendar-grid--day {
                grid-template-columns: 1fr;
            }

            .calendar-day {
                position: relative;
                min-height: 140px;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                background: #fafdff;
                padding: 12px;
                cursor: pointer;
                transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
            }

            .calendar-grid--week .calendar-day {
                min-height: 520px;
            }

            .calendar-grid--day .calendar-day {
                min-height: 560px;
            }

            .calendar-day:hover,
            .calendar-day.is-selected {
                border-color: #008f91;
                box-shadow: 0 12px 26px rgba(0, 143, 145, .12);
                transform: translateY(-1px);
            }

            .calendar-day.is-outside-month {
                opacity: .45;
            }

            .calendar-day.is-today {
                border-color: #ff6bb3;
            }

            .calendar-day.has-events {
                border-color: #008f91;
                background: #f0ffff;
            }

            .month-event-summary {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border-radius: 999px;
                background: #ccfeff;
                color: #006b6d;
                padding: 6px 10px;
                font-size: .78rem;
                font-weight: 900;
            }

            .month-event-summary::before {
                content: "";
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: #008f91;
                box-shadow: 0 0 0 2px #ffffff;
            }

            .day-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 8px;
                margin-bottom: 12px;
            }

            .day-name {
                font-size: .7rem;
                font-weight: 900;
                color: #748686;
                text-transform: uppercase;
                letter-spacing: .12em;
            }

            .day-number {
                font-size: 1.15rem;
                font-weight: 900;
                color: #0d2b2b;
            }

            .day-create-action {
                display: inline-flex;
                width: 30px;
                height: 30px;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                border: 2px solid #0d2b2b;
                background: #008f91;
                color: #ffffff;
                font-weight: 900;
                text-decoration: none;
                opacity: 0;
                transform: scale(.9);
                transition: opacity .15s ease, transform .15s ease;
            }

            .calendar-day:hover .day-create-action,
            .calendar-day.is-selected .day-create-action {
                opacity: 1;
                transform: scale(1);
            }

            .event-preview {
                border-left: 5px solid var(--calendar-color, #008f91);
                border-radius: 8px;
                background: #ffffff;
                padding: 9px;
                box-shadow: 0 8px 18px rgba(13, 43, 43, .06);
            }

            .event-preview + .event-preview {
                margin-top: 8px;
            }

            .event-preview.is-cancelled {
                opacity: .55;
                background: #f7f7f7;
            }

            .event-time {
                font-size: .72rem;
                font-weight: 900;
                color: #008f91;
            }

            .event-title {
                margin-top: 3px;
                font-size: .82rem;
                font-weight: 900;
                color: #0d2b2b;
            }

            .event-meta {
                margin-top: 3px;
                font-size: .72rem;
                font-weight: 700;
                color: #6b7d7d;
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 3px 8px;
                background: #e5ffff;
                color: #006b6d;
                font-size: .72rem;
                font-weight: 900;
            }

            .status-pill.is-high {
                background: #fff1f7;
                color: #b42369;
            }

            .day-details {
                position: sticky;
                top: 88px;
                align-self: start;
                padding: 18px;
            }

            .detail-event {
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                padding: 14px;
                background: #fafdff;
            }

            .detail-event + .detail-event {
                margin-top: 12px;
            }

            .empty-state {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 16px;
                color: #748686;
                font-size: .9rem;
                font-weight: 700;
                background: #ffffff;
            }

            .list-view {
                padding: 18px;
            }

            .list-event {
                display: grid;
                grid-template-columns: 150px minmax(0, 1fr) auto;
                gap: 14px;
                align-items: center;
                padding: 14px 0;
                border-bottom: 1px solid #dbe7e7;
            }

            .detail-event.is-clickable {
                cursor: pointer;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .detail-event.is-clickable:hover {
                border-color: #008f91;
                box-shadow: 0 4px 14px rgba(0, 143, 145, .14);
            }

            .cal-overlay {
                position: fixed;
                inset: 0;
                z-index: 80;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .cal-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(13, 43, 43, .35);
                backdrop-filter: blur(3px);
            }

            .cal-modal {
                position: relative;
                width: min(760px, 100%);
                max-height: calc(100vh - 48px);
                overflow: auto;
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 12px;
                box-shadow: 0 24px 70px rgba(13, 43, 43, .22);
            }

            .cal-modal__header {
                position: sticky;
                top: 0;
                z-index: 2;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
                background: #ffffff;
            }

            .cal-modal__body { padding: 18px; }

            .cal-modal__eyebrow {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
            }

            .cal-modal__title {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: 1.35rem;
                font-weight: 900;
            }

            .cal-modal__close {
                flex-shrink: 0;
                width: 38px;
                height: 38px;
                border-radius: 999px;
                border: 2px solid #0d2b2b;
                background: #ffffff;
                color: #0d2b2b;
                font-size: 1.4rem;
                font-weight: 900;
                line-height: 1;
                cursor: pointer;
            }

            .cal-modal__close:hover { background: #fff0f0; color: #c0392b; }

            .cal-detail-card {
                border: 1px solid #dbe7e7;
                border-left: 6px solid var(--event-color, #008f91);
                border-radius: 8px;
                padding: 16px;
                background: #fafdff;
            }

            .cal-detail-card__top {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            .cal-detail-card__time { color: #008f91; font-size: .9rem; font-weight: 900; }
            .cal-detail-card__heading { margin-top: 14px; color: #0d2b2b; font-size: 1.25rem; font-weight: 900; }
            .cal-detail-card__desc { margin-top: 10px; color: #526767; font-size: .95rem; font-weight: 600; line-height: 1.6; }

            .cal-detail-card__info {
                margin-top: 16px;
                display: grid;
                gap: 10px;
                color: #647878;
                font-size: .9rem;
                font-weight: 800;
            }

            .cal-detail-card__section {
                margin-top: 18px;
                padding-top: 14px;
                border-top: 1px solid #dbe7e7;
            }

            .cal-detail-card__section-label {
                margin-bottom: 8px;
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .cal-chip-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }

            .cal-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border-radius: 999px;
                border: 1px solid #cfe0e0;
                background: #fff;
                color: #0d2b2b;
                padding: 6px 10px;
                font-size: .82rem;
                font-weight: 800;
            }

            .cal-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 14px;
                font-size: .86rem;
                font-weight: 900;
                text-decoration: none;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .cal-btn:hover { transform: translate(-1px,-1px); box-shadow: 3px 3px 0 #0d2b2b; }
            .cal-btn--ghost { background: #fff; color: #0d2b2b; }
            .cal-btn--danger { background: #fff0f0; border-color: #c0392b; color: #c0392b; }

            .cal-empty {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 16px;
                background: #fff;
                color: #647878;
                font-size: .9rem;
                font-weight: 700;
            }

            .is-hidden { display: none !important; }

            @media (max-width: 1100px) {
                .calendar-toolbar {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .calendar-layout {
                    grid-template-columns: 1fr;
                }

                .day-details {
                    position: static;
                }

                .calendar-grid--month,
                .calendar-grid--week {
                    grid-template-columns: repeat(7, minmax(130px, 1fr));
                    overflow-x: auto;
                }

                .list-event {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="calendar-page">
            @unless ($defaultCalendar)
                <div class="calendar-card p-6">
                    <h2 class="text-xl font-black text-[#0d2b2b]">
                        Nenhum calendário padrão encontrado
                    </h2>
                    <p class="mt-2 text-sm font-semibold text-gray-500">
                        Crie ou defina um calendário padrão para visualizar seus eventos.
                    </p>
                </div>
            @else
                <section class="calendar-card">
                    <div class="calendar-toolbar">
                        <div>
                            <p class="calendar-subtitle">Calendário padrão</p>
                            <h2 class="calendar-title">{{ $periodTitle }}</h2>
                            <p class="mt-1 text-sm font-bold text-gray-500">
                                {{ $defaultCalendar->name }}
                            </p>
                        </div>

                        <div class="calendar-actions">
                            <a href="{{ route('calendars.index', ['view' => 'day', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'day' ? 'is-active' : '' }}">
                                Dia
                            </a>

                            <a href="{{ route('calendars.index', ['view' => 'week', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'week' ? 'is-active' : '' }}">
                                Semana
                            </a>

                            <a href="{{ route('calendars.index', ['view' => 'month', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'month' ? 'is-active' : '' }}">
                                Mês
                            </a>

                            <a href="{{ route('calendars.index', ['view' => 'list', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'list' ? 'is-active' : '' }}">
                                Lista
                            </a>
                        </div>
                    </div>

                    @if ($viewMode === 'list')
                        <div class="list-view">
                            @forelse ($periodEvents as $event)
                                @php
                                    $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                    $priority = $event->priority instanceof BackedEnum ? $event->priority->value : $event->priority;
                                @endphp

                                <article class="list-event">
                                    <div class="text-sm font-black text-[#008f91]">
                                        {{ $event->startAt?->format('d/m/Y H:i') }}
                                        <br>
                                        <span class="text-gray-400">
                                            até {{ $event->endAt?->format('H:i') }}
                                        </span>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-black text-[#0d2b2b]">
                                            {{ $event->title }}
                                        </h3>

                                        @if ($event->description)
                                            <p class="mt-1 text-sm font-semibold text-gray-500">
                                                {{ Str::limit($event->description, 120) }}
                                            </p>
                                        @endif

                                        @if ($event->location || $event->meetingURL)
                                            <p class="mt-1 text-sm font-bold text-gray-500">
                                                {{ $event->location ?: $event->meetingURL }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-1">
                                        <span class="status-pill">
                                            {{ $statusLabels[$status] ?? $status }}
                                        </span>

                                        <span class="status-pill {{ $priority === 'high' ? 'is-high' : '' }}">
                                            {{ $priorityLabels[$priority] ?? $priority }}
                                        </span>

                                        @if ($event->createByAI)
                                            <span class="status-pill">IA</span>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="empty-state">
                                    Nenhum evento encontrado neste período.
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="calendar-layout">
                            <div class="min-w-0">
                                @if ($viewMode !== 'day')
                                    <div class="week-header">
                                        <span>Seg</span>
                                        <span>Ter</span>
                                        <span>Qua</span>
                                        <span>Qui</span>
                                        <span>Sex</span>
                                        <span>Sáb</span>
                                        <span>Dom</span>
                                    </div>
                                @endif

                                <div class="calendar-grid calendar-grid--{{ $viewMode }}">
                                    @foreach ($days as $day)
                                        @php
                                            $dayKey = $day->format('Y-m-d');
                                            $dayEvents = $eventsByDay->get($dayKey, collect())->sortBy('startAt');
                                            $isToday = $day->isSameDay(now());
                                            $isOutsideMonth = $viewMode === 'month' && ! $day->isSameMonth($anchorDate);
                                            $hasEvents = $dayEvents->isNotEmpty();
                                        @endphp

                                        <div
                                            class="calendar-day js-calendar-day {{ $isToday ? 'is-today' : '' }} {{ $isOutsideMonth ? 'is-outside-month' : '' }} {{ $hasEvents ? 'has-events' : '' }}"
                                            data-day="{{ $dayKey }}"
                                        >
                                            <div class="day-header">
                                                <div>
                                                    <p class="day-name">
                                                        {{ $day->translatedFormat('D') }}
                                                    </p>
                                                    <h3 class="day-number">
                                                        {{ $day->format('d') }}
                                                    </h3>
                                                </div>

                                                <a
                                                    href="{{ $createEventUrl($day) }}"
                                                    class="day-create-action"
                                                    title="Criar evento em {{ $day->format('d/m/Y') }}"
                                                    onclick="event.stopPropagation()"
                                                >
                                                    +
                                                </a>
                                            </div>

                                            @if ($viewMode === 'month')
                                                @if ($hasEvents)
                                                    <div class="month-event-summary">
                                                        {{ $dayEvents->count() }} evento(s)
                                                    </div>
                                                @else
                                                    <div class="empty-state">
                                                        Sem eventos.
                                                    </div>
                                                @endif
                                            @else
                                                @forelse ($dayEvents->take(12) as $event)
                                                    @php
                                                        $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                                    @endphp

                                                    <article
                                                        class="event-preview {{ $status === 'cancelled' ? 'is-cancelled' : '' }}"
                                                        style="--calendar-color: {{ $defaultCalendar->color ?: '#008f91' }}"
                                                    >
                                                        <div class="event-time">
                                                            {{ $event->startAt?->format('H:i') }} - {{ $event->endAt?->format('H:i') }}
                                                        </div>
                                                    </article>
                                                @empty
                                                    <div class="empty-state">
                                                        Sem eventos.
                                                    </div>
                                                @endforelse
                                            @endif
                                        </div>

                                        <template data-day-template="{{ $dayKey }}">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="calendar-subtitle">Dia selecionado</p>
                                                    <h3 class="text-xl font-black text-[#0d2b2b]">
                                                        {{ $day->translatedFormat('d \d\e F') }}
                                                    </h3>
                                                    <p class="mt-1 text-sm font-bold text-gray-500">
                                                        {{ $dayEvents->count() }} evento(s)
                                                    </p>
                                                </div>

                                                <a
                                                    href="{{ $createEventUrl($day) }}"
                                                    class="eos-btn eos-btn--primary"
                                                >
                                                    +
                                                </a>
                                            </div>

                                            @forelse ($dayEvents as $event)
                                                @php
                                                    $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                                    $priority = $event->priority instanceof BackedEnum ? $event->priority->value : $event->priority;
                                                @endphp

                                                <article class="detail-event is-clickable" data-event-id="{{ $event->id }}" title="Ver detalhes do evento">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <div class="text-sm font-black text-[#008f91]">
                                                            {{ $event->startAt?->format('H:i') }}
                                                            -
                                                            {{ $event->endAt?->format('H:i') }}
                                                        </div>

                                                        <div class="flex flex-wrap gap-1">
                                                            <span class="status-pill">
                                                                {{ $statusLabels[$status] ?? $status }}
                                                            </span>

                                                            <span class="status-pill {{ $priority === 'high' ? 'is-high' : '' }}">
                                                                {{ $priorityLabels[$priority] ?? $priority }}
                                                            </span>

                                                            @if ($event->createByAI)
                                                                <span class="status-pill">Criado por IA</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <h4 class="mt-3 text-base font-black text-[#0d2b2b]">
                                                        {{ $event->title }}
                                                    </h4>

                                                    @if ($event->description)
                                                        <p class="mt-2 text-sm font-semibold text-gray-600">
                                                            {{ $event->description }}
                                                        </p>
                                                    @endif

                                                    <div class="mt-3 space-y-1 text-sm font-bold text-gray-500">
                                                        @if ($event->location)
                                                            <p>Local: {{ $event->location }}</p>
                                                        @endif

                                                        @if ($event->meetingURL)
                                                            <p>
                                                                Reunião:
                                                                <a href="{{ $event->meetingURL }}" class="text-[#008f91]" target="_blank">
                                                                    {{ $event->meetingURL }}
                                                                </a>
                                                            </p>
                                                        @endif

                                                        @if ($event->timezone)
                                                            <p>Fuso horário: {{ $event->timezone }}</p>
                                                        @endif

                                                        @if ($event->participants_count ?? false)
                                                        <p>Participantes: {{ $event->participants_count }}</p>
                                                        @endif
                                                    </div>
                                                </article>
                                            @empty
                                                <div class="empty-state">
                                                    Este dia ainda não possui eventos.
                                                    Clique no botão <strong>+</strong> para criar um novo compromisso.
                                                </div>
                                            @endforelse
                                        </template>
                                    @endforeach
                                </div>
                            </div>

                            <aside class="calendar-card day-details">
                                <div id="selectedDayDetails">
                                    <div class="empty-state">
                                        Clique em um dia do calendário para ver os eventos detalhados ou criar um novo evento.
                                    </div>
                                </div>
                            </aside>
                        </div>
                    @endif
                </section>
            @endunless
        </div>

        <div id="calEventOverlay" class="cal-overlay is-hidden">
            <div class="cal-backdrop" data-cal-close></div>
            <section class="cal-modal">
                <div class="cal-modal__header">
                    <div>
                        <p class="cal-modal__eyebrow">Detalhes do evento</p>
                        <h2 id="calModalTitle" class="cal-modal__title">Evento selecionado</h2>
                    </div>
                    <button class="cal-modal__close" type="button" data-cal-close>×</button>
                </div>
                <div id="calModalBody" class="cal-modal__body">
                    <div class="cal-empty">Carregando detalhes...</div>
                </div>
            </section>
        </div>

        <script>
            const dayCards = document.querySelectorAll('.js-calendar-day');
            const detailContainer = document.getElementById('selectedDayDetails');

            function selectDay(dayKey) {
                dayCards.forEach((card) => {
                    card.classList.toggle('is-selected', card.dataset.day === dayKey);
                });

                const template = document.querySelector(`[data-day-template="${dayKey}"]`);

                if (template && detailContainer) {
                    detailContainer.innerHTML = template.innerHTML;
                }
            }

            dayCards.forEach((card) => {
                card.addEventListener('click', () => {
                    selectDay(card.dataset.day);
                });
            });

            // — Event detail modal —

            const calOverlay  = document.getElementById('calEventOverlay');
            const calModalTitle = document.getElementById('calModalTitle');
            const calModalBody  = document.getElementById('calModalBody');
            const editBaseUrl   = "{{ url('/events') }}";
            const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            function calOpenModal()  { calOverlay.classList.remove('is-hidden'); document.body.style.overflow = 'hidden'; }
            function calCloseModal() { calOverlay.classList.add('is-hidden');    document.body.style.overflow = ''; }

            document.querySelectorAll('[data-cal-close]').forEach(el => el.addEventListener('click', calCloseModal));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') calCloseModal(); });

            async function calApi(url) {
                const res = await fetch(url, {
                    headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                });
                if (!res.ok) throw new Error('Não foi possível carregar o evento.');
                return res.json();
            }

            const calEscape = v => String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            const calDate   = v => v ? new Date(v).toLocaleString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '-';
            const calStatus   = s => ({ draft:'Rascunho', confirmed:'Confirmado', cancelled:'Cancelado' }[s] || s || '-');
            const calPriority = p => ({ low:'Baixa', medium:'Média', high:'Alta' }[p] || p || '-');

            async function calShowEvent(id) {
                calModalTitle.textContent = 'Carregando...';
                calModalBody.innerHTML = '<div class="cal-empty">Carregando detalhes...</div>';
                calOpenModal();

                try {
                    const payload = await calApi(`/api/events/${id}`);
                    const ev = payload.data;

                    calModalTitle.textContent = ev.title || 'Evento';

                    const participants = ev.participants || [];
                    const reminders    = ev.reminders   || [];
                    const statusClass  = ev.status   === 'cancelled' ? 'is-cancelled' : '';
                    const priorClass   = ev.priority  === 'high'     ? 'is-high'      : '';

                    calModalBody.innerHTML = `
                        <article class="cal-detail-card" style="--event-color:${calEscape(ev.calendar?.color || '#008f91')}">
                            <div class="cal-detail-card__top">
                                <div class="cal-detail-card__time">
                                    ${calDate(ev.startAt)} até ${calDate(ev.endAt)}
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    <span class="status-pill ${statusClass}">${calEscape(calStatus(ev.status))}</span>
                                    <span class="status-pill ${priorClass}">Prioridade ${calEscape(calPriority(ev.priority))}</span>
                                    ${ev.createByAI ? '<span class="status-pill">Criado por IA</span>' : ''}
                                </div>
                            </div>

                            <h3 class="cal-detail-card__heading">${calEscape(ev.title || '-')}</h3>

                            <p class="cal-detail-card__desc">
                                ${ev.description ? calEscape(ev.description) : 'Sem descrição cadastrada.'}
                            </p>

                            <div class="cal-detail-card__info">
                                <p><strong>Calendário:</strong> ${calEscape(ev.calendar?.name || '-')}</p>
                                <p><strong>Local:</strong> ${calEscape(ev.location || '-')}</p>
                                <p><strong>Reunião:</strong> ${ev.meetingURL ? `<a href="${calEscape(ev.meetingURL)}" target="_blank" style="color:#008f91">${calEscape(ev.meetingURL)}</a>` : '-'}</p>
                                <p><strong>Fuso horário:</strong> ${calEscape(ev.timezone || '-')}</p>
                                ${ev.isAllDay    ? '<p><strong>Tipo:</strong> Evento de dia todo</p>'    : ''}
                                ${ev.isRecurring ? '<p><strong>Recorrência:</strong> Evento recorrente</p>' : ''}
                            </div>

                            <div class="cal-detail-card__section">
                                <p class="cal-detail-card__section-label">Participantes</p>
                                <div class="cal-chip-list">
                                    ${participants.length
                                        ? participants.map(p => `<span class="cal-chip">${calEscape(p.name || p.email || 'Participante')}${p.email ? ` · ${calEscape(p.email)}` : ''} · ${calEscape(p.role || 'attendee')} · ${calEscape(p.responseStatus || 'pending')}</span>`).join('')
                                        : '<div class="cal-empty">Nenhum participante.</div>'}
                                </div>
                            </div>

                            <div class="cal-detail-card__section">
                                <p class="cal-detail-card__section-label">Lembretes</p>
                                <div class="cal-chip-list">
                                    ${reminders.length
                                        ? reminders.map(r => `<span class="cal-chip">${calEscape(r.type || 'notification')} · ${Number(r.minutesBefore)} min antes</span>`).join('')
                                        : '<div class="cal-empty">Nenhum lembrete.</div>'}
                                </div>
                            </div>

                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px;">
                                <a href="${editBaseUrl}/${calEscape(ev.id)}/edit" class="cal-btn cal-btn--ghost">Editar evento</a>
                            </div>
                        </article>
                    `;
                } catch (err) {
                    calModalBody.innerHTML = `<div class="cal-empty">${calEscape(err.message)}</div>`;
                }
            }

            // Delegação de clique nos eventos do painel lateral (conteúdo inserido dinamicamente)
            document.getElementById('selectedDayDetails').addEventListener('click', function (e) {
                const article = e.target.closest('[data-event-id]');
                if (article) calShowEvent(article.dataset.eventId);
            });

            // — Calendário —

            const today = new Date().toISOString().slice(0, 10);
            const todayCard = document.querySelector(`[data-day="${today}"]`);
            const firstCard = document.querySelector('.js-calendar-day');

            if (todayCard) {
                selectDay(today);
            } else if (firstCard) {
                selectDay(firstCard.dataset.day);
            }
        </script>
    </div>
</x-app-layout>
