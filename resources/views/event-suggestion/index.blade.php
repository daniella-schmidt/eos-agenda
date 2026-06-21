<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/event-suggestion.css', 'resources/js/event-suggestion.js'])

        <div class="es-page">
            <a href="{{ route('smart-requests.index') }}" class="es-back">← Voltar para solicitações inteligentes</a>

            <div class="mb-5">
                <p class="es-eyebrow">Sugestões de horários</p>
                <h1 class="es-heading">Escolha o melhor horário</h1>
                <p class="es-muted">Analise as opções disponíveis e selecione o horário ideal para criar seu evento.</p>
            </div>

            <div class="es-shell">
                {{-- Left column --}}
                <div class="space-y-5">

                    {{-- Smart request summary --}}
                    <section class="es-card">
                        <div class="es-card__header">
                            <p class="es-eyebrow">Solicitação original</p>
                            <h2 class="es-title">O que você pediu</h2>
                        </div>
                        <div class="es-card__body space-y-4">
                            <blockquote class="es-quote">
                                "{{ $smartRequest->rawText }}"
                            </blockquote>

                            <div class="es-info-grid">
                                @php
                                    $statusMap = [
                                        'pending'             => ['label' => 'Pendente',                'bg' => '#fff8c7', 'text' => '#645400'],
                                        'needs_more_info'     => ['label' => 'Precisa de dados',        'bg' => '#fff0df', 'text' => '#8a4d00'],
                                        'needs_confirmation'  => ['label' => 'Aguardando confirmação',  'bg' => '#e5ffff', 'text' => '#006b6d'],
                                        'suggesting_times'    => ['label' => 'Sugerindo horários',      'bg' => '#fff0df', 'text' => '#8a4d00'],
                                        'confirmed'           => ['label' => 'Confirmada',              'bg' => '#e6fff5', 'text' => '#15803d'],
                                        'completed'           => ['label' => 'Evento criado',           'bg' => '#e6fff5', 'text' => '#15803d'],
                                        'cancelled'           => ['label' => 'Cancelada',               'bg' => '#fff0f0', 'text' => '#a32222'],
                                        'failed'              => ['label' => 'Falhou',                  'bg' => '#fff0f0', 'text' => '#a32222'],
                                    ];
                                    $statusValue = $smartRequest->status instanceof \BackedEnum
                                        ? $smartRequest->status->value
                                        : $smartRequest->status;
                                    $statusInfo = $statusMap[$statusValue] ?? ['label' => $statusValue, 'bg' => '#e5ffff', 'text' => '#006b6d'];
                                @endphp

                                <div class="es-info-row">
                                    <span class="es-info-label">Status</span>
                                    <span class="es-status" style="--s-bg: {{ $statusInfo['bg'] }}; --s-text: {{ $statusInfo['text'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </div>

                                @if ($smartRequest->extractedTitle)
                                    <div class="es-info-row">
                                        <span class="es-info-label">Título</span>
                                        <span class="es-info-value">{{ $smartRequest->extractedTitle }}</span>
                                    </div>
                                @endif

                                @if ($smartRequest->extractedStartAt)
                                    <div class="es-info-row">
                                        <span class="es-info-label">Início</span>
                                        <span class="es-info-value">
                                            {{ $smartRequest->extractedStartAt->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @endif

                                @if ($smartRequest->extractedEndAt)
                                    <div class="es-info-row">
                                        <span class="es-info-label">Fim</span>
                                        <span class="es-info-value">
                                            {{ $smartRequest->extractedEndAt->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @endif

                                @if (!empty($smartRequest->extractedParticipants))
                                    <div class="es-info-row">
                                        <span class="es-info-label">Participantes</span>
                                        <span class="es-info-value">
                                            @foreach ($smartRequest->extractedParticipants as $p)
                                                {{ is_array($p) ? ($p['name'] ?? $p['email'] ?? '') : $p }}@if (!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    {{-- Generate form --}}
                    <section class="es-card">
                        <div class="es-card__header">
                            <p class="es-eyebrow">Gerar sugestões</p>
                            <h2 class="es-title">Encontrar horários</h2>
                        </div>
                        <div class="es-card__body space-y-4">
                            <p class="es-muted">
                                Informe o intervalo de busca e quantas opções deseja receber.
                            </p>

                            <div class="es-field">
                                <label for="daysAhead">Buscar nos próximos (dias)</label>
                                <input id="daysAhead" type="number" min="1" max="90" value="7">
                            </div>

                            <div class="es-field">
                                <label for="limitInput">Quantidade máxima de sugestões</label>
                                <input id="limitInput" type="number" min="1" max="10" value="3">
                            </div>

                            <button id="generateBtn" class="es-btn es-btn--primary" type="button">
                                Gerar sugestões
                            </button>

                            <div id="generateFeedback" class="es-feedback" role="status"></div>
                        </div>
                    </section>
                </div>

                {{-- Right column --}}
                <section class="es-card">
                    <div class="es-card__header">
                        <p class="es-eyebrow">Horários disponíveis</p>
                        <h2 class="es-title">Sugestões</h2>
                    </div>
                    <div class="es-card__body">
                        <div id="suggestionFeedback" class="es-feedback" role="status" style="margin-bottom:14px;"></div>

                        <div id="confirmArea" class="es-confirm-area" role="status">
                            <p class="es-confirm-area__title">
                                Horário selecionado. Deseja confirmar a criação do evento?
                            </p>
                            <div class="es-confirm-area__actions">
                                <button id="confirmEventBtn" class="es-btn es-btn--success" type="button">
                                    Confirmar e criar evento
                                </button>
                                <a id="calendarLink" href="{{ route('calendars.index') }}"
                                   class="es-btn es-btn--ghost" style="display:none">
                                    Ver na agenda
                                </a>
                            </div>
                        </div>

                        <div id="suggestionsList">
                            <div class="es-empty">
                                <p class="es-empty__title">Carregando sugestões...</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div
            id="eventSuggestionBoot"
            data-csrf-token="{{ csrf_token() }}"
            data-smart-request-id="{{ (int) $smartRequest->id }}"
            data-calendar-base-url="{{ route('calendars.index') }}"
    </div>
</x-app-layout>

