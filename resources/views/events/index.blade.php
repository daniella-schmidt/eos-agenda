<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        @vite(['resources/css/events.css', 'resources/js/events.js'])

        <div id="events-page-root" data-edit-base-url="{{ url('/events') }}"></div>

        <div class="events-page">
            <div class="events-top">
                <div>
                    <h1 class="events-heading">Meus Eventos</h1>
                    <p class="events-muted">Gerencie compromissos, participantes e lembretes.</p>
                </div>
                <a href="{{ route('events.create') }}" class="events-btn events-btn--primary">
                    + Novo evento
                </a>
            </div>

            <div id="listFeedback" class="events-feedback" role="status"></div>

            <div class="mb-5">
                <input id="eventSearch" class="events-search" type="search"
                    placeholder="Buscar evento, participante, local ou status...">
            </div>

            <section class="events-card events-list-card">
                <div class="events-card__header">
                    <p class="events-eyebrow">Eventos</p>
                    <h2 class="events-title">Todos os eventos</h2>
                </div>
                <div class="events-card__body">
                    <div id="eventsList" class="events-list">
                        <div class="events-empty">Carregando eventos...</div>
                    </div>
                </div>
            </section>
        </div>

        <div id="eventDetailsOverlay" class="event-detail-overlay is-hidden">
            <div class="event-detail-backdrop" data-close-details></div>

            <section class="event-detail-modal">
                <div class="event-detail-modal__header">
                    <div>
                        <p class="events-eyebrow">Detalhes do evento</p>
                        <h2 id="eventDetailsTitle" class="events-title">Evento selecionado</h2>
                    </div>
                    <button class="event-detail-close" type="button" data-close-details>×</button>
                </div>
                <div id="eventDetailsContent" class="event-detail-modal__body">
                    <div class="events-empty">Carregando detalhes...</div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

