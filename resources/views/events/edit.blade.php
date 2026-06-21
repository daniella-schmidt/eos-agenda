<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/events-edit.css', 'resources/js/events-edit.js'])

        <div class="events-page" data-event-id="{{ (int) $eventId }}" data-redirect="{{ route('events.index') }}">
            <div class="events-top">
                <div>
                    <h1 class="events-heading">Editar evento</h1>
                    <p class="events-muted" id="eventSubtitle">Carregando...</p>
                </div>
                <a href="{{ route('events.index') }}" class="events-btn events-btn--ghost">← Voltar</a>
            </div>

            <section class="events-card">
                <div class="events-card__header">
                    <p class="events-eyebrow">Evento</p>
                    <h2 class="events-title">Informações do evento</h2>
                </div>

                <div class="events-card__body">
                    <div id="pageLoader" class="events-empty">Carregando dados do evento...</div>

                    <form id="eventForm" class="space-y-5 is-hidden" data-redirect="{{ route('events.index') }}">
                        <div class="events-panel">
                            <h3 class="events-section-title">Informações principais</h3>
                            <div class="events-grid">
                                <div class="events-field events-field--full">
                                    <label for="title">Título</label>
                                    <input id="title" maxlength="200" required type="text">
                                </div>
                                <div class="events-field events-field--full">
                                    <label for="description">Descrição</label>
                                    <textarea id="description" maxlength="2000"></textarea>
                                </div>
                                <div class="events-field">
                                    <label for="location">Local</label>
                                    <input id="location" maxlength="500" type="text">
                                </div>
                                <div class="events-field">
                                    <label for="meetingURL">URL da reunião</label>
                                    <input id="meetingURL" maxlength="1000" type="url">
                                </div>
                                <div class="events-field">
                                    <label for="calendarId">Calendário</label>
                                    <select id="calendarId"></select>
                                </div>
                            </div>
                        </div>

                        <div class="events-panel">
                            <h3 class="events-section-title">Data e horário</h3>
                            <div class="events-grid">
                                <div class="events-field">
                                    <label for="startAt">Início</label>
                                    <input id="startAt" required type="datetime-local">
                                </div>
                                <div class="events-field">
                                    <label for="endAt">Fim</label>
                                    <input id="endAt" required type="datetime-local">
                                </div>
                                <div class="events-field">
                                    <label for="timezone">Fuso horário</label>
                                    <select id="timezone">
                                        <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                                <div class="events-field">
                                    <label>Opções</label>
                                    <div class="events-option-list">
                                        <label class="events-option-chip">
                                            <input id="isAllDay" type="checkbox">
                                            <span>Dia todo</span>
                                        </label>
                                        <label class="events-option-chip">
                                            <input id="isRecurring" type="checkbox">
                                            <span>Recorrente</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="events-panel">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="events-eyebrow">Participantes</p>
                                    <p class="events-muted mt-1">Adicione manualmente ou selecione um contato.</p>
                                </div>
                                <div class="events-actions">
                                    <button id="showManualParticipantBtn" class="events-btn events-btn--ghost" type="button">+ Participante</button>
                                    <button id="showContactParticipantBtn" class="events-btn events-btn--soft" type="button">👤 Contato</button>
                                </div>
                            </div>

                            <div id="contactParticipantFields" class="mt-4 is-hidden">
                                <div class="grid gap-3 md:grid-cols-[1fr_140px_150px_auto]">
                                    <select id="contactSelect" class="events-search"></select>
                                    <select id="contactRole" class="events-search">
                                        <option value="attendee">Participante</option>
                                        <option value="organizer">Organizador</option>
                                    </select>
                                    <select id="contactResponseStatus" class="events-search">
                                        <option value="pending">Pendente</option>
                                        <option value="accepted">Aceito</option>
                                        <option value="declined">Recusado</option>
                                        <option value="tentative">Talvez</option>
                                    </select>
                                    <button id="addContactBtn" class="events-btn events-btn--ghost" type="button">Adicionar</button>
                                </div>
                            </div>

                            <div id="manualParticipantFields" class="mt-4 is-hidden">
                                <div class="grid gap-3 md:grid-cols-[1fr_1fr_140px_150px_auto]">
                                    <input id="manualName" class="events-search" type="text" placeholder="Nome">
                                    <input id="manualEmail" class="events-search" type="email" placeholder="email@exemplo.com">
                                    <select id="manualRole" class="events-search">
                                        <option value="attendee">Participante</option>
                                        <option value="organizer">Organizador</option>
                                    </select>
                                    <select id="manualResponseStatus" class="events-search">
                                        <option value="pending">Pendente</option>
                                        <option value="accepted">Aceito</option>
                                        <option value="declined">Recusado</option>
                                        <option value="tentative">Talvez</option>
                                    </select>
                                    <button id="addManualBtn" class="events-btn events-btn--ghost" type="button">Adicionar</button>
                                </div>
                            </div>

                            <div id="participantsList" class="events-chip-list"></div>
                        </div>

                        <div class="events-panel">
                            <p class="events-eyebrow">Lembretes</p>
                            <div class="mt-3 events-actions">
                                <button class="events-btn events-btn--soft" type="button" data-reminder="10">10 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="15">15 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="30">30 min</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="60">1 hora</button>
                                <button class="events-btn events-btn--soft" type="button" data-reminder="1440">1 dia</button>
                            </div>
                            <div id="remindersList" class="events-chip-list"></div>
                        </div>

                        <div class="events-panel">
                            <h3 class="events-section-title">Configurações</h3>
                            <div class="events-grid">
                                <div class="events-field">
                                    <label for="status">Status</label>
                                    <select id="status">
                                        <option value="draft">Rascunho</option>
                                        <option value="confirmed">Confirmado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>
                                <div class="events-field">
                                    <label for="priority">Prioridade</label>
                                    <select id="priority">
                                        <option value="low">Baixa</option>
                                        <option value="medium">Média</option>
                                        <option value="high">Alta</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="formFeedback" class="events-feedback" role="status"></div>

                        <div class="events-actions">
                            <button id="deleteEventBtn" class="events-btn events-btn--danger" type="button">Excluir evento</button>
                            <span style="flex:1;"></span>
                            <a href="{{ route('events.index') }}" class="events-btn events-btn--ghost">Cancelar</a>
                            <button id="saveEventBtn" class="events-btn events-btn--primary" type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

