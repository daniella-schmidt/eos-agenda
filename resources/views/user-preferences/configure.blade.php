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
                grid-template-columns: 340px minmax(0, 1fr);
                gap: 18px;
                padding: 18px;
            }

            .pref-group-label {
                font-size: .72rem;
                font-weight: 900;
                color: #008f91;
                text-transform: uppercase;
                letter-spacing: .12em;
                margin-bottom: 12px;
            }

            .pref-item {
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                padding: 14px;
                background: #fafdff;
            }

            .pref-item + .pref-item {
                margin-top: 10px;
            }

            .pref-item__label {
                font-size: .75rem;
                font-weight: 700;
                color: #748686;
            }

            .pref-item__value {
                font-size: 1rem;
                font-weight: 900;
                color: #0d2b2b;
                margin-top: 4px;
            }

            .status-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 3px 10px;
                background: #e5ffff;
                color: #006b6d;
                font-size: .72rem;
                font-weight: 900;
            }

            .status-pill.is-off {
                background: #f5f5f5;
                color: #748686;
            }

            .method-badge {
                border-radius: 4px;
                padding: 4px 10px;
                font-size: .78rem;
                font-weight: 900;
                background: #e5f9ff;
                color: #006b6d;
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
                background: #0d2b2b;
                border-color: #0d2b2b;
                color: #ffffff;
            }

            .btn-primary:hover {
                background: #008f91;
                border-color: #008f91;
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
                        <h2 class="pref-title">Configurações</h2>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                        <span class="method-badge">GET /api/user-preferences</span>
                        <button id="send-request" type="button" class="btn-action btn-primary">
                            Consultar preferências
                        </button>
                        <a href="{{ route('user-preferences.update-tester') }}" class="btn-action">
                            Atualizar preferências
                        </a>
                    </div>
                </div>

                <div class="pref-layout">
                    <div id="prefs-display">
                        <div class="empty-state">
                            Clique em "Consultar preferências" para carregar os dados.
                        </div>
                    </div>

                    <div style="background:#0f1923;border:1px solid #1e3040;border-radius:8px;padding:18px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:16px;">
                            <h3 style="font-weight:900;color:#ffffff;font-size:.9rem;">Resposta da API</h3>
                            <span id="status" style="background:#1e3040;color:#94b3b3;border-radius:4px;padding:3px 10px;font-size:.75rem;font-weight:700;">Pronto</span>
                        </div>
                        <pre id="output" style="min-height:380px;overflow:auto;white-space:pre-wrap;font-size:.82rem;line-height:1.7;color:#4ade80;">Clique em "Consultar preferências".</pre>
                    </div>
                </div>
            </section>
        </div>

        <script>
            const output = document.getElementById('output');
            const status = document.getElementById('status');
            const prefsDisplay = document.getElementById('prefs-display');

            const labels = {
                defaultEventDurationMinutes:    'Duração padrão de evento (min)',
                defaultMeetingDurationMinutes:  'Duração padrão de reunião (min)',
                preferredStartTime:             'Horário de início preferido',
                preferredEndTime:               'Horário de término preferido',
                bufferBetweenEventsMinutes:     'Intervalo entre eventos (min)',
                requireConfirmationBeforeCreate:'Exigir confirmação antes de criar',
                autoCreateMeetingLink:          'Criar link de reunião automaticamente',
                autoCreateReminder:             'Criar lembrete automaticamente',
            };

            const boolFields = new Set([
                'requireConfirmationBeforeCreate',
                'autoCreateMeetingLink',
                'autoCreateReminder',
            ]);

            function renderPrefs(data) {
                const d = data.data ?? data;

                let html = '<p class="pref-group-label">Configurações carregadas</p>';

                for (const [key, label] of Object.entries(labels)) {
                    const val = d[key];
                    let valueHtml;

                    if (boolFields.has(key)) {
                        valueHtml = val
                            ? '<span class="status-pill">Ativado</span>'
                            : '<span class="status-pill is-off">Desativado</span>';
                    } else {
                        valueHtml = `<p class="pref-item__value">${val ?? '—'}</p>`;
                    }

                    html += `
                        <div class="pref-item">
                            <p class="pref-item__label">${label}</p>
                            <div class="mt-1">${valueHtml}</div>
                        </div>
                    `;
                }

                prefsDisplay.innerHTML = html;
            }

            document.getElementById('send-request').addEventListener('click', async () => {
                status.textContent = 'Carregando...';

                try {
                    const response = await fetch('/api/user-preferences', {
                        headers: { 'Accept': 'application/json' },
                    });
                    const data = await response.json();

                    status.textContent = `${response.status} ${response.ok ? 'OK' : 'Erro'}`;
                    output.textContent = JSON.stringify(data, null, 2);

                    if (response.ok) {
                        renderPrefs(data);
                    }
                } catch (error) {
                    status.textContent = 'Erro';
                    output.textContent = JSON.stringify({ error: error.message }, null, 2);
                }
            });
        </script>
    </div>
</x-app-layout>
