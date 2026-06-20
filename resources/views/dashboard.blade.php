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
        <style>
            .dashboard-shell {
                display: grid;
                grid-template-columns: 260px minmax(0, 1fr);
                gap: 24px;
                max-width: 1440px;
                margin: 0 auto;
                padding: 24px;
            }

            .dashboard-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .dashboard-sidebar {
                position: sticky;
                top: 88px;
                height: calc(100vh - 112px);
                overflow: auto;
                padding: 18px;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 8px;
                color: #365050;
                font-size: .92rem;
                font-weight: 700;
                text-decoration: none;
                transition: background .15s ease, color .15s ease;
            }

            .sidebar-link:hover,
            .sidebar-link.is-active {
                background: #ccfeff;
                color: #0d2b2b;
            }

            .calendar-dot {
                width: 10px;
                height: 10px;
                border-radius: 999px;
                border: 2px solid #0d2b2b;
                background: #008f91;
                flex: none;
            }

            .dashboard-topbar {
                display: grid;
                grid-template-columns: minmax(220px, 1fr) minmax(260px, 420px) auto;
                gap: 16px;
                align-items: center;
                padding: 18px;
            }

            .search-input,
            .smart-input {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #ffffff;
                color: #0d2b2b;
                font-size: .95rem;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .search-input {
                padding: 12px 14px;
            }

            .smart-input {
                min-height: 88px;
                resize: vertical;
                padding: 14px;
            }

            .search-input:focus,
            .smart-input:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .eos-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-height: 42px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 16px;
                font-size: .9rem;
                font-weight: 900;
                text-decoration: none;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .eos-action--primary {
                background: #008f91;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .eos-action--secondary {
                background: #ffffff;
                color: #0d2b2b;
            }

            .eos-action--pink {
                background: #ff6bb3;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .eos-action:hover {
                transform: translate(-1px, -1px);
                box-shadow: 4px 4px 0 #0d2b2b;
            }

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

            .dashboard-calendar-nav {
                border: 1px solid #cfe0e0;
                background: #ffffff;
                color: #365050;
                padding: 8px 12px;
                font-size: .85rem;
                font-weight: 800;
                border-radius: 8px;
                text-decoration: none;
            }

            .agenda-grid {
                display: grid;
                gap: 12px;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .agenda-grid--month {
                grid-template-columns: repeat(7, minmax(120px, 1fr));
            }

            .agenda-grid--week {
                grid-template-columns: repeat(7, minmax(150px, 1fr));
            }

            .agenda-grid--day {
                grid-template-columns: 1fr;
            }

            .day-column {
                min-height: 140px;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                background: #fafdff;
                padding: 12px;
            }

            .agenda-grid--week .day-column {
                min-height: 440px;
            }

            .agenda-grid--day .day-column {
                min-height: 520px;
            }

            .day-column.is-outside-month {
                opacity: .45;
            }

            .day-column.is-today {
                border-color: #ff6bb3;
            }

            .day-column.has-events {
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

            .event-item {
                border-left: 5px solid var(--calendar-color, #008f91);
                border-radius: 8px;
                background: #ffffff;
                padding: 10px;
                box-shadow: 0 10px 24px rgba(13, 43, 43, .07);
            }

            .event-item + .event-item {
                margin-top: 10px;
            }

            .event-item.is-cancelled {
                opacity: .62;
                background: #f7f7f7;
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

            .dashboard-list-event {
                display: grid;
                grid-template-columns: 150px minmax(0, 1fr) auto;
                gap: 14px;
                align-items: center;
                padding: 14px 0;
                border-bottom: 1px solid #dbe7e7;
            }

            .smart-feedback {
                display: none;
                border-radius: 8px;
                border: 1px solid #b8eeee;
                background: #e5ffff;
                padding: 12px;
                color: #0d2b2b;
                font-weight: 700;
            }

            .smart-feedback.is-visible {
                display: block;
            }

            @media (max-width: 1100px) {
                .dashboard-shell {
                    grid-template-columns: 1fr;
                }

                .dashboard-sidebar {
                    position: static;
                    height: auto;
                }

                .dashboard-topbar {
                    grid-template-columns: 1fr;
                }

                .agenda-grid--month,
                .agenda-grid--week {
                    grid-template-columns: repeat(7, minmax(130px, 1fr));
                }

                .dashboard-list-event {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="dashboard-shell">
            <aside class="dashboard-sidebar dashboard-card">
                <div class="mb-6">
                    <p class="text-xs font-black uppercase tracking-[.22em] text-[#008f91]">Menu</p>
                    <h2 class="mt-1 text-xl font-black text-[#0d2b2b]">Agenda Inteligente</h2>
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
                    <a href="{{ route('event-reminder-tester') }}" class="sidebar-link">
                        <span>Lembretes</span>
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

                        <div class="space-y-3">
                            @forelse ($eventReminders as $reminder)
                                @php
                                    $reminderType = $reminder->type instanceof BackedEnum ? $reminder->type->value : $reminder->type;
                                    $notificationAt = $reminder->event?->startAt?->copy()->subMinutes($reminder->minutesBefore);
                                    $reminderTypeLabels = [
                                        'notification' => 'Notifica&ccedil;&atilde;o',
                                        'email' => 'E-mail',
                                        'whatsapp' => 'WhatsApp',
                                    ];
                                @endphp

                                <a href="{{ route('event-reminder-tester') }}" class="block rounded-lg border border-[#dbe7e7] p-3 transition hover:border-[#008f91] hover:bg-[#fafdff]">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-black text-[#0d2b2b]">
                                                {{ $reminder->event?->title ?? 'Evento sem t&iacute;tulo' }}
                                            </p>
                                            <p class="mt-1 truncate text-xs font-bold text-gray-500">
                                                {{ $reminder->event?->calendar?->name ?? 'Sem calend&aacute;rio' }}
                                            </p>
                                        </div>
                                        <span class="status-pill {{ $reminder->isSent ? '' : 'is-high' }}">
                                            {{ $reminder->isSent ? 'Enviado' : 'Pendente' }}
                                        </span>
                                    </div>

                                    <div class="mt-3 grid gap-1 text-xs font-bold text-gray-500">
                                        <span>
                                            Avisar {{ $reminder->minutesBefore }} min antes
                                        </span>
                                        <span>
                                            {{ $notificationAt?->format('d/m H:i') ?? '-' }}
                                            &middot;
                                            {!! $reminderTypeLabels[$reminderType] ?? $reminderType !!}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-lg border border-dashed border-[#cfe0e0] p-4 text-sm font-semibold text-gray-500">
                                    Nenhum lembrete configurado para seus eventos.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <script>
            const smartRequestStoreUrl = '/api/smart-requests';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            document.getElementById('smartCommandForm')?.addEventListener('submit', async (event) => {
                event.preventDefault();

                const input = document.getElementById('smartCommandInput');
                const feedback = document.getElementById('smartCommandFeedback');
                const rawText = input.value.trim();

                if (rawText.length < 5) {
                    feedback.className = 'smart-feedback is-visible';
                    feedback.textContent = 'Digite uma solicitacao com pelo menos 5 caracteres.';
                    return;
                }

                feedback.className = 'smart-feedback is-visible';
                feedback.textContent = 'Analisando sua solicitacao...';

                try {
                    const response = await fetch(smartRequestStoreUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ rawText }),
                    });

                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message || 'Nao foi possivel registrar a solicitacao.');
                    }

                    const request = payload.data || payload;
                    const status = request.status || 'pending';
                    const needsConfirmation = status === 'needs_confirmation';
                    const suggestingTimes = status === 'suggesting_times';

                    feedback.innerHTML = `
                        <div>${needsConfirmation ? 'Encontrei dados suficientes. Deseja confirmar este evento?' : suggestingTimes ? 'Encontrei um conflito. Veja sugest&otilde;es de hor&aacute;rios alternativos.' : 'Solicita&ccedil;&atilde;o registrada para an&aacute;lise.'}</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            ${needsConfirmation ? `<button type="button" class="eos-action eos-action--primary" data-confirm-request="${request.id}">Confirmar</button>` : ''}
                            <a class="eos-action eos-action--secondary" href="{{ route('smart-requests.index') }}">Editar</a>
                            <a class="eos-action eos-action--secondary" href="{{ route('smart-requests.index') }}">Ver sugest&otilde;es</a>
                        </div>
                    `;

                    input.value = '';
                } catch (error) {
                    feedback.className = 'smart-feedback is-visible';
                    feedback.textContent = error.message;
                }
            });

            document.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-confirm-request]');

                if (!button) {
                    return;
                }

                button.disabled = true;
                button.textContent = 'Confirmando...';

                try {
                    const response = await fetch(`/api/smart-requests/${button.dataset.confirmRequest}/confirm`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    if (!response.ok) {
                        const payload = await response.json();
                        throw new Error(payload.message || 'Nao foi possivel confirmar.');
                    }

                    button.textContent = 'Confirmado';
                    window.setTimeout(() => window.location.reload(), 800);
                } catch (error) {
                    button.disabled = false;
                    button.textContent = 'Confirmar';
                    document.getElementById('smartCommandFeedback').textContent = error.message;
                }
            });

            document.getElementById('dashboardSearch')?.addEventListener('input', (event) => {
                const query = event.target.value.trim().toLowerCase();

                document.querySelectorAll('.js-searchable').forEach((item) => {
                    const content = item.dataset.search || item.textContent.toLowerCase();
                    item.hidden = query.length > 0 && !content.includes(query);
                });
            });
        </script>
    </div>
</x-app-layout>
