@php
    use Carbon\CarbonImmutable;
    use Carbon\CarbonInterface;

    $viewMode = request('view', 'week');

    if (! in_array($viewMode, ['day', 'week', 'month', 'list'], true)) {
        $viewMode = 'week';
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
    } elseif ($viewMode === 'month' || $viewMode === 'list') {
        $periodStart = $anchorDate->startOfMonth()->startOfWeek(CarbonInterface::MONDAY);
        $periodEnd = $anchorDate->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);
        $periodTitle = $anchorDate->translatedFormat('F \d\e Y');
    } else {
        $periodStart = $anchorDate->startOfWeek(CarbonInterface::MONDAY);
        $periodEnd = $anchorDate->endOfWeek(CarbonInterface::SUNDAY);
        $periodTitle = $periodStart->format('d/m') . ' - ' . $periodEnd->format('d/m/Y');
    }

    $periodDays = collect();
    $cursor = $periodStart;

    while ($cursor->lte($periodEnd)) {
        $periodDays->push($cursor);
        $cursor = $cursor->addDay();
    }

    $periodEvents = $events
        ->filter(fn ($event) => $event->startAt && $event->startAt->between($periodStart, $periodEnd))
        ->sortBy('startAt');

    $eventsByDay = $periodEvents->groupBy(fn ($event) => $event->startAt?->format('Y-m-d'));

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

    $statusLabels = [
        'draft' => 'Pendente',
        'confirmed' => 'Confirmado',
        'cancelled' => 'Cancelado',
    ];

    $priorityLabels = [
        'low' => 'Baixa',
        'medium' => 'M&eacute;dia',
        'high' => 'Alta',
    ];
@endphp

<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

        <div class="dashboard-shell">
            <aside class="dashboard-sidebar dashboard-card">
                <div class="mb-6">
                    <p class="text-xs font-black uppercase tracking-[.22em] text-[#008f91]">Menu</p>
                    <h2 class="mt-1 text-xl font-black text-[#0d2b2b]">Agenda</h2>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('calendars.index') }}" class="sidebar-link">
                        <span>Minha agenda</span>
                        <span>&rsaquo;</span>
                    </a>
                    <a href="{{ route('smart-requests.index') }}" class="sidebar-link">
                        <span>Solicita&ccedil;&otilde;es inteligentes</span>
                        <span>&rsaquo;</span>
                    </a>
                    <a href="{{ route('contacts.index') }}" class="sidebar-link">
                        <span>Contatos</span>
                        <span>&rsaquo;</span>
                    </a>
                    <a href="{{ route('user-preferences.index') }}" class="sidebar-link">
                        <span>Prefer&ecirc;ncias</span>
                        <span>&rsaquo;</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <span>Configura&ccedil;&otilde;es</span>
                        <span>&rsaquo;</span>
                    </a>
                </nav>

                <div class="mt-8 border-t border-[#dbe7e7] pt-5">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-[.18em] text-[#008f91]">Calend&aacute;rios</p>
                    </div>

                    <div class="space-y-2">
                        @forelse ($calendars as $calendar)
                            <a href="{{ route('calendars.index') }}" class="sidebar-link">
                                <span class="inline-flex min-w-0 items-center gap-2">
                                    <span class="calendar-dot"></span>
                                    <span class="truncate">{{ $calendar->name }}</span>
                                </span>
                                @if ($calendar->isDefault)
                                    <span class="text-xs text-[#008f91]">Padr&atilde;o</span>
                                @endif
                            </a>
                        @empty
                            <div class="rounded-lg border border-dashed border-[#cfe0e0] p-3 text-sm font-semibold text-gray-500">
                                Nenhum calend&aacute;rio ativo encontrado.
                            </div>
                        @endforelse
                    </div>
                </div>
            </aside>

            <main class="min-w-0 space-y-5">
                <section class="dashboard-card p-5">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[.22em] text-[#008f91]">Visualiza&ccedil;&atilde;o</p>
                            <h2 class="mt-1 text-xl font-black text-[#0d2b2b]">{{ $periodTitle }}</h2>
                        </div>

                        <div class="flex flex-wrap gap-2" aria-label="Navegacao e modos de visualizacao">
                            <a href="{{ route('dashboard', ['view' => 'day', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'day' ? 'is-active' : '' }}">Dia</a>
                            <a href="{{ route('dashboard', ['view' => 'week', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'week' ? 'is-active' : '' }}">Semana</a>
                            <a href="{{ route('dashboard', ['view' => 'month', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'month' ? 'is-active' : '' }}">M&ecirc;s</a>
                            <a href="{{ route('dashboard', ['view' => 'list', 'date' => $anchorDate->toDateString()]) }}"
                               class="view-tab {{ $viewMode === 'list' ? 'is-active' : '' }}">Lista</a>
                        </div>
                    </div>

                    @if ($viewMode === 'list')
                        <div class="divide-y divide-[#dbe7e7]">
                            @forelse ($periodEvents as $event)
                                @php
                                    $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                    $priority = $event->priority instanceof BackedEnum ? $event->priority->value : $event->priority;
                                @endphp

                                <article class="dashboard-list-event js-searchable" data-search="{{ strtolower($event->title.' '.$event->location.' '.$event->meetingURL.' '.$event->calendar?->name) }}">
                                    <div class="text-sm font-black text-[#008f91]">
                                        {{ $event->startAt?->format('d/m/Y H:i') }}
                                        <br>
                                        <span class="text-gray-400">ate {{ $event->endAt?->format('H:i') }}</span>
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-black text-[#0d2b2b]">{{ $event->title }}</h3>
                                        <p class="truncate text-sm font-semibold text-gray-500">{{ $event->calendar?->name ?? 'Sem calendario' }}</p>
                                    </div>

                                    <div class="flex flex-wrap gap-1">
                                        <span class="status-pill">{{ $statusLabels[$status] ?? $status }}</span>
                                        <span class="status-pill {{ $priority === 'high' ? 'is-high' : '' }}">
                                            {!! $priorityLabels[$priority] ?? $priority !!}
                                        </span>
                                        @if ($event->createByAI)
                                            <span class="status-pill">IA</span>
                                        @endif
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-lg border border-dashed border-[#cfe0e0] p-5 text-sm font-semibold text-gray-500">
                                    Nenhum compromisso encontrado neste per&iacute;odo.
                                </div>
                            @endforelse
                        </div>
                    @else
                        @if ($viewMode !== 'day')
                            <div class="mb-3 grid grid-cols-7 gap-3 text-center text-xs font-black uppercase tracking-[.12em] text-[#008f91]">
                                <span>Seg</span>
                                <span>Ter</span>
                                <span>Qua</span>
                                <span>Qui</span>
                                <span>Sex</span>
                                <span>Sab</span>
                                <span>Dom</span>
                            </div>
                        @endif

                        <div class="agenda-grid agenda-grid--{{ $viewMode }}">
                            @foreach ($periodDays as $day)
                                @php
                                    $dayKey = $day->format('Y-m-d');
                                    $dayEvents = $eventsByDay->get($dayKey, collect())->sortBy('startAt');
                                    $isToday = $day->isSameDay(now());
                                    $isOutsideMonth = $viewMode === 'month' && ! $day->isSameMonth($anchorDate);
                                    $hasEvents = $dayEvents->isNotEmpty();
                                @endphp

                                <div class="day-column {{ $isToday ? 'is-today' : '' }} {{ $isOutsideMonth ? 'is-outside-month' : '' }} {{ $hasEvents ? 'has-events' : '' }}">
                                    <div class="mb-3">
                                        <p class="text-xs font-black uppercase tracking-[.12em] text-gray-500">{{ $day->translatedFormat('D') }}</p>
                                        <h3 class="text-lg font-black text-[#0d2b2b]">{{ $day->format($viewMode === 'day' ? 'd/m/Y' : 'd/m') }}</h3>
                                    </div>

                                    @if ($viewMode === 'month')
                                        @if ($hasEvents)
                                            <div class="month-event-summary">
                                                {{ $dayEvents->count() }} evento(s)
                                            </div>
                                        @else
                                            <div class="rounded-lg border border-dashed border-[#cfe0e0] p-3 text-sm font-semibold text-gray-400">
                                                Sem eventos.
                                            </div>
                                        @endif
                                    @else
                                        @forelse ($dayEvents as $event)
                                            @php
                                                $status = $event->status instanceof BackedEnum ? $event->status->value : $event->status;
                                                $priority = $event->priority instanceof BackedEnum ? $event->priority->value : $event->priority;
                                            @endphp

                                            <article class="event-item js-searchable {{ $status === 'cancelled' ? 'is-cancelled' : '' }}" data-search="{{ strtolower($event->title.' '.$event->location.' '.$event->meetingURL.' '.$event->calendar?->name) }}" style="--calendar-color: {{ $event->calendar?->color ?: '#008f91' }}">
                                                <div class="text-xs font-black text-[#008f91]">
                                                    {{ $event->startAt?->format('H:i') }} - {{ $event->endAt?->format('H:i') }}
                                                </div>
                                                @if ($viewMode === 'day')
                                                    <h4 class="mt-1 text-sm font-black text-[#0d2b2b]">{{ $event->title }}</h4>
                                                    @if ($event->location || $event->meetingURL)
                                                        <p class="mt-1 truncate text-xs font-semibold text-gray-500">
                                                            {{ $event->location ?: $event->meetingURL }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-3 flex flex-wrap gap-1">
                                                        <span class="status-pill">{{ $statusLabels[$status] ?? $status }}</span>
                                                        <span class="status-pill {{ $priority === 'high' ? 'is-high' : '' }}">
                                                            {!! $priorityLabels[$priority] ?? $priority !!}
                                                        </span>
                                                        @if ($event->createByAI)
                                                            <span class="status-pill">Criado por IA</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </article>
                                        @empty
                                            <div class="rounded-lg border border-dashed border-[#cfe0e0] p-3 text-sm font-semibold text-gray-400">
                                                Sem eventos neste dia.
                                            </div>
                                        @endforelse
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="grid gap-5 lg:grid-cols-3">
                    <div class="dashboard-card p-5 lg:col-span-2">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[.22em] text-[#008f91]">Pr&oacute;ximos compromissos</p>
                                <h2 class="mt-1 text-xl font-black text-[#0d2b2b]">Lista do per&iacute;odo</h2>
                            </div>
                            <a href="{{ route('events.index') }}" class="text-sm font-black text-[#ff6bb3]">Gerir eventos</a>
                        </div>

                        <div class="divide-y divide-[#dbe7e7]">
                            @forelse ($periodEvents as $event)
                                <div class="js-searchable grid gap-3 py-3 sm:grid-cols-[120px_minmax(0,1fr)_auto] sm:items-center" data-search="{{ strtolower($event->title.' '.$event->location.' '.$event->meetingURL.' '.$event->calendar?->name) }}">
                                    <div class="text-sm font-black text-[#008f91]">
                                        {{ $event->startAt?->format('d/m H:i') }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-black text-[#0d2b2b]">{{ $event->title }}</p>
                                        <p class="truncate text-sm font-semibold text-gray-500">{{ $event->calendar?->name ?? 'Sem calendario' }}</p>
                                    </div>
                                    <span class="status-pill">{{ $event->createByAI ? 'IA' : 'Manual' }}</span>
                                </div>
                            @empty
                                <div class="rounded-lg border border-dashed border-[#cfe0e0] p-5 text-sm font-semibold text-gray-500">
                                    Nenhum compromisso encontrado neste per&iacute;odo.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="dashboard-card p-5">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[.22em] text-[#008f91]">Notifica&ccedil;&otilde;es</p>
                                <h2 class="mt-1 text-xl font-black text-[#0d2b2b]">Lembretes</h2>
                            </div>
                        </div>

                        <div id="upcomingRemindersContainer" class="space-y-3">
                            <div class="rounded-lg border border-dashed border-[#cfe0e0] p-4 text-sm font-semibold text-gray-500">
                                Carregando lembretes...
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>


