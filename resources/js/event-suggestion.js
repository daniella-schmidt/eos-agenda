(() => {
    const cfg = window.__EVENT_SUGGESTION__;
    if (!cfg) {
        // Backward-compatibilidade: quando não há script inline na view
        // (window.__EVENT_SUGGESTION__ removido), tentamos montar os valores
        // via DOM.
        const smartRequestIdEl = document.querySelector('[data-smart-request-id]');
        if (!smartRequestIdEl) return;

        window.__EVENT_SUGGESTION__ = {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            smartRequestId: Number(smartRequestIdEl.dataset.smartRequestId),
            calendarBaseUrl: smartRequestIdEl.dataset.calendarBaseUrl || '/calendars',
        };
    }

    const cfg2 = window.__EVENT_SUGGESTION__;
    if (!cfg2) return;

    const csrfToken = cfg2.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const smartRequestId = cfg2.smartRequestId;
    const calendarBaseUrl = cfg2.calendarBaseUrl;


    const els = {
        generateBtn: document.getElementById('generateBtn'),
        generateFeedback: document.getElementById('generateFeedback'),
        confirmEventBtn: document.getElementById('confirmEventBtn'),
        suggestionFeedback: document.getElementById('suggestionFeedback'),
        confirmArea: document.getElementById('confirmArea'),
        calendarLink: document.getElementById('calendarLink'),
        suggestionsList: document.getElementById('suggestionsList'),
        daysAhead: document.getElementById('daysAhead'),
        limitInput: document.getElementById('limitInput'),
    };

    let suggestions = [];

    const escHtml = (v) => String(v ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/"/g, '"');

    const api = async (url, options = {}) => {
        const res = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            ...options,
        });

        if (res.status === 204) return null;
        const payload = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(payload.message || 'Não foi possível concluir a operação.');
        return payload;
    };

    const formatDate = (value) => {
        if (!value) return '-';
        return new Date(value).toLocaleString('pt-BR', {
            weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
        });
    };

    const formatTime = (value) => {
        if (!value) return '-';
        return new Date(value).toLocaleString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    };

    const scorePercent = (score) => {
        const n = parseFloat(score) || 0;
        return n <= 1 ? Math.round(n * 100) : Math.round(n);
    };

    const scoreColor = (pct) => {
        if (pct >= 80) return '#16a34a';
        if (pct >= 55) return '#008f91';
        return '#ffb76b';
    };

    const showFeedback = (el, message, type = 'success') => {
        if (!el) return;
        el.textContent = message;
        el.className = `es-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
    };

    const clearFeedback = (el) => {
        if (!el) return;
        el.textContent = '';
        el.className = 'es-feedback';
    };

    const renderSuggestions = () => {
        if (!els.suggestionsList) return;

        const hasSelected = suggestions.some((s) => s.selected);
        if (els.confirmArea) {
            els.confirmArea.classList.toggle('is-visible', hasSelected);
        }

        if (!suggestions.length) {
            els.suggestionsList.innerHTML = `
                <div class="es-empty">
                    <p class="es-empty__title">Nenhuma sugestão gerada ainda.</p>
                    <p>Informe os parâmetros ao lado e clique em <strong>Gerar sugestões</strong>.</p>
                </div>`;
            return;
        }

        els.suggestionsList.innerHTML = `
            <div class="es-suggestions">
                ${suggestions.map((s, index) => {
                    const pct = scorePercent(s.score);
                    const color = scoreColor(pct);
                    const isFirst = index === 0;

                    const dateStr = formatDate(s.suggestedStartAt);
                    const timeStr = `${formatTime(s.suggestedStartAt)} - ${formatTime(s.suggestedEndAt)}`;

                    const badges = [];
                    if (s.selected) {
                        badges.push('<span class="es-badge es-badge--selected">✓ Selecionada</span>');
                    } else if (isFirst && !hasSelected) {
                        badges.push('<span class="es-badge es-badge--top">★ Mais recomendada</span>');
                    }

                    const selectBtn = s.selected
                        ? `<span class="es-btn es-btn--ghost" style="cursor:default;opacity:.6">Já selecionada</span>`
                        : `<button class="es-btn es-btn--primary" type="button" style="width:auto;" data-select-id="${escHtml(s.id)}">
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
                                    <div class="es-score-bar__fill" style="width:${pct}%; --score-color:${color}"></div>
                                </div>
                            </div>

                            ${s.reason ? `<p class="es-suggestion__reason">${escHtml(s.reason)}</p>` : ''}

                            <div class="es-suggestion__actions">${selectBtn}</div>
                        </div>`;
                }).join('')}
            </div>`;
    };

    const loadSuggestions = async () => {
        if (!els.suggestionsList) return;
        els.suggestionsList.innerHTML = `<div class="es-empty"><p class="es-empty__title">Buscando sugestões...</p></div>`;

        try {
            const payload = await api(`/api/smart-requests/${smartRequestId}/suggestions`);
            suggestions = payload.data || [];
            renderSuggestions();
        } catch (err) {
            els.suggestionsList.innerHTML = `
                <div class="es-empty">
                    <p class="es-empty__title">${escHtml(err.message)}</p>
                </div>`;
        }
    };

    // Generate
    if (els.generateBtn) {
        els.generateBtn.addEventListener('click', async () => {
            clearFeedback(els.generateFeedback);

            const daysAhead = parseInt(els.daysAhead?.value || '7', 10) || 7;
            const limit = parseInt(els.limitInput?.value || '3', 10) || 3;

            const btn = els.generateBtn;
            btn.disabled = true;
            btn.textContent = 'Buscando horários...';

            try {
                const payload = await api(`/api/smart-requests/${smartRequestId}/suggestions/generate`, {
                    method: 'POST',
                    body: JSON.stringify({ daysAhead, limit }),
                });

                suggestions = payload.data || [];
                renderSuggestions();
                showFeedback(els.generateFeedback, `${suggestions.length} sugestão(ões) gerada(s) com sucesso.`);
            } catch (err) {
                showFeedback(els.generateFeedback, err.message, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Gerar sugestões';
            }
        });
    }

    // Select
    if (els.suggestionsList) {
        els.suggestionsList.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-select-id]');
            if (!btn) return;

            const id = btn.dataset.selectId;
            clearFeedback(els.suggestionFeedback);

            btn.disabled = true;
            btn.textContent = 'Selecionando...';

            try {
                const payload = await api(`/api/event-suggestions/${id}/select`, { method: 'POST' });
                const updated = payload.data;

                suggestions = suggestions.map((s) => String(s.id) === String(updated.id)
                    ? { ...s, selected: true }
                    : { ...s, selected: false }
                );

                renderSuggestions();
                showFeedback(els.suggestionFeedback, 'Horário selecionado. Agora você pode confirmar o evento.');
            } catch (err) {
                showFeedback(els.suggestionFeedback, err.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Selecionar este horário';
            }
        });
    }

    // Confirm
    if (els.confirmEventBtn) {
        els.confirmEventBtn.addEventListener('click', async () => {
            const btn = els.confirmEventBtn;
            clearFeedback(els.suggestionFeedback);

            btn.disabled = true;
            btn.textContent = 'Criando evento...';

            try {
                await api(`/api/smart-requests/${smartRequestId}/confirm`, { method: 'POST' });
                showFeedback(els.suggestionFeedback, 'Evento criado com sucesso!');

                const selected = suggestions.find((s) => s.selected);
                const date = selected?.suggestedStartAt
                    ? new Date(selected.suggestedStartAt).toISOString().slice(0, 10)
                    : new Date().toISOString().slice(0, 10);

                if (els.calendarLink) {
                    els.calendarLink.href = `${calendarBaseUrl}?view=day&date=${date}`;
                    els.calendarLink.style.display = 'inline-flex';
                }

                btn.textContent = 'Evento criado';
            } catch (err) {
                showFeedback(els.suggestionFeedback, err.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Confirmar e criar evento';
            }
        });
    }

    // Init
    loadSuggestions();
})();

