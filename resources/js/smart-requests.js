
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