<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .pref-page {
                max-width: 1200px;
                margin: 0 auto;
                padding: 24px;
            }

            .pref-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .pref-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .pref-title {
                font-size: 1.35rem;
                font-weight: 900;
                color: #0d2b2b;
            }

            .pref-subtitle {
                font-size: .8rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                letter-spacing: .18em;
            }

            .pref-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 18px;
                padding: 18px;
            }

            .pref-group-label {
                font-size: .72rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                letter-spacing: .12em;
                margin-bottom: 14px;
            }

            .form-field {
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                padding: 14px;
                background: #fafdff;
            }

            .form-field + .form-field {
                margin-top: 10px;
            }

            .form-field label {
                display: block;
                font-size: .75rem;
                font-weight: 700;
                color: #748686;
                margin-bottom: 6px;
            }

            .form-field input[type="number"],
            .form-field input[type="time"] {
                display: block;
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 6px;
                padding: 8px 10px;
                font-size: .9rem;
                font-weight: 700;
                color: #0d2b2b;
                background: #ffffff;
                outline: none;
            }

            .form-field input:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 2px rgba(0, 143, 145, .15);
            }

            .toggle-group {
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                padding: 14px;
                background: #fafdff;
                margin-top: 10px;
            }

            .toggle-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 8px 0;
            }

            .toggle-item + .toggle-item {
                border-top: 1px solid #dbe7e7;
            }

            .toggle-item span {
                font-size: .85rem;
                font-weight: 700;
                color: #0d2b2b;
            }

            .toggle-item input[type="checkbox"] {
                width: 16px;
                height: 16px;
                border-radius: 4px;
                border: 1px solid #cfe0e0;
                accent-color: #008f91;
                cursor: pointer;
                flex-shrink: 0;
            }

            .method-badge {
                border-radius: 4px;
                padding: 4px 10px;
                font-size: .78rem;
                font-weight: 900;
                background: #fff8e5;
                color: #92400e;
            }

            .btn-action {
                border: 1px solid #cfe0e0;
                background: #ffffff;
                color: #365050;
                padding: 8px 14px;
                font-size: .85rem;
                font-weight: 800;
                border-radius: 8px;
                text-decoration: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
                background: #0d2b2b;
                border-color: #0d2b2b;
                color: #ffffff;
                margin-top: 14px;
                padding: 10px 14px;
            }

            .btn-primary:hover {
                background: #008f91;
                border-color: #008f91;
            }

            @media (max-width: 900px) {
                .pref-toolbar {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .pref-layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="pref-page">
            <section class="pref-card">
                <div class="pref-toolbar">
                    <div>
                        <p class="pref-subtitle">Preferências do usuário</p>
                        <h2 class="pref-title">Atualizar configurações</h2>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span class="method-badge">PATCH /api/user-preferences</span>
                        <a href="{{ route('user-preferences.show-tester') }}" class="btn-action">
                            Consultar preferências
                        </a>
                    </div>
                </div>

                <div class="pref-layout">
                    <div>
                        <p class="pref-group-label">Campos do formulário</p>

                        <form id="update-form">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                <div class="form-field">
                                    <label for="defaultEventDurationMinutes">Duração de evento (min)</label>
                                    <input id="defaultEventDurationMinutes" name="defaultEventDurationMinutes" type="number" min="5" max="1440" value="60">
                                </div>
                                <div class="form-field">
                                    <label for="defaultMeetingDurationMinutes">Duração de reunião (min)</label>
                                    <input id="defaultMeetingDurationMinutes" name="defaultMeetingDurationMinutes" type="number" min="5" max="480" value="30">
                                </div>
                                <div class="form-field">
                                    <label for="preferredStartTime">Horário de início preferido</label>
                                    <input id="preferredStartTime" name="preferredStartTime" type="time" value="09:00">
                                </div>
                                <div class="form-field">
                                    <label for="preferredEndTime">Horário de término preferido</label>
                                    <input id="preferredEndTime" name="preferredEndTime" type="time" value="18:00">
                                </div>
                            </div>

                            <div class="form-field" style="margin-top:10px;">
                                <label for="bufferBetweenEventsMinutes">Intervalo entre eventos (min)</label>
                                <input id="bufferBetweenEventsMinutes" name="bufferBetweenEventsMinutes" type="number" min="0" max="180" value="15">
                            </div>

                            <div class="toggle-group">
                                <div class="toggle-item">
                                    <span>Exigir confirmação antes de criar</span>
                                    <input name="requireConfirmationBeforeCreate" type="checkbox" checked>
                                </div>
                                <div class="toggle-item">
                                    <span>Criar link de reunião automaticamente</span>
                                    <input name="autoCreateMeetingLink" type="checkbox">
                                </div>
                                <div class="toggle-item">
                                    <span>Criar lembrete automaticamente</span>
                                    <input name="autoCreateReminder" type="checkbox" checked>
                                </div>
                            </div>

                            <button type="submit" class="btn-action btn-primary">
                                Salvar preferências
                            </button>
                        </form>
                    </div>

                    <div style="background:#0f1923;border:1px solid #1e3040;border-radius:8px;padding:18px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:16px;">
                            <h3 style="font-weight:900;color:#ffffff;font-size:.9rem;">Resposta da API</h3>
                            <span id="status" style="background:#1e3040;color:#94b3b3;border-radius:4px;padding:3px 10px;font-size:.75rem;font-weight:700;">Pronto</span>
                        </div>
                        <pre id="output" style="min-height:420px;overflow:auto;white-space:pre-wrap;font-size:.82rem;line-height:1.7;color:#4ade80;">Preencha o formulário e envie.</pre>
                    </div>
                </div>
            </section>
        </div>

        <script>
            const form = document.getElementById('update-form');
            const output = document.getElementById('output');
            const status = document.getElementById('status');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                status.textContent = 'Enviando...';

                const values = new FormData(form);
                const payload = {
                    defaultEventDurationMinutes:    Number(values.get('defaultEventDurationMinutes')),
                    defaultMeetingDurationMinutes:  Number(values.get('defaultMeetingDurationMinutes')),
                    preferredStartTime:             values.get('preferredStartTime'),
                    preferredEndTime:               values.get('preferredEndTime'),
                    bufferBetweenEventsMinutes:     Number(values.get('bufferBetweenEventsMinutes')),
                    requireConfirmationBeforeCreate: values.has('requireConfirmationBeforeCreate'),
                    autoCreateMeetingLink:          values.has('autoCreateMeetingLink'),
                    autoCreateReminder:             values.has('autoCreateReminder'),
                };

                try {
                    const response = await fetch('/api/user-preferences', {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await response.json();

                    status.textContent = `${response.status} ${response.ok ? 'OK' : 'Erro'}`;
                    output.textContent = JSON.stringify({ request: payload, response: data }, null, 2);
                } catch (error) {
                    status.textContent = 'Erro';
                    output.textContent = JSON.stringify({ error: error.message }, null, 2);
                }
            });
        </script>
    </div>
</x-app-layout>
