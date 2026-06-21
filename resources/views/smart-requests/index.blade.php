<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/smart-requests.css', 'resources/js/smart-requests.js'])

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
    </div>
</x-app-layout>
