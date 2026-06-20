<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .sr-page {
                max-width: 1280px;
                margin: 0 auto;
                padding: 24px;
            }

            .sr-shell {
                display: grid;
                grid-template-columns: minmax(320px, 420px) minmax(0, 1fr);
                gap: 20px;
                align-items: start;
            }

            .sr-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .sr-card__header {
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .sr-card__body {
                padding: 18px;
            }

            .sr-eyebrow {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
            }

            .sr-title {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: 1.35rem;
                font-weight: 900;
            }

            .sr-muted {
                color: #647878;
                font-size: .9rem;
                font-weight: 600;
            }

            .sr-input {
                width: 100%;
                min-height: 148px;
                resize: vertical;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #ffffff;
                color: #0d2b2b;
                padding: 14px;
                font-size: .98rem;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .sr-input:focus,
            .sr-field input:focus,
            .sr-field textarea:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .sr-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
            }

            .sr-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                border-radius: 8px;
                border: 2px solid #0d2b2b;
                padding: 0 14px;
                font-size: .86rem;
                font-weight: 900;
                text-decoration: none;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .sr-btn:hover {
                transform: translate(-1px, -1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .sr-btn:disabled {
                cursor: not-allowed;
                opacity: .55;
                transform: none;
                box-shadow: none;
            }

            .sr-btn--primary {
                background: #008f91;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .sr-btn--ghost {
                background: #ffffff;
                color: #0d2b2b;
            }

            .sr-btn--danger {
                background: #fff0f0;
                border-color: #c0392b;
                color: #c0392b;
            }

            .sr-btn--success {
                background: #e6fff5;
                border-color: #16a34a;
                color: #15803d;
            }

            .sr-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-height: 680px;
                overflow: auto;
            }

            .sr-request {
                width: 100%;
                border: 1px solid #dbe7e7;
                border-left: 6px solid var(--status-color, #008f91);
                border-radius: 8px;
                background: #ffffff;
                padding: 12px;
                text-align: left;
                cursor: pointer;
                transition: border-color .15s ease, background .15s ease, transform .15s ease;
            }

            .sr-request:hover,
            .sr-request.is-selected {
                border-color: #008f91;
                background: #fafdff;
                transform: translateY(-1px);
            }

            .sr-request__text {
                color: #0d2b2b;
                font-size: .93rem;
                font-weight: 900;
                line-height: 1.4;
            }

            .sr-request__meta {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
                justify-content: space-between;
                margin-top: 10px;
            }

            .sr-status {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: 4px 9px;
                background: var(--status-bg, #e5ffff);
                color: var(--status-text, #006b6d);
                font-size: .72rem;
                font-weight: 900;
            }

            .sr-review-empty {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 24px;
                background: #ffffff;
                color: #647878;
                font-weight: 700;
            }

            .sr-review-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .sr-field {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .sr-field label {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .sr-field input,
            .sr-field textarea {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #ffffff;
                color: #0d2b2b;
                padding: 10px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
            }

            .sr-field textarea {
                min-height: 88px;
                resize: vertical;
            }

            .sr-field--full {
                grid-column: 1 / -1;
            }

            .sr-feedback {
                display: none;
                border-radius: 8px;
                border: 1px solid #b8eeee;
                background: #e5ffff;
                color: #0d2b2b;
                padding: 12px;
                font-size: .9rem;
                font-weight: 800;
            }

            .sr-feedback.is-visible {
                display: block;
            }

            .sr-feedback.is-error {
                border-color: #f3b4b4;
                background: #fff0f0;
                color: #a32222;
            }

            .sr-event-link {
                display: none;
            }

            .sr-event-link.is-visible {
                display: inline-flex;
            }

            .sr-examples {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .sr-example-chip {
                border: 1px solid #cfe0e0;
                border-radius: 999px;
                background: #f0ffff;
                color: #006b6d;
                padding: 6px 12px;
                font-size: .82rem;
                font-weight: 800;
                cursor: pointer;
                transition: background .15s ease, border-color .15s ease, transform .15s ease;
            }

            .sr-example-chip:hover {
                background: #ccfeff;
                border-color: #008f91;
                transform: translateY(-1px);
            }

            @media (max-width: 980px) {
                .sr-shell {
                    grid-template-columns: 1fr;
                }

                .sr-review-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="sr-page">
            <div class="mb-5">
                <p class="sr-eyebrow">Solicitacoes inteligentes</p>
                <h1 class="sr-title">Crie eventos usando linguagem natural</h1>
            </div>

            <div class="sr-shell">
                <div class="space-y-5">
                    <section class="sr-card">
                        <div class="sr-card__header">
                            <p class="sr-eyebrow">Novo pedido</p>
                            <h2 class="sr-title">O que voce quer agendar?</h2>
                        </div>

                        <div class="sr-card__body space-y-4">
                            <div class="sr-examples">
                                <span class="sr-example-chip" onclick="fillExample(this)">Dentista sexta às 10h</span>
                                <span class="sr-example-chip" onclick="fillExample(this)">Call com cliente na quinta, 15h, 45 min</span>
                                <span class="sr-example-chip" onclick="fillExample(this)">Revisão do projeto hoje 18h com Ana e Pedro</span>
                                <span class="sr-example-chip" onclick="fillExample(this)">Academia todo dia às 7h por 1h</span>
                                <span class="sr-example-chip" onclick="fillExample(this)">Almoço com equipe amanhã ao meio-dia</span>
                                <span class="sr-example-chip" onclick="fillExample(this)">Apresentação para o cliente na segunda às 9h, 2 horas</span>
                            </div>

                            <textarea
                                id="rawText"
                                class="sr-input"
                                maxlength="1000"
                                placeholder="Ex: Marque uma reuniao com Joao amanha as 15h por uma hora"
                            ></textarea>

                            <div class="sr-actions">
                                <button id="sendBtn" class="sr-btn sr-btn--primary" type="button">Enviar pedido</button>
                                <span id="charCount" class="sr-muted">0/1000</span>
                            </div>

                            <div id="requestFeedback" class="sr-feedback" role="status"></div>
                        </div>
                    </section>

                    <section class="sr-card">
                        <div class="sr-card__header">
                            <p class="sr-eyebrow">Recentes</p>
                            <h2 class="sr-title">Solicitacoes</h2>
                        </div>

                        <div class="sr-card__body">
                            <div id="requestList" class="sr-list">
                                <div class="sr-review-empty">Carregando solicitacoes...</div>
                            </div>
                        </div>
                    </section>
                </div>

                <section class="sr-card">
                    <div class="sr-card__header">
                        <p class="sr-eyebrow">Revisao</p>
                        <h2 class="sr-title">Dados extraidos</h2>
                    </div>

                    <div class="sr-card__body">
                        <div id="emptyReview" class="sr-review-empty">
                            Selecione uma solicitacao recente para revisar titulo, horario, participantes e status.
                        </div>

                        <form id="reviewPanel" class="hidden space-y-5">
                            <input type="hidden" id="reviewId">

                            <div class="sr-review-grid">
                                <div class="sr-field sr-field--full">
                                    <label>Pedido original</label>
                                    <textarea id="reviewRawText" readonly></textarea>
                                </div>

                                <div class="sr-field">
                                    <label>Status</label>
                                    <input id="reviewStatus" type="text" readonly>
                                </div>

                                <div class="sr-field">
                                    <label>Intencao</label>
                                    <input id="reviewIntent" type="text" readonly>
                                </div>

                                <div class="sr-field sr-field--full">
                                    <label>Titulo</label>
                                    <input id="reviewTitle" type="text">
                                </div>

                                <div class="sr-field sr-field--full">
                                    <label>Descricao</label>
                                    <textarea id="reviewDescription"></textarea>
                                </div>

                                <div class="sr-field">
                                    <label>Inicio</label>
                                    <input id="reviewStartAt" type="datetime-local">
                                </div>

                                <div class="sr-field">
                                    <label>Fim</label>
                                    <input id="reviewEndAt" type="datetime-local">
                                </div>

                                <div class="sr-field sr-field--full">
                                    <label>Participantes</label>
                                    <textarea id="reviewParticipants" placeholder="Um participante por linha. Ex: Maria &lt;maria@email.com&gt;"></textarea>
                                </div>
                            </div>

                            <div id="reviewFeedback" class="sr-feedback" role="status"></div>

                            <div class="sr-actions">
                                <button id="saveBtn" class="sr-btn sr-btn--ghost" type="button">Salvar revisao</button>
                                <button id="confirmBtn" class="sr-btn sr-btn--success" type="button">Confirmar evento</button>
                                <button id="deleteBtn" class="sr-btn sr-btn--danger" type="button">Excluir</button>
                                <a id="suggestionsLink" class="sr-btn sr-btn--ghost sr-event-link" href="#">Ver sugestões de horário</a>
                            <a id="eventLink" class="sr-btn sr-btn--primary sr-event-link" href="{{ route('calendars.index') }}">Ver na agenda</a>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const statuses = [
                'pending',
                'needs_more_info',
                'needs_confirmation',
                'suggesting_times',
                'confirmed',
                'completed',
                'cancelled',
                'failed',
            ];

            const statusInfo = {
                pending: { label: 'Pendente', color: '#ffe14d', bg: '#fff8c7', text: '#645400' },
                needs_more_info: { label: 'Precisa de dados', color: '#ffb76b', bg: '#fff0df', text: '#8a4d00' },
                needs_confirmation: { label: 'Aguardando confirmacao', color: '#008f91', bg: '#e5ffff', text: '#006b6d' },
                suggesting_times: { label: 'Sugerindo horarios', color: '#ffb76b', bg: '#fff0df', text: '#8a4d00' },
                confirmed: { label: 'Confirmada', color: '#16a34a', bg: '#e6fff5', text: '#15803d' },
                completed: { label: 'Evento criado', color: '#16a34a', bg: '#e6fff5', text: '#15803d' },
                cancelled: { label: 'Cancelada', color: '#c0392b', bg: '#fff0f0', text: '#a32222' },
                failed: { label: 'Falhou', color: '#c0392b', bg: '#fff0f0', text: '#a32222' },
            };

            let requests = [];
            let selectedRequest = null;

            async function api(url, options = {}) {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    ...options,
                });

                if (response.status === 204) {
                    return null;
                }

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message || 'Nao foi possivel concluir a operacao.');
                }

                return payload;
            }

            function getStatusInfo(status) {
                return statusInfo[status] || { label: status || 'Sem status', color: '#008f91', bg: '#e5ffff', text: '#006b6d' };
            }

            function showFeedback(targetId, message, type = 'success') {
                const feedback = document.getElementById(targetId);
                feedback.textContent = message;
                feedback.className = `sr-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
            }

            function clearFeedback(targetId) {
                const feedback = document.getElementById(targetId);
                feedback.textContent = '';
                feedback.className = 'sr-feedback';
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function formatDate(value) {
                if (!value) {
                    return '';
                }

                return new Date(value).toLocaleString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }

            function toDatetimeLocal(value) {
                if (!value) {
                    return '';
                }

                const date = new Date(value);
                const pad = number => String(number).padStart(2, '0');

                // Usa métodos UTC para exibir o horário exato armazenado,
                // sem conversão para o fuso horário local do navegador.
                return `${date.getUTCFullYear()}-${pad(date.getUTCMonth() + 1)}-${pad(date.getUTCDate())}T${pad(date.getUTCHours())}:${pad(date.getUTCMinutes())}`;
            }

            function toParticipantText(participants) {
                if (!Array.isArray(participants)) {
                    return '';
                }

                return participants.map(participant => {
                    if (typeof participant === 'string') {
                        return participant;
                    }

                    const name = participant.name || '';
                    const email = participant.email ? ` <${participant.email}>` : '';

                    return `${name}${email}`.trim();
                }).join('\n');
            }

            function parseParticipants(value) {
                return value
                    .split('\n')
                    .map(line => line.trim())
                    .filter(Boolean)
                    .map(line => {
                        const match = line.match(/^(.*?)\s*<([^>]+)>$/);

                        if (match) {
                            return { name: match[1].trim(), email: match[2].trim() };
                        }

                        if (line.includes('@')) {
                            return { name: line.split('@')[0], email: line };
                        }

                        return { name: line, email: null };
                    });
            }

            function agendaUrlFor(request) {
                const date = request?.extractedStartAt
                    ? new Date(request.extractedStartAt).toISOString().slice(0, 10)
                    : new Date().toISOString().slice(0, 10);

                return `{{ route('calendars.index') }}?view=day&date=${date}`;
            }

            async function loadRequests() {
                const list = document.getElementById('requestList');
                list.innerHTML = '<div class="sr-review-empty">Carregando solicitacoes...</div>';

                try {
                    const responses = await Promise.all(statuses.map(status => api(`/api/smart-requests/status/${status}`)));
                    requests = responses
                        .flatMap(response => response?.data || [])
                        .sort((a, b) => new Date(b.createdAt || 0) - new Date(a.createdAt || 0));

                    renderRequests();

                    if (requests.length) {
                        selectRequest(selectedRequest?.id || requests[0].id);
                    } else {
                        selectedRequest = null;
                        renderReview(null);
                    }
                } catch (error) {
                    list.innerHTML = `<div class="sr-review-empty">${escapeHtml(error.message)}</div>`;
                }
            }

            function renderRequests() {
                const list = document.getElementById('requestList');

                if (!requests.length) {
                    list.innerHTML = '<div class="sr-review-empty">Nenhuma solicitacao recente. Envie seu primeiro pedido acima.</div>';
                    return;
                }

                list.innerHTML = requests.map(request => {
                    const info = getStatusInfo(request.status);
                    const selected = selectedRequest?.id === request.id;

                    return `
                        <button
                            class="sr-request ${selected ? 'is-selected' : ''}"
                            style="--status-color:${info.color}; --status-bg:${info.bg}; --status-text:${info.text};"
                            type="button"
                            data-request-id="${request.id}"
                        >
                            <div class="sr-request__text">${escapeHtml(request.rawText)}</div>
                            <div class="sr-request__meta">
                                <span class="sr-status">${info.label}</span>
                                <span class="sr-muted">${formatDate(request.createdAt)}</span>
                            </div>
                        </button>
                    `;
                }).join('');
            }

            function selectRequest(id) {
                selectedRequest = requests.find(request => Number(request.id) === Number(id)) || null;
                renderRequests();
                renderReview(selectedRequest);
            }

            function renderReview(request) {
                clearFeedback('reviewFeedback');

                document.getElementById('emptyReview').classList.toggle('hidden', Boolean(request));
                document.getElementById('reviewPanel').classList.toggle('hidden', !request);

                if (!request) {
                    return;
                }

                const info = getStatusInfo(request.status);
                const canConfirm = request.status === 'needs_confirmation';
                const canEdit = ['pending', 'needs_more_info', 'needs_confirmation', 'failed'].includes(request.status);
                const canViewAgenda = ['confirmed', 'completed'].includes(request.status);

                document.getElementById('reviewId').value = request.id;
                document.getElementById('reviewRawText').value = request.rawText || '';
                document.getElementById('reviewStatus').value = info.label;
                document.getElementById('reviewIntent').value = request.intent || 'create_event';
                document.getElementById('reviewTitle').value = request.extractedTitle || '';
                document.getElementById('reviewDescription').value = request.extractedDescription || '';
                document.getElementById('reviewStartAt').value = toDatetimeLocal(request.extractedStartAt);
                document.getElementById('reviewEndAt').value = toDatetimeLocal(request.extractedEndAt);
                document.getElementById('reviewParticipants').value = toParticipantText(request.extractedParticipants);

                document.getElementById('saveBtn').disabled = !canEdit;
                document.getElementById('confirmBtn').disabled = !canConfirm;

                const canSuggest = request.status === 'suggesting_times';
                const suggestionsLink = document.getElementById('suggestionsLink');
                suggestionsLink.href = `/smart-requests/${request.id}/suggestions`;
                suggestionsLink.classList.toggle('is-visible', canSuggest);

                const eventLink = document.getElementById('eventLink');
                eventLink.href = agendaUrlFor(request);
                eventLink.classList.toggle('is-visible', canViewAgenda);

                if (request.errorMessage) {
                    showFeedback('reviewFeedback', request.errorMessage, 'error');
                }
            }

            async function createRequest() {
                const input = document.getElementById('rawText');
                const rawText = input.value.trim();

                clearFeedback('requestFeedback');

                if (rawText.length < 5) {
                    showFeedback('requestFeedback', 'Digite um pedido com pelo menos 5 caracteres.', 'error');
                    return;
                }

                const button = document.getElementById('sendBtn');
                button.disabled = true;

                try {
                    const response = await api('/api/smart-requests', {
                        method: 'POST',
                        body: JSON.stringify({ rawText }),
                    });

                    input.value = '';
                    document.getElementById('charCount').textContent = '0/1000';
                    showFeedback('requestFeedback', 'Pedido enviado. Revise os dados extraidos ao lado.');

                    await loadRequests();
                    if (response?.data?.id) {
                        selectRequest(response.data.id);
                    }
                } catch (error) {
                    showFeedback('requestFeedback', error.message, 'error');
                } finally {
                    button.disabled = false;
                }
            }

            async function saveReview() {
                if (!selectedRequest) {
                    return;
                }

                clearFeedback('reviewFeedback');

                const toUtcIso = v => v ? v + 'Z' : null;

                const payload = {
                    extractedTitle: document.getElementById('reviewTitle').value || null,
                    extractedDescription: document.getElementById('reviewDescription').value || null,
                    extractedStartAt: toUtcIso(document.getElementById('reviewStartAt').value),
                    extractedEndAt: toUtcIso(document.getElementById('reviewEndAt').value),
                    extractedParticipants: parseParticipants(document.getElementById('reviewParticipants').value),
                };

                document.getElementById('saveBtn').disabled = true;

                try {
                    const response = await api(`/api/smart-requests/${selectedRequest.id}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload),
                    });

                    const updated = response.data;
                    requests = requests.map(request => request.id === updated.id ? updated : request);
                    selectedRequest = updated;
                    renderReview(updated);
                    renderRequests();
                    showFeedback('reviewFeedback', 'Revisao salva.');
                } catch (error) {
                    showFeedback('reviewFeedback', error.message, 'error');
                } finally {
                    document.getElementById('saveBtn').disabled = !selectedRequest || !['pending', 'needs_more_info', 'needs_confirmation', 'failed'].includes(selectedRequest.status);
                }
            }

            async function confirmSelected() {
                if (!selectedRequest) {
                    return;
                }

                clearFeedback('reviewFeedback');
                document.getElementById('confirmBtn').disabled = true;

                try {
                    await api(`/api/smart-requests/${selectedRequest.id}/confirm`, { method: 'POST' });
                    showFeedback('reviewFeedback', 'Evento criado. Use o botao para abrir a agenda.');
                    await loadRequests();
                    selectRequest(selectedRequest.id);
                } catch (error) {
                    showFeedback('reviewFeedback', error.message, 'error');
                } finally {
                    document.getElementById('confirmBtn').disabled = !selectedRequest || selectedRequest.status !== 'needs_confirmation';
                }
            }

            async function deleteSelected() {
                if (!selectedRequest || !confirm('Excluir esta solicitacao?')) {
                    return;
                }

                clearFeedback('reviewFeedback');
                document.getElementById('deleteBtn').disabled = true;

                try {
                    await api(`/api/smart-requests/${selectedRequest.id}`, { method: 'DELETE' });
                    requests = requests.filter(request => request.id !== selectedRequest.id);
                    selectedRequest = requests[0] || null;
                    renderRequests();
                    renderReview(selectedRequest);
                } catch (error) {
                    showFeedback('reviewFeedback', error.message, 'error');
                } finally {
                    document.getElementById('deleteBtn').disabled = false;
                }
            }

            function fillExample(chip) {
                const input = document.getElementById('rawText');
                input.value = chip.textContent.trim();
                document.getElementById('charCount').textContent = `${input.value.length}/1000`;
                input.focus();
            }

            document.getElementById('rawText').addEventListener('input', event => {
                document.getElementById('charCount').textContent = `${event.target.value.length}/1000`;
            });

            document.getElementById('sendBtn').addEventListener('click', createRequest);
            document.getElementById('saveBtn').addEventListener('click', saveReview);
            document.getElementById('confirmBtn').addEventListener('click', confirmSelected);
            document.getElementById('deleteBtn').addEventListener('click', deleteSelected);
            document.getElementById('requestList').addEventListener('click', event => {
                const button = event.target.closest('[data-request-id]');

                if (button) {
                    selectRequest(button.dataset.requestId);
                }
            });

            loadRequests();
        </script>
    </div>
</x-app-layout>
