<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/user-preferences.css', 'resources/js/user-preferences.js'])

        <div id="user-preferences-page-root">
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
        </div>
    </div>
</x-app-layout>

