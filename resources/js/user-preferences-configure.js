(() => {
    const root = document;

    const output = root.getElementById('output');
    const status = root.getElementById('status');
    const prefsDisplay = root.getElementById('prefs-display');

    const csrfToken = root.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const labels = {
        defaultEventDurationMinutes: 'Duração padrão de evento (min)',
        defaultMeetingDurationMinutes: 'Duração padrão de reunião (min)',
        preferredStartTime: 'Horário de início preferido',
        preferredEndTime: 'Horário de término preferido',
        bufferBetweenEventsMinutes: 'Intervalo entre eventos (min)',
        requireConfirmationBeforeCreate: 'Exigir confirmação antes de criar',
        autoCreateMeetingLink: 'Criar link de reunião automaticamente',
        autoCreateReminder: 'Criar lembrete automaticamente',
    };

    const boolFields = new Set([
        'requireConfirmationBeforeCreate',
        'autoCreateMeetingLink',
        'autoCreateReminder',
    ]);

    function renderPrefs(data) {
        const d = data?.data ?? data;
        if (!prefsDisplay) return;

        let html = '<p class="pref-group-label">Configurações carregadas</p>';

        for (const [key, label] of Object.entries(labels)) {
            const val = d?.[key];
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

    async function loadPrefs() {
        if (!output || !status) return;

        status.textContent = 'Carregando...';

        try {
            const response = await fetch('/api/user-preferences', {
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
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
    }

    const btn = root.getElementById('send-request');
    if (btn) btn.addEventListener('click', loadPrefs);
})();

