(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const root = document;

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
        const el = root.getElementById('feedback');
        if (!el) return;

        el.textContent = message;
        el.className = `settings-feedback ${type === 'error' ? 'is-error' : 'is-success'}`;
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearFeedback() {
        const el = root.getElementById('feedback');
        if (!el) return;
        el.textContent = '';
        el.className = 'settings-feedback';
    }

    function fillForm(prefs) {
        const setValue = (id, v) => {
            const input = root.getElementById(id);
            if (input) input.value = v ?? '';
        };

        setValue('defaultEventDurationMinutes', prefs.defaultEventDurationMinutes);
        setValue('defaultMeetingDurationMinutes', prefs.defaultMeetingDurationMinutes);
        setValue('preferredStartTime', prefs.preferredStartTime);
        setValue('preferredEndTime', prefs.preferredEndTime);
        setValue('bufferBetweenEventsMinutes', prefs.bufferBetweenEventsMinutes);

        const setChecked = (id, v) => {
            const input = root.getElementById(id);
            if (input) input.checked = Boolean(v);
        };

        setChecked('requireConfirmationBeforeCreate', prefs.requireConfirmationBeforeCreate);
        setChecked('autoCreateMeetingLink', prefs.autoCreateMeetingLink);
        setChecked('autoCreateReminder', prefs.autoCreateReminder);
    }

    function timeValue(id) {
        const input = root.getElementById(id);
        if (!input) return null;

        const val = input.value;
        if (!val) return null;

        return val.substring(0, 5); // normaliza HH:MM
    }

    function buildPayload() {
        return {
            defaultEventDurationMinutes: Number(root.getElementById('defaultEventDurationMinutes')?.value),
            defaultMeetingDurationMinutes: Number(root.getElementById('defaultMeetingDurationMinutes')?.value),
            preferredStartTime: timeValue('preferredStartTime'),
            preferredEndTime: timeValue('preferredEndTime'),
            bufferBetweenEventsMinutes: Number(root.getElementById('bufferBetweenEventsMinutes')?.value),
            requireConfirmationBeforeCreate: root.getElementById('requireConfirmationBeforeCreate')?.checked,
            autoCreateMeetingLink: root.getElementById('autoCreateMeetingLink')?.checked,
            autoCreateReminder: root.getElementById('autoCreateReminder')?.checked,
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

        const saveBtn = root.getElementById('saveBtn');
        const saveTopBtn = root.getElementById('saveTopBtn');

        if (saveBtn) saveBtn.disabled = true;
        if (saveTopBtn) saveTopBtn.disabled = true;

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
            if (saveBtn) saveBtn.disabled = false;
            if (saveTopBtn) saveTopBtn.disabled = false;
        }
    }

    const form = root.getElementById('prefForm');
    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            savePrefs();
        });
    }

    const saveTopBtn = root.getElementById('saveTopBtn');
    if (saveTopBtn) saveTopBtn.addEventListener('click', savePrefs);

    const cancelBtn = root.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            if (savedPrefs) {
                fillForm(savedPrefs);
                clearFeedback();
            }
        });
    }

    loadPrefs();
})();

