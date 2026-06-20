<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .settings-page {
                max-width: 760px;
                margin: 0 auto;
                padding: 32px 24px 64px;
            }

            /* ── Header ─────────────────────────────────────────── */
            .settings-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 6px;
            }

            .settings-page-title {
                font-size: 1.55rem;
                font-weight: 900;
                color: #0d2b2b;
                line-height: 1.2;
            }

            .settings-page-desc {
                margin-top: 6px;
                font-size: .92rem;
                font-weight: 600;
                color: #647878;
            }

            .settings-hint {
                font-size: .82rem;
                font-weight: 600;
                color: #94a9a9;
                margin-bottom: 28px;
                padding-bottom: 20px;
                border-bottom: 1px solid #dbe7e7;
            }

            /* ── Feedback ────────────────────────────────────────── */
            .settings-feedback {
                display: none;
                border-radius: 8px;
                padding: 12px 16px;
                font-size: .88rem;
                font-weight: 800;
                margin-bottom: 20px;
            }

            .settings-feedback.is-success {
                display: flex;
                align-items: center;
                gap: 10px;
                border: 1px solid #b8eeee;
                background: #e5ffff;
                color: #0d6b6d;
            }

            .settings-feedback.is-error {
                display: flex;
                align-items: center;
                gap: 10px;
                border: 1px solid #f3b4b4;
                background: #fff0f0;
                color: #a32222;
            }

            /* ── Card ────────────────────────────────────────────── */
            .settings-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 10px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .05);
                margin-bottom: 16px;
            }

            .settings-card__header {
                padding: 18px 22px 14px;
                border-bottom: 1px solid #dbe7e7;
            }

            .settings-card__eyebrow {
                font-size: .7rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                letter-spacing: .18em;
                margin-bottom: 4px;
            }

            .settings-card__title {
                font-size: 1rem;
                font-weight: 900;
                color: #0d2b2b;
            }

            .settings-card__desc {
                margin-top: 3px;
                font-size: .82rem;
                font-weight: 600;
                color: #8a9f9f;
            }

            .settings-card__body {
                padding: 6px 0;
            }

            /* ── Row ─────────────────────────────────────────────── */
            .settings-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 14px 22px;
            }

            .settings-row + .settings-row {
                border-top: 1px solid #f0f7f7;
            }

            .settings-row__label {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .settings-row__label span {
                font-size: .92rem;
                font-weight: 700;
                color: #0d2b2b;
            }

            .settings-row__label small {
                font-size: .78rem;
                font-weight: 600;
                color: #8a9f9f;
            }

            .settings-row__control {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-shrink: 0;
            }

            /* ── Inputs ──────────────────────────────────────────── */
            .settings-input {
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #f6fbfb;
                color: #0d2b2b;
                padding: 8px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .settings-input:focus {
                border-color: #008f91;
                background: #ffffff;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .settings-input--number {
                width: 88px;
                text-align: right;
            }

            .settings-input--time {
                width: 112px;
            }

            .settings-unit {
                font-size: .82rem;
                font-weight: 700;
                color: #8a9f9f;
                white-space: nowrap;
            }

            /* ── Toggle switch ───────────────────────────────────── */
            .settings-switch {
                position: relative;
                display: inline-block;
                width: 46px;
                height: 26px;
                flex-shrink: 0;
                cursor: pointer;
            }

            .settings-switch input {
                position: absolute;
                opacity: 0;
                width: 0;
                height: 0;
            }

            .settings-switch__track {
                position: absolute;
                inset: 0;
                border-radius: 999px;
                background: #cfe0e0;
                border: 2px solid #b8d4d4;
                transition: background .2s ease, border-color .2s ease;
            }

            .settings-switch__track::after {
                content: '';
                position: absolute;
                left: 2px;
                top: 2px;
                width: 18px;
                height: 18px;
                border-radius: 999px;
                background: #ffffff;
                box-shadow: 0 1px 4px rgba(0, 0, 0, .18);
                transition: transform .2s ease;
            }

            .settings-switch input:checked + .settings-switch__track {
                background: #008f91;
                border-color: #007073;
            }

            .settings-switch input:checked + .settings-switch__track::after {
                transform: translateX(20px);
            }

            .settings-switch input:focus-visible + .settings-switch__track {
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .2);
            }

            /* ── Buttons ─────────────────────────────────────────── */
            .settings-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 18px;
                font-size: .86rem;
                font-weight: 900;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
                white-space: nowrap;
                text-decoration: none;
            }

            .settings-btn:hover:not(:disabled) {
                transform: translate(-1px, -1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .settings-btn:disabled {
                opacity: .5;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            .settings-btn--primary {
                background: #008f91;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .settings-btn--primary:hover:not(:disabled) {
                background: #007073;
            }

            .settings-btn--ghost {
                background: #ffffff;
                color: #0d2b2b;
            }

            /* ── Footer ──────────────────────────────────────────── */
            .settings-footer {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 10px;
                padding-top: 8px;
            }

            /* ── Responsive ──────────────────────────────────────── */
            @media (max-width: 600px) {
                .settings-header {
                    flex-direction: column;
                }

                .settings-row {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .settings-input--number {
                    width: 100%;
                }

                .settings-input--time {
                    width: 100%;
                }
            }
        </style>

        <div class="settings-page">

            {{-- Header --}}
            <div class="settings-header">
                <div>
                    <h1 class="settings-page-title">Preferências</h1>
                    <p class="settings-page-desc">Configure seus horários, eventos, lembretes e comportamento da agenda inteligente.</p>
                </div>
                <button id="saveTopBtn" type="button" class="settings-btn settings-btn--primary">
                    Salvar alterações
                </button>
            </div>

            <p class="settings-hint">
                Essas configurações serão usadas como padrão ao criar eventos manualmente ou por solicitação inteligente.
            </p>

            {{-- Feedback --}}
            <div id="feedback" class="settings-feedback" role="status"></div>

            <form id="prefForm">

                {{-- Card 1: Duração padrão --}}
                <div class="settings-card">
                    <div class="settings-card__header">
                        <p class="settings-card__eyebrow">Eventos</p>
                        <h2 class="settings-card__title">Duração padrão</h2>
                        <p class="settings-card__desc">Defina os padrões usados na criação de eventos e reuniões.</p>
                    </div>
                    <div class="settings-card__body">
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Evento comum</span>
                            </div>
                            <div class="settings-row__control">
                                <input id="defaultEventDurationMinutes" class="settings-input settings-input--number" type="number" min="5" max="1440">
                                <span class="settings-unit">minutos</span>
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Reunião</span>
                            </div>
                            <div class="settings-row__control">
                                <input id="defaultMeetingDurationMinutes" class="settings-input settings-input--number" type="number" min="5" max="480">
                                <span class="settings-unit">minutos</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Horário preferido --}}
                <div class="settings-card">
                    <div class="settings-card__header">
                        <p class="settings-card__eyebrow">Horários</p>
                        <h2 class="settings-card__title">Horário preferido</h2>
                        <p class="settings-card__desc">A agenda usará esse intervalo para sugerir horários e evitar compromissos fora do seu período ideal.</p>
                    </div>
                    <div class="settings-card__body">
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Início</span>
                            </div>
                            <div class="settings-row__control">
                                <input id="preferredStartTime" class="settings-input settings-input--time" type="time">
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Fim</span>
                            </div>
                            <div class="settings-row__control">
                                <input id="preferredEndTime" class="settings-input settings-input--time" type="time">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Organização --}}
                <div class="settings-card">
                    <div class="settings-card__header">
                        <p class="settings-card__eyebrow">Agenda</p>
                        <h2 class="settings-card__title">Organização da agenda</h2>
                        <p class="settings-card__desc">Esse tempo será considerado ao sugerir horários livres entre compromissos.</p>
                    </div>
                    <div class="settings-card__body">
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Intervalo entre eventos</span>
                            </div>
                            <div class="settings-row__control">
                                <input id="bufferBetweenEventsMinutes" class="settings-input settings-input--number" type="number" min="0" max="180">
                                <span class="settings-unit">minutos</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Criação inteligente --}}
                <div class="settings-card">
                    <div class="settings-card__header">
                        <p class="settings-card__eyebrow">IA e confirmações</p>
                        <h2 class="settings-card__title">Criação inteligente</h2>
                        <p class="settings-card__desc">Se a confirmação estiver ativa, a IA sempre vai mostrar os dados extraídos antes de criar o evento.</p>
                    </div>
                    <div class="settings-card__body">
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Exigir confirmação antes de criar eventos</span>
                                <small>A IA pedirá sua aprovação antes de criar qualquer evento.</small>
                            </div>
                            <label class="settings-switch" title="Exigir confirmação">
                                <input id="requireConfirmationBeforeCreate" type="checkbox">
                                <span class="settings-switch__track"></span>
                            </label>
                        </div>
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Criar link de reunião automaticamente</span>
                                <small>Gerar link de videoconferência ao criar reuniões.</small>
                            </div>
                            <label class="settings-switch" title="Link automático">
                                <input id="autoCreateMeetingLink" type="checkbox">
                                <span class="settings-switch__track"></span>
                            </label>
                        </div>
                        <div class="settings-row">
                            <div class="settings-row__label">
                                <span>Criar lembrete automaticamente</span>
                                <small>Adicionar lembrete padrão a novos eventos criados.</small>
                            </div>
                            <label class="settings-switch" title="Lembrete automático">
                                <input id="autoCreateReminder" type="checkbox">
                                <span class="settings-switch__track"></span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="settings-footer">
                    <button id="cancelBtn" type="button" class="settings-btn settings-btn--ghost">Cancelar</button>
                    <button id="saveBtn" type="submit" class="settings-btn settings-btn--primary">Salvar alterações</button>
                </div>
            </form>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let savedPrefs = null;

            async function api(url, options = {}) {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    ...options,
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message || 'Não foi possível concluir a operação.');
                }

                return payload;
            }

            function showFeedback(message, type = 'success') {
                const el = document.getElementById('feedback');
                el.textContent = message;
                el.className = `settings-feedback ${type === 'error' ? 'is-error' : 'is-success'}`;
                el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function clearFeedback() {
                const el = document.getElementById('feedback');
                el.textContent = '';
                el.className = 'settings-feedback';
            }

            function fillForm(prefs) {
                document.getElementById('defaultEventDurationMinutes').value    = prefs.defaultEventDurationMinutes ?? '';
                document.getElementById('defaultMeetingDurationMinutes').value  = prefs.defaultMeetingDurationMinutes ?? '';
                document.getElementById('preferredStartTime').value             = prefs.preferredStartTime ?? '';
                document.getElementById('preferredEndTime').value               = prefs.preferredEndTime ?? '';
                document.getElementById('bufferBetweenEventsMinutes').value     = prefs.bufferBetweenEventsMinutes ?? '';
                document.getElementById('requireConfirmationBeforeCreate').checked = Boolean(prefs.requireConfirmationBeforeCreate);
                document.getElementById('autoCreateMeetingLink').checked            = Boolean(prefs.autoCreateMeetingLink);
                document.getElementById('autoCreateReminder').checked               = Boolean(prefs.autoCreateReminder);
            }

            function timeValue(id) {
                const val = document.getElementById(id).value;
                if (!val) return null;
                return val.substring(0, 5); // normaliza para HH:MM, ignorando segundos que alguns browsers retornam
            }

            function buildPayload() {
                return {
                    defaultEventDurationMinutes:     Number(document.getElementById('defaultEventDurationMinutes').value),
                    defaultMeetingDurationMinutes:   Number(document.getElementById('defaultMeetingDurationMinutes').value),
                    preferredStartTime:              timeValue('preferredStartTime'),
                    preferredEndTime:                timeValue('preferredEndTime'),
                    bufferBetweenEventsMinutes:      Number(document.getElementById('bufferBetweenEventsMinutes').value),
                    requireConfirmationBeforeCreate: document.getElementById('requireConfirmationBeforeCreate').checked,
                    autoCreateMeetingLink:           document.getElementById('autoCreateMeetingLink').checked,
                    autoCreateReminder:              document.getElementById('autoCreateReminder').checked,
                };
            }

            async function loadPrefs() {
                try {
                    const response = await api('/api/user-preferences');
                    savedPrefs = response.data ?? response;
                    fillForm(savedPrefs);
                } catch (error) {
                    showFeedback('Não foi possível carregar as preferências. Tente recarregar a página.', 'error');
                }
            }

            async function savePrefs() {
                clearFeedback();

                const saveBtn = document.getElementById('saveBtn');
                const saveTopBtn = document.getElementById('saveTopBtn');
                saveBtn.disabled = true;
                saveTopBtn.disabled = true;

                try {
                    const response = await api('/api/user-preferences', {
                        method: 'PATCH',
                        body: JSON.stringify(buildPayload()),
                    });

                    savedPrefs = response.data ?? response;
                    fillForm(savedPrefs);
                    showFeedback('Preferências atualizadas com sucesso.');
                } catch (error) {
                    showFeedback(error.message || 'Não foi possível salvar suas preferências. Revise os campos e tente novamente.', 'error');
                } finally {
                    saveBtn.disabled = false;
                    saveTopBtn.disabled = false;
                }
            }

            document.getElementById('prefForm').addEventListener('submit', (event) => {
                event.preventDefault();
                savePrefs();
            });

            document.getElementById('saveTopBtn').addEventListener('click', savePrefs);

            document.getElementById('cancelBtn').addEventListener('click', () => {
                if (savedPrefs) {
                    fillForm(savedPrefs);
                    clearFeedback();
                }
            });

            loadPrefs();
        </script>
    </div>
</x-app-layout>
