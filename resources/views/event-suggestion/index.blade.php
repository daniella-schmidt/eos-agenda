<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .es-page {
                max-width: 1280px;
                margin: 0 auto;
                padding: 24px;
            }

            .es-shell {
                display: grid;
                grid-template-columns: minmax(320px, 400px) minmax(0, 1fr);
                gap: 20px;
                align-items: start;
            }

            .es-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .es-card__header {
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .es-card__body {
                padding: 18px;
            }

            .es-eyebrow {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
            }

            .es-title {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: 1.35rem;
                font-weight: 900;
            }

            .es-heading {
                color: #0d2b2b;
                font-size: 1.8rem;
                font-weight: 900;
            }

            .es-muted {
                color: #647878;
                font-size: .9rem;
                font-weight: 600;
            }

            .es-back {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #008f91;
                font-size: .85rem;
                font-weight: 900;
                text-decoration: none;
                margin-bottom: 10px;
            }

            .es-back:hover { text-decoration: underline; }

            .es-quote {
                border-left: 4px solid #008f91;
                background: #f0ffff;
                border-radius: 0 8px 8px 0;
                padding: 12px 14px;
                color: #0d2b2b;
                font-size: .95rem;
                font-weight: 700;
                font-style: italic;
                line-height: 1.55;
            }

            .es-info-grid {
                display: grid;
                gap: 10px;
                margin-top: 14px;
            }

            .es-info-row {
                display: flex;
                align-items: baseline;
                gap: 8px;
            }

            .es-info-label {
                flex-shrink: 0;
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .1em;
                text-transform: uppercase;
                width: 110px;
            }

            .es-info-value {
                color: #0d2b2b;
                font-size: .88rem;
                font-weight: 700;
            }

            .es-status {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 4px 10px;
                font-size: .72rem;
                font-weight: 900;
                background: var(--s-bg, #e5ffff);
                color: var(--s-text, #006b6d);
            }

            .es-field {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .es-field label {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .es-field input {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #ffffff;
                color: #0d2b2b;
                padding: 10px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .es-field input:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .es-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 16px;
                font-size: .86rem;
                font-weight: 900;
                text-decoration: none;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
                white-space: nowrap;
            }

            .es-btn:hover {
                transform: translate(-1px, -1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .es-btn:disabled {
                cursor: not-allowed;
                opacity: .55;
                transform: none;
                box-shadow: none;
            }

            .es-btn--primary {
                background: #008f91;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
                width: 100%;
            }

            .es-btn--ghost  { background: #ffffff; color: #0d2b2b; }
            .es-btn--success { background: #e6fff5; border-color: #16a34a; color: #15803d; }
            .es-btn--danger  { background: #fff0f0; border-color: #c0392b; color: #c0392b; }

            .es-feedback {
                display: none;
                border-radius: 8px;
                border: 1px solid #b8eeee;
                background: #e5ffff;
                color: #0d2b2b;
                padding: 12px;
                font-size: .88rem;
                font-weight: 800;
            }

            .es-feedback.is-visible { display: block; }

            .es-feedback.is-error {
                border-color: #f3b4b4;
                background: #fff0f0;
                color: #a32222;
            }

            .es-empty {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 28px 20px;
                text-align: center;
                background: #ffffff;
                color: #647878;
                font-size: .9rem;
                font-weight: 700;
            }

            .es-empty__title {
                margin-bottom: 6px;
                color: #0d2b2b;
                font-size: 1rem;
                font-weight: 900;
            }

            .es-suggestions {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .es-suggestion {
                border: 1px solid #dbe7e7;
                border-radius: 10px;
                padding: 18px;
                background: #fafdff;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .es-suggestion.is-top-pick {
                border-color: #008f91;
            }

            .es-suggestion.is-selected {
                border-color: #16a34a;
                background: #f0fff8;
            }

            .es-suggestion:hover {
                border-color: #008f91;
                box-shadow: 0 6px 18px rgba(0, 143, 145, .10);
            }

            .es-suggestion__badges {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-bottom: 10px;
            }

            .es-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                border-radius: 999px;
                padding: 3px 9px;
                font-size: .72rem;
                font-weight: 900;
            }

            .es-badge--top    { background: #e5ffff; color: #006b6d; }
            .es-badge--selected { background: #e6fff5; color: #15803d; }

            .es-suggestion__datetime {
                color: #0d2b2b;
                font-size: 1.05rem;
                font-weight: 900;
            }

            .es-suggestion__time {
                margin-top: 2px;
                color: #008f91;
                font-size: .9rem;
                font-weight: 900;
            }

            .es-score-bar {
                margin-top: 14px;
            }

            .es-score-bar__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 5px;
                font-size: .75rem;
                font-weight: 900;
                color: #647878;
            }

            .es-score-bar__track {
                height: 6px;
                border-radius: 999px;
                background: #dbe7e7;
                overflow: hidden;
            }

            .es-score-bar__fill {
                height: 100%;
                border-radius: 999px;
                background: var(--score-color, #008f91);
                transition: width .4s ease;
            }

            .es-suggestion__reason {
                margin-top: 12px;
                color: #526767;
                font-size: .88rem;
                font-weight: 600;
                line-height: 1.55;
            }

            .es-suggestion__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 14px;
            }

            .es-confirm-area {
                display: none;
                border: 1px solid #b8eeee;
                border-radius: 8px;
                background: #e5ffff;
                padding: 16px;
                margin-top: 14px;
                gap: 10px;
                flex-direction: column;
            }

            .es-confirm-area.is-visible { display: flex; }

            .es-confirm-area__title {
                color: #006b6d;
                font-size: .88rem;
                font-weight: 900;
            }

            .es-confirm-area__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            @media (max-width: 980px) {
                .es-shell { grid-template-columns: 1fr; }
            }
        </style>

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

        <script>
            const csrfToken    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const smartRequestId = {{ $smartRequest->id }};
            const calendarBaseUrl = "{{ route('calendars.index') }}";

            let suggestions = [];
            let hasSelected  = false;

            // ── Helpers ─────────────────────────────────────────────────────────

            const escHtml = v => String(v ?? '')
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

            function formatDate(value) {
                if (!value) return '-';
                return new Date(value).toLocaleString('pt-BR', {
                    weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
                });
            }

            function formatTime(value) {
                if (!value) return '-';
                return new Date(value).toLocaleString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            }

            function scorePercent(score) {
                const n = parseFloat(score) || 0;
                return n <= 1 ? Math.round(n * 100) : Math.round(n);
            }

            function scoreColor(pct) {
                if (pct >= 80) return '#16a34a';
                if (pct >= 55) return '#008f91';
                return '#ffb76b';
            }

            function showFeedback(id, message, type = 'success') {
                const el = document.getElementById(id);
                el.textContent = message;
                el.className = `es-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
            }

            function clearFeedback(id) {
                const el = document.getElementById(id);
                el.textContent = '';
                el.className = 'es-feedback';
            }

            // ── API ──────────────────────────────────────────────────────────────

            async function api(url, options = {}) {
                const res = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    ...options,
                });

                if (res.status === 204) return null;

                const payload = await res.json().catch(() => ({}));

                if (!res.ok) throw new Error(payload.message || 'Não foi possível concluir a operação.');

                return payload;
            }

            // ── Load suggestions ─────────────────────────────────────────────────

            async function loadSuggestions() {
                document.getElementById('suggestionsList').innerHTML =
                    '<div class="es-empty"><p class="es-empty__title">Buscando sugestões...</p></div>';

                try {
                    const payload = await api(`/api/smart-requests/${smartRequestId}/suggestions`);
                    suggestions = payload.data || [];
                    renderSuggestions();
                } catch (err) {
                    document.getElementById('suggestionsList').innerHTML =
                        `<div class="es-empty"><p class="es-empty__title">${escHtml(err.message)}</p></div>`;
                }
            }

            // ── Render ───────────────────────────────────────────────────────────

            function renderSuggestions() {
                const list = document.getElementById('suggestionsList');
                hasSelected = suggestions.some(s => s.selected);

                document.getElementById('confirmArea').classList.toggle('is-visible', hasSelected);

                if (!suggestions.length) {
                    list.innerHTML = `
                        <div class="es-empty">
                            <p class="es-empty__title">Nenhuma sugestão gerada ainda.</p>
                            <p>Informe os parâmetros ao lado e clique em <strong>Gerar sugestões</strong>.</p>
                        </div>`;
                    return;
                }

                list.innerHTML = `<div class="es-suggestions">${suggestions.map((s, index) => {
                    const pct     = scorePercent(s.score);
                    const color   = scoreColor(pct);
                    const isFirst = index === 0;
                    const dateStr = formatDate(s.suggestedStartAt);
                    const timeStr = `${formatTime(s.suggestedStartAt)} - ${formatTime(s.suggestedEndAt)}`;

                    const badges = [];
                    if (s.selected)       badges.push('<span class="es-badge es-badge--selected">✓ Selecionada</span>');
                    else if (isFirst && !hasSelected) badges.push('<span class="es-badge es-badge--top">★ Mais recomendada</span>');

                    const selectBtn = s.selected
                        ? `<span class="es-btn es-btn--ghost" style="cursor:default;opacity:.6">Já selecionada</span>`
                        : `<button class="es-btn es-btn--primary" type="button"
                                   style="width:auto;" data-select-id="${s.id}">
                               Selecionar este horário
                           </button>`;

                    return `
                        <div class="es-suggestion ${s.selected ? 'is-selected' : (isFirst && !hasSelected ? 'is-top-pick' : '')}">
                            ${badges.length ? `<div class="es-suggestion__badges">${badges.join('')}</div>` : ''}
                            <div class="es-suggestion__datetime">${escHtml(dateStr)}</div>
                            <div class="es-suggestion__time">${escHtml(timeStr)}</div>

                            <div class="es-score-bar">
                                <div class="es-score-bar__header">
                                    <span>Compatibilidade</span>
                                    <strong style="color:${color}">${pct}%</strong>
                                </div>
                                <div class="es-score-bar__track">
                                    <div class="es-score-bar__fill"
                                         style="width:${pct}%; --score-color:${color}"></div>
                                </div>
                            </div>

                            ${s.reason
                                ? `<p class="es-suggestion__reason">${escHtml(s.reason)}</p>`
                                : ''}

                            <div class="es-suggestion__actions">
                                ${selectBtn}
                            </div>
                        </div>`;
                }).join('')}</div>`;
            }

            // ── Generate suggestions ─────────────────────────────────────────────

            document.getElementById('generateBtn').addEventListener('click', async () => {
                clearFeedback('generateFeedback');

                const daysAhead = parseInt(document.getElementById('daysAhead').value) || 7;
                const limit     = parseInt(document.getElementById('limitInput').value) || 3;
                const btn       = document.getElementById('generateBtn');

                btn.disabled    = true;
                btn.textContent = 'Buscando horários...';

                try {
                    const payload = await api(`/api/smart-requests/${smartRequestId}/suggestions/generate`, {
                        method: 'POST',
                        body: JSON.stringify({ daysAhead, limit }),
                    });

                    suggestions = payload.data || [];
                    renderSuggestions();
                    showFeedback('generateFeedback', `${suggestions.length} sugestão(ões) gerada(s) com sucesso.`);
                } catch (err) {
                    showFeedback('generateFeedback', err.message, 'error');
                } finally {
                    btn.disabled    = false;
                    btn.textContent = 'Gerar sugestões';
                }
            });

            // ── Select suggestion ────────────────────────────────────────────────

            document.getElementById('suggestionsList').addEventListener('click', async (e) => {
                const btn = e.target.closest('[data-select-id]');
                if (!btn) return;

                const id = btn.dataset.selectId;
                clearFeedback('suggestionFeedback');
                btn.disabled    = true;
                btn.textContent = 'Selecionando...';

                try {
                    const payload = await api(`/api/event-suggestions/${id}/select`, { method: 'POST' });
                    const updated = payload.data;

                    suggestions = suggestions.map(s =>
                        String(s.id) === String(updated.id)
                            ? { ...s, selected: true }
                            : { ...s, selected: false }
                    );

                    renderSuggestions();
                    showFeedback('suggestionFeedback', 'Horário selecionado. Agora você pode confirmar o evento.');
                } catch (err) {
                    showFeedback('suggestionFeedback', err.message, 'error');
                    btn.disabled    = false;
                    btn.textContent = 'Selecionar este horário';
                }
            });

            // ── Confirm event ────────────────────────────────────────────────────

            document.getElementById('confirmEventBtn').addEventListener('click', async () => {
                const btn = document.getElementById('confirmEventBtn');
                clearFeedback('suggestionFeedback');
                btn.disabled    = true;
                btn.textContent = 'Criando evento...';

                try {
                    await api(`/api/smart-requests/${smartRequestId}/confirm`, { method: 'POST' });

                    showFeedback('suggestionFeedback', 'Evento criado com sucesso!');

                    const selected = suggestions.find(s => s.selected);
                    const date = selected?.suggestedStartAt
                        ? new Date(selected.suggestedStartAt).toISOString().slice(0, 10)
                        : new Date().toISOString().slice(0, 10);

                    const link = document.getElementById('calendarLink');
                    link.href = `${calendarBaseUrl}?view=day&date=${date}`;
                    link.style.display = 'inline-flex';

                    btn.textContent = 'Evento criado';
                } catch (err) {
                    showFeedback('suggestionFeedback', err.message, 'error');
                    btn.disabled    = false;
                    btn.textContent = 'Confirmar e criar evento';
                }
            });

            // ── Init ─────────────────────────────────────────────────────────────

            loadSuggestions();
        </script>
    </div>
</x-app-layout>
