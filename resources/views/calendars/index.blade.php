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

    // Importante: o model Event usa `calendarId` (chave estrangeira)
    $calendarEvents = $events->filter(function ($event) use ($defaultCalendar) {
        if (! $defaultCalendar) {
            return false;
        }

        return (int) ($event->calendarId ?? 0) === (int) $defaultCalendar->id;
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
        @vite(['resources/css/calendars.css', 'resources/js/calendars.js'])

        <div id="calendars-page-root" data-cal-edit-base-url="{{ url('/events') }}"></div>

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
                                                    <p class="day-name">{{ $day->translatedFormat('D') }}</p>
                                                    <h3 class="day-number">{{ $day->format('d') }}</h3>
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
                                                    <div class="month-event-summary">{{ $dayEvents->count() }} evento(s)</div>
                                                @else
                                                @forelse ($dayEvents->take(12) as $event)
                                                    @php
                                                        $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                                    @endphp

                                                    <article
                                                        class="event-preview {{ $status === 'cancelled' ? 'is-cancelled' : '' }}"
                                                        style="--calendar-color: {{ $defaultCalendar->color ?: '#008f91' }}"
                                                    >
                                                        <div class="event-time">{{ $event->startAt?->format('H:i') }} - {{ $event->endAt?->format('H:i') }}</div>
                                                    </article>
                                                @empty
                                                    <div class="empty-state">Sem eventos.</div>
                                                @endforelse
                                                @endif
                                            @endif
                                        </div>

                                        <template data-day-template="{{ $dayKey }}">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="calendar-subtitle">Dia selecionado</p>
                                                    <h3 class="text-xl font-black text-[#0d2b2b]">{{ $day->translatedFormat('d \d\e F') }}</h3>
                                                    <p class="mt-1 text-sm font-bold text-gray-500">{{ $dayEvents->count() }} evento(s)</p>
                                                </div>

                                                <a href="{{ $createEventUrl($day) }}" class="eos-btn eos-btn--primary">+</a>
                                            </div>

                                            @forelse ($dayEvents as $event)
                                                @php
                                                    $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                                    $priority = $event->priority instanceof BackedEnum ? $event->priority->value : $event->priority;
                                                @endphp

                                                <article class="detail-event is-clickable" data-event-id="{{ $event->id }}" title="Ver detalhes do evento">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <div class="text-sm font-black text-[#008f91]">
                                                            {{ $event->startAt?->format('H:i') }} - {{ $event->endAt?->format('H:i') }}
                                                        </div>

                                                        <div class="flex flex-wrap gap-1">
                                                            <span class="status-pill">{{ $statusLabels[$status] ?? $status }}</span>
                                                            <span class="status-pill {{ $priority === 'high' ? 'is-high' : '' }}">{{ $priorityLabels[$priority] ?? $priority }}</span>

                                                            @if ($event->createByAI)
                                                                <span class="status-pill">Criado por IA</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <h4 class="mt-3 text-base font-black text-[#0d2b2b]">{{ $event->title }}</h4>

                                                    @if ($event->description)
                                                        <p class="mt-2 text-sm font-semibold text-gray-600">{{ $event->description }}</p>
                                                    @endif

                                                    <div class="mt-3 space-y-1 text-sm font-bold text-gray-500">
                                                        @if ($event->location)
                                                            <p>Local: {{ $event->location }}</p>
                                                        @endif

                                                        @if ($event->meetingURL)
                                                            <p>
                                                                Reunião:
                                                                <a href="{{ $event->meetingURL }}" class="text-[#008f91]" target="_blank">{{ $event->meetingURL }}</a>
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
                                                    Este dia ainda não possui eventos. Clique no botão <strong>+</strong> para criar um novo compromisso.
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
    </div>
</x-app-layout>

