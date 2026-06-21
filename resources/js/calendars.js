(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const editBaseUrl = document.querySelector('[data-cal-edit-base-url]')?.dataset?.calEditBaseUrl
        || window.__CAL_EDIT_BASE_URL__
        || '';

    const root = document;

    const dayCards = root.querySelectorAll('.js-calendar-day');
    const detailContainer = root.getElementById('selectedDayDetails');

    function selectDay(dayKey) {
        dayCards.forEach((card) => {
            card.classList.toggle('is-selected', card.dataset.day === dayKey);
        });

        const template = root.querySelector(`[data-day-template="${dayKey}"]`);

        if (template && detailContainer) {
            detailContainer.innerHTML = template.innerHTML;
        }
    }

    dayCards.forEach((card) => {
        card.addEventListener('click', () => {
            selectDay(card.dataset.day);
        });
    });

    // — Event detail modal —
    const calOverlay = root.getElementById('calEventOverlay');
    const calModalTitle = root.getElementById('calModalTitle');
    const calModalBody = root.getElementById('calModalBody');

    function calOpenModal() {
        calOverlay?.classList.remove('is-hidden');
        document.body.style.overflow = 'hidden';
    }

    function calCloseModal() {
        calOverlay?.classList.add('is-hidden');
        document.body.style.overflow = '';
    }

    root.querySelectorAll('[data-cal-close]').forEach((el) => el.addEventListener('click', calCloseModal));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') calCloseModal();
    });

    async function calApi(url) {
        const res = await fetch(url, {
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });
        if (!res.ok) throw new Error('Não foi possível carregar o evento.');
        return res.json();
    }

    const calEscape = (v) => String(v ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/"/g, '"');

    const calDate = (v) => v
        ? new Date(v).toLocaleString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        })
        : '-';

    const calStatus = (s) => ({ draft: 'Rascunho', confirmed: 'Confirmado', cancelled: 'Cancelado' }[s] || s || '-');
    const calPriority = (p) => ({ low: 'Baixa', medium: 'Média', high: 'Alta' }[p] || p || '-');

    async function calShowEvent(id) {
        if (!calModalBody || !calModalTitle) return;

        calModalTitle.textContent = 'Carregando...';
        calModalBody.innerHTML = '<div class="cal-empty">Carregando detalhes...</div>';
        calOpenModal();

        try {
            const payload = await calApi(`/api/events/${id}`);
            const ev = payload.data;

            calModalTitle.textContent = ev.title || 'Evento';

            const participants = ev.participants || [];
            const reminders = ev.reminders || [];
            const statusClass = ev.status === 'cancelled' ? 'is-cancelled' : '';
            const priorClass = ev.priority === 'high' ? 'is-high' : '';

            calModalBody.innerHTML = `
                <article class="cal-detail-card" style="--event-color:${calEscape(ev.calendar?.color || '#008f91')}">
                    <div class="cal-detail-card__top">
                        <div class="cal-detail-card__time">
                            ${calDate(ev.startAt)} até ${calDate(ev.endAt)}
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                            <span class="status-pill ${statusClass}">${calEscape(calStatus(ev.status))}</span>
                            <span class="status-pill ${priorClass}">Prioridade ${calEscape(calPriority(ev.priority))}</span>
                            ${ev.createByAI ? '<span class="status-pill">Criado por IA</span>' : ''}
                        </div>
                    </div>

                    <h3 class="cal-detail-card__heading">${calEscape(ev.title || '-')}</h3>

                    <p class="cal-detail-card__desc">
                        ${ev.description ? calEscape(ev.description) : 'Sem descrição cadastrada.'}
                    </p>

                    <div class="cal-detail-card__info">
                        <p><strong>Calendário:</strong> ${calEscape(ev.calendar?.name || '-')}</p>
                        <p><strong>Local:</strong> ${calEscape(ev.location || '-')}</p>
                        <p><strong>Reunião:</strong> ${
                            ev.meetingURL
                                ? `<a href="${calEscape(ev.meetingURL)}" target="_blank" style="color:#008f91">${calEscape(ev.meetingURL)}</a>`
                                : '-'
                        }</p>
                        <p><strong>Fuso horário:</strong> ${calEscape(ev.timezone || '-')}</p>
                        ${ev.isAllDay ? '<p><strong>Tipo:</strong> Evento de dia todo</p>' : ''}
                        ${ev.isRecurring ? '<p><strong>Recorrência:</strong> Evento recorrente</p>' : ''}
                    </div>

                    <div class="cal-detail-card__section">
                        <p class="cal-detail-card__section-label">Participantes</p>
                        <div class="cal-chip-list">
                            ${participants.length
                                ? participants.map(p =>
                                    `<span class="cal-chip">${calEscape(p.name || p.email || 'Participante')}${p.email ? ` · ${calEscape(p.email)}` : ''} · ${calEscape(p.role || 'attendee')} · ${calEscape(p.responseStatus || 'pending')}</span>`
                                ).join('')
                                : '<div class="cal-empty">Nenhum participante.</div>'
                            }
                        </div>
                    </div>

                    <div class="cal-detail-card__section">
                        <p class="cal-detail-card__section-label">Lembretes</p>
                        <div class="cal-chip-list">
                            ${reminders.length
                                ? reminders.map(r =>
                                    `<span class="cal-chip">${calEscape(r.type || 'notification')} · ${Number(r.minutesBefore)} min antes</span>`
                                ).join('')
                                : '<div class="cal-empty">Nenhum lembrete.</div>'
                            }
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px;">
                        <a href="${editBaseUrl}/${calEscape(ev.id)}/edit" class="cal-btn cal-btn--ghost">Editar evento</a>
                    </div>
                </article>
            `;
        } catch (err) {
            calModalBody.innerHTML = `<div class="cal-empty">${calEscape(err.message)}</div>`;
        }
    }

    // Delegação de clique nos eventos do painel lateral (conteúdo inserido dinamicamente)
    const selectedDayDetails = document.getElementById('selectedDayDetails');
    if (selectedDayDetails) {
        selectedDayDetails.addEventListener('click', function (e) {
            const article = e.target.closest('[data-event-id]');
            if (article) calShowEvent(article.dataset.eventId);
        });
    }

    // Init: seleciona hoje (ou primeiro card)
    const today = new Date().toISOString().slice(0, 10);
    const todayCard = document.querySelector(`[data-day="${today}"]`);
    const firstCard = document.querySelector('.js-calendar-day');

    if (todayCard) {
        selectDay(today);
    } else if (firstCard) {
        selectDay(firstCard.dataset.day);
    }
})();

