<x-app-layout>
    <x-slot name="header">
        <div class="eos-page-header">
            <div class="eos-page-header__left">
                <h2 class="eos-page-title">Solicitações Inteligentes</h2>
            </div>
        </div>
    </x-slot>

    <style>
        /* ── EOS DESIGN TOKENS ─────────────────────────────── */
        :root {
            --teal:    #008f91;
            --teal-m:  #00c1c4;
            --teal-l:  #ccfeff;
            --teal-xl: #e5ffff;
            --pink:    #ff6bb3;
            --orange:  #ffb76b;
            --sun:     #ffe14d;
            --dark:    #0d2b2b;
            --white:   #ffffff;
            --radius:  20px;
            --border:  3px solid var(--dark);
            --shadow:  5px 5px 0 var(--dark);
        }

        /* ── PAGE LAYOUT ──────────────────────────────────── */
        .eos-page-header {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .eos-page-header__left { display: flex; flex-direction: column; gap: 4px; }
        .eos-page-tag {
            display: inline-flex; align-items: center; gap: 6px;
            font-weight: 900; font-size: .72rem; text-transform: uppercase; letter-spacing: 2px;
            color: var(--teal); background: var(--teal-l);
            padding: 4px 14px; border-radius: 50px; border: 2px solid var(--teal); width: fit-content;
        }
        .eos-page-title {
    
            font-size: 1.8rem; color: var(--dark); margin: 0; font-weight: 700;
        }

        .sr-page { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }

        /* ── BUTTONS ──────────────────────────────────────── */
        .eos-btn {
            font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .9rem;
            padding: 10px 22px; border-radius: 50px; cursor: pointer;
            border: var(--border); transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }
        .eos-btn--primary { background: var(--teal); color: var(--white); box-shadow: var(--shadow); }
        .eos-btn--primary:hover { transform: translate(-2px,-2px); box-shadow: 7px 7px 0 var(--dark); }
        .eos-btn--ghost   { background: var(--white); color: var(--dark); box-shadow: 3px 3px 0 var(--dark); }
        .eos-btn--ghost:hover { background: var(--teal-l); transform: translate(-2px,-2px); }
        .eos-btn--pink    { background: var(--pink); color: var(--white); box-shadow: var(--shadow); }
        .eos-btn--pink:hover { transform: translate(-2px,-2px); box-shadow: 7px 7px 0 var(--dark); }
        .eos-btn--danger  { background: #fff0f0; color: #c0392b; border-color: #c0392b; box-shadow: 3px 3px 0 #c0392b; }
        .eos-btn--danger:hover { background: #ffe0e0; transform: translate(-1px,-1px); }
        .eos-btn--success { background: #e6fff5; color: #16a34a; border-color: #16a34a; box-shadow: 3px 3px 0 #16a34a; }
        .eos-btn--success:hover { background: #d0ffe8; transform: translate(-1px,-1px); }
        .eos-btn--sm { padding: 6px 14px; font-size: .78rem; }
        .eos-btn:disabled { opacity: .55; cursor: not-allowed; transform: none !important; }

        /* ── TOAST ────────────────────────────────────────── */
        #eos-toast {
            position: fixed; top: 24px; right: 24px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px; pointer-events: none;
        }
        .toast-item {
            font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .88rem;
            padding: 12px 20px; border-radius: 14px; border: var(--border);
            box-shadow: var(--shadow); color: var(--dark);
            animation: toastIn .25s ease both; pointer-events: all;
        }
        .toast-item--success { background: var(--teal-l); }
        .toast-item--error   { background: #fff0f0; }
        .toast-item--info    { background: var(--sun); }
        @keyframes toastIn { from{opacity:0;transform:translateY(-10px) scale(.94)}to{opacity:1;transform:none} }

        /* ── COMPOSE BOX ──────────────────────────────────── */
        .sr-compose {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow); padding: 28px; margin-bottom: 32px;
        }
        .sr-compose__lead {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--teal); margin-bottom: 4px;
        }
        .sr-compose__hint {
            font-weight: 400; font-size: .85rem; opacity: .6; margin-bottom: 18px; line-height: 1.5;
        }
        .sr-compose__row { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
        .sr-compose__textarea-wrap { flex: 1; min-width: 0; }
        .sr-compose__textarea {
            width: 100%; font-family: 'Nunito', sans-serif; font-weight: 400;
            font-size: 1rem; padding: 12px 16px;
            border: var(--border); border-radius: 14px;
            background: var(--teal-xl); color: var(--dark);
            resize: none; outline: none; transition: box-shadow .15s;
            min-height: 56px; line-height: 1.5;
        }
        .sr-compose__textarea:focus {
            box-shadow: 0 0 0 3px var(--teal-l), var(--shadow);
        }
        .sr-compose__meta { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 12px; }
        .sr-compose__select-wrap { display: flex; align-items: center; gap: 8px; }
        .sr-compose__select-wrap label { font-weight: 900; font-size: .9rem; color: var(--teal); text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
        .sr-cal-select {
            font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .88rem;
            padding: 8px 14px; border: var(--border); border-radius: 50px;
            background: var(--teal-xl); color: var(--dark);
            cursor: pointer; outline: none; transition: box-shadow .15s;
        }
        .sr-cal-select:focus { box-shadow: 0 0 0 3px var(--teal-l); }
        .sr-char-count { font-weight: 800; font-size: .78rem; opacity: .45; margin-left: auto; }

        /* ── EXAMPLES ─────────────────────────────────────── */
        .sr-examples { margin-top: 14px; display: flex; flex-wrap: wrap; gap: 8px; }
        .sr-example-chip {
            font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .78rem;
            padding: 5px 14px; border-radius: 50px;
            border: 2px solid var(--teal); background: var(--teal-l); color: var(--teal);
            cursor: pointer;
        }

        /* ── STATUS TABS ──────────────────────────────────── */
        .sr-tabs { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 22px; }
        .sr-tab {
            font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .82rem;
            padding: 7px 18px; border-radius: 50px; border: var(--border);
            cursor: pointer; background: var(--white); color: var(--dark);
        }
        .sr-tab.active { background: var(--teal); color: var(--white); box-shadow: 3px 3px 0 var(--dark); }

        /* ── REQUEST CARD ─────────────────────────────────── */
        .sr-list { display: flex; flex-direction: column; gap: 16px; }

        .sr-card {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow); overflow: hidden;
        }

        .sr-card__bar { height: 6px; }
        .sr-card__bar--pending    { background: var(--sun); }
        .sr-card__bar--processing { background: var(--orange); }
        .sr-card__bar--success    { background: #4ade80; }
        .sr-card__bar--failed     { background: var(--pink); }
        .sr-card__bar--confirmed  { background: var(--teal); }

        .sr-card__body { padding: 20px 24px; }
        .sr-card__top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
        .sr-card__raw {
            font-weight: 800; font-size: .95rem; color: var(--dark);
            line-height: 1.5; flex: 1; min-width: 0;
        }
        .sr-status-badge {
            font-weight: 900; font-size: .68rem; text-transform: uppercase; letter-spacing: .5px;
            padding: 4px 12px; border-radius: 50px; border: 2px solid var(--dark);
            white-space: nowrap; flex-shrink: 0;
        }
        .sr-status--pending    { background: var(--sun);   color: var(--dark); }
        .sr-status--processing { background: var(--orange); color: var(--dark); }
        .sr-status--extracted  { background: var(--teal-l); color: var(--teal); }
        .sr-status--confirmed  { background: #d0ffe8;      color: #16a34a; border-color: #16a34a; }
        .sr-status--failed     { background: #ffe0e0;      color: #c0392b; border-color: #c0392b; }

        /* Extracted data grid */
        .sr-extracted {
            margin-top: 14px; padding: 14px 16px;
            background: var(--teal-xl); border-radius: 12px;
            border: 2px dashed var(--teal);
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;
        }
        .sr-field { display: flex; flex-direction: column; gap: 2px; }
        .sr-field__label {
            font-weight: 900; font-size: .65rem; text-transform: uppercase;
            letter-spacing: .5px; color: var(--teal); opacity: .8;
        }
        .sr-field__value { font-weight: 800; font-size: .88rem; color: var(--dark); }
        .sr-field__input {
            font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .88rem;
            padding: 6px 10px; border: 2px solid var(--dark); border-radius: 8px;
            background: var(--white); color: var(--dark); outline: none;
            transition: box-shadow .13s; width: 100%;
        }
        .sr-field__input:focus { box-shadow: 0 0 0 3px var(--teal-l); }

        .sr-participants { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
        .sr-participant-chip {
            font-weight: 800; font-size: .74rem; padding: 3px 10px;
            background: var(--white); border: 2px solid var(--dark); border-radius: 50px;
        }

        /* Error msg */
        .sr-error-msg {
            margin-top: 10px; padding: 10px 14px;
            background: #fff0f0; border-radius: 10px; border: 2px solid #c0392b;
            font-weight: 800; font-size: .82rem; color: #c0392b;
        }

        /* card footer */
        .sr-card__footer {
            padding: 12px 24px 16px;
            border-top: 2px dashed var(--teal-l);
            display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
        }
        .sr-card__time { font-weight: 800; font-size: .75rem; opacity: .45; margin-left: auto; }

        /* ── EMPTY STATE ──────────────────────────────────── */
        .eos-empty {
            text-align: center; padding: 80px 24px;
            display: flex; flex-direction: column; align-items: center; gap: 16px;
        }
        .eos-empty__icon {
            width: 80px; height: 80px; border-radius: 20px;
            background: var(--teal-l); border: var(--border); box-shadow: var(--shadow);
            display: flex; align-items: center; justify-content: center; font-size: 2.4rem;
        }
        .eos-empty__title{  font-size: 1.4rem; color: var(--dark); }
        .eos-empty__sub   { font-weight: 700; opacity: .6; max-width: 340px; line-height: 1.5; }

        /* ── LOADING DOTS ─────────────────────────────────── */
        .sr-loading { text-align: center; padding: 60px; }
        .sr-loading__dots { display: inline-flex; gap: 8px; }
        .sr-loading__dot {
            width: 12px; height: 12px; border-radius: 50%; background: var(--teal);
            animation: bounce 1s ease-in-out infinite;
        }
        .sr-loading__dot:nth-child(2){animation-delay:.15s}
        .sr-loading__dot:nth-child(3){animation-delay:.3s}
        @keyframes bounce{0%,80%,100%{transform:scale(0)}40%{transform:scale(1)}}

        /* ── MODAL ────────────────────────────────────────── */
        .eos-modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(13,43,43,.55); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            z-index: 500; padding: 20px;
        }
        .eos-modal-backdrop.open { display: flex; }
        .eos-modal {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: 10px 10px 0 var(--dark);
            width: 100%; max-width: 520px;
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1) both;
            max-height: 90vh; overflow-y: auto;
        }
        @keyframes modalIn { from{opacity:0;transform:scale(.9) translateY(16px)}to{opacity:1;transform:none} }
        .eos-modal__header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px 0; position: sticky; top: 0; background: var(--white);
            border-bottom: 2px dashed var(--teal-l); padding-bottom: 14px; margin-bottom: 0;
        }
        .eos-modal__title  {font-size: 1.3rem; color: var(--dark); }
        .eos-modal__close {
            background: var(--teal-l); border: var(--border); border-radius: 10px;
            width: 36px; height: 36px; cursor: pointer; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center; transition: .15s;
        }
        .eos-modal__close:hover { background: #ffe0e0; border-color: #c0392b; }
        .eos-modal__body { padding: 20px 24px 24px; }

        .eos-field { margin-bottom: 16px; }
        .eos-field label {
            display: block; font-weight: 900; font-size: .82rem;
            text-transform: uppercase; letter-spacing: .5px;
            color: var(--teal); margin-bottom: 6px;
        }
        .eos-field input[type="text"],
        .eos-field input[type="datetime-local"],
        .eos-field textarea,
        .eos-field select {
            width: 100%; font-family: 'Nunito', sans-serif; font-weight: 700;
            font-size: .95rem; padding: 10px 14px;
            border: var(--border); border-radius: 12px;
            background: var(--teal-xl); color: var(--dark);
            transition: box-shadow .15s; outline: none;
        }
        .eos-field input:focus,
        .eos-field textarea:focus,
        .eos-field select:focus { box-shadow: 0 0 0 3px var(--teal-l), var(--shadow); }
        .eos-field textarea { resize: vertical; min-height: 72px; }

        .eos-modal__actions {
            display: flex; justify-content: flex-end; gap: 10px; padding-top: 8px; flex-wrap: wrap;
        }

        /* ── RESPONSIVE ───────────────────────────────────── */
        @media (max-width: 640px) {
            .sr-page { padding: 20px 16px; }
            .sr-compose__row { flex-direction: column; }
            .sr-card__top { flex-direction: column; }
            .sr-card__time { margin-left: 0; }
        }
    </style>

    <div class="sr-page">
        <div id="eos-toast"></div>

        <!-- ── COMPOSE BOX ───────────────────────────────── -->
        <div class="sr-compose">
            <div class="sr-compose__lead"> O que você precisa agendar?</div>
            <div class="sr-compose__row">
                <div class="sr-compose__textarea-wrap">
                    <textarea
                        id="rawText"
                        class="sr-compose__textarea"
                        maxlength="1000"
                        placeholder="Ex: Reunião com o time de design amanhã às 14h por 1 hora…"
                        rows="2"
                    ></textarea>
                </div>
                <button id="sendBtn" class="eos-btn eos-btn--primary" style="height:56px;border-radius:14px;padding:0 24px;">
                    <span id="sendBtnIcon"></span>Enviar
                </button>
            </div>

            <div class="sr-compose__meta">
                <div class="sr-compose__select-wrap">
                    <label for="calendarSelect">Calendário:</label>
                    <select id="calendarSelect" class="sr-cal-select">
                        <option value="">Carregando…</option>
                    </select>
                </div>
                <div class="sr-char-count"><span id="charCount">0</span>/1000</div>
            </div>

            <div class="sr-examples">
                <span class="sr-example-chip" onclick="fillExample(this)">Dentista sexta às 10h</span>
                <span class="sr-example-chip" onclick="fillExample(this)">Call com cliente na quinta, 15h, 45 min</span>
                <span class="sr-example-chip" onclick="fillExample(this)">Revisão do projeto hoje 18h com Ana e Pedro</span>
                <span class="sr-example-chip" onclick="fillExample(this)">Academia todo dia às 7h por 1h</span>
            </div>
        </div>

        <!-- ── STATUS TABS ────────────────────────────────── -->
        <div class="sr-tabs">
            <button class="sr-tab active" data-status="all" onclick="switchTab(this)">Todas</button>
            <button class="sr-tab" data-status="pending" onclick="switchTab(this)">Pendentes</button>
            <button class="sr-tab" data-status="needs_more_info" onclick="switchTab(this)">Precisam de informações</button>
            <button class="sr-tab" data-status="needs_confirmation" onclick="switchTab(this)">Aguardando confirmação</button>
            <button class="sr-tab" data-status="suggesting_times" onclick="switchTab(this)">Sugerindo horários</button>
            <button class="sr-tab" data-status="confirmed" onclick="switchTab(this)">Confirmadas</button>
            <button class="sr-tab" data-status="completed" onclick="switchTab(this)">Concluídas</button>
            <button class="sr-tab" data-status="cancelled" onclick="switchTab(this)">Canceladas</button>
            <button class="sr-tab" data-status="failed" onclick="switchTab(this)">Com erro</button>
        </div>

        <!-- ── REQUEST LIST ───────────────────────────────── -->
        <div id="srList">
            <div class="sr-loading">
                <div class="sr-loading__dots">
                    <div class="sr-loading__dot"></div>
                    <div class="sr-loading__dot"></div>
                    <div class="sr-loading__dot"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── EDIT MODAL ────────────────────────────────────── -->
    <div id="editModal" class="eos-modal-backdrop">
        <div class="eos-modal">
            <div class="eos-modal__header">
                <h3 class="eos-modal__title">Revisar Solicitação</h3>
                <button class="eos-modal__close" onclick="closeEditModal()">✕</button>
            </div>
            <div class="eos-modal__body">
                <input type="hidden" id="editId">
                <div class="eos-field">
                    <label>Texto original</label>
                    <textarea id="editRaw" readonly style="opacity:.6; min-height: 54px;"></textarea>
                </div>
                <div class="eos-field">
                    <label>Título do evento</label>
                    <input type="text" id="editTitle" placeholder="Título extraído pela IA">
                </div>
                <div class="eos-field">
                    <label>Descrição</label>
                    <textarea id="editDescription" placeholder="Descrição opcional"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="eos-field">
                        <label>Início</label>
                        <input type="datetime-local" id="editStartAt">
                    </div>
                    <div class="eos-field">
                        <label>Fim</label>
                        <input type="datetime-local" id="editEndAt">
                    </div>
                </div>
                <div class="eos-field">
                    <label>Calendário destino</label>
                    <select id="editCalendarSelect" class="eos-field">
                        <option value="">Selecione um calendário</option>
                    </select>
                </div>
                <div class="eos-modal__actions">
                    <button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="closeEditModal()">Cancelar</button>
                    <button class="eos-btn eos-btn--primary eos-btn--sm" onclick="saveEdit()">Salvar</button>
                    <button class="eos-btn eos-btn--success eos-btn--sm" id="confirmBtn" onclick="confirmRequest()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let currentTab = 'all';
        let allRequests = [];
        let calendars   = [];
        const smartRequestStatuses = [
            'pending',
            'needs_more_info',
            'needs_confirmation',
            'suggesting_times',
            'confirmed',
            'completed',
            'cancelled',
            'failed',
        ];

        /* ── TOAST ─────────────────────────────────────────── */
        function toast(msg, type = 'success') {
            const el = document.createElement('div');
            el.className = `toast-item toast-item--${type}`;
            el.textContent = msg;
            document.getElementById('eos-toast').appendChild(el);
            setTimeout(() => el.remove(), 3500);
        }

        /* ── API ──────────────────────────────────────────── */
        async function api(url, opts = {}) {
            const res = await fetch(url, {
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                ...opts,
            });
            if (!res.ok) throw new Error(await res.text());
            if (res.status === 204) return null;
            return res.json();
        }

        /* ── CALENDARS ────────────────────────────────────── */
        async function loadCalendars() {
            try {
                const { data } = await api('/api/calendars');
                calendars = data;
                populateCalendarSelects();
            } catch { }
        }

        function populateCalendarSelects() {
            const opts = calendars.map(c =>
                `<option value="${c.id}" ${c.isDefault ? 'selected' : ''}>${escHtml(c.name)}</option>`
            ).join('');

            document.getElementById('calendarSelect').innerHTML =
                '<option value="">Sem calendário específico</option>' + opts;

            document.getElementById('editCalendarSelect').innerHTML =
                '<option value="">Selecione um calendário</option>' + opts;
        }

        /* ── LOAD REQUESTS ────────────────────────────────── */
        async function loadRequests() {
            document.getElementById('srList').innerHTML = `
                <div class="sr-loading">
                    <div class="sr-loading__dots">
                        <div class="sr-loading__dot"></div>
                        <div class="sr-loading__dot"></div>
                        <div class="sr-loading__dot"></div>
                    </div>
                </div>`;
            try {
                if (currentTab === 'all') {
                    const responses = await Promise.all(
                        smartRequestStatuses.map(status =>
                            api(`/api/smart-requests/status/${status}`)
                        )
                    );

                    allRequests = responses.flatMap(response => response.data ?? []);
                } else {
                    const response = await api(`/api/smart-requests/status/${currentTab}`);
                    allRequests = response.data ?? [];
                }

                renderList();
            } catch (e) {
                document.getElementById('srList').innerHTML = `
                    <div class="eos-empty">
                        <div class="eos-empty__icon">⚠️</div>
                        <div class="eos-empty__title">Erro ao carregar</div>
                        <p class="eos-empty__sub">Não foi possível carregar as solicitações. Tente novamente.</p>
                        <button class="eos-btn eos-btn--primary" onclick="loadRequests()">Tentar novamente</button>
                    </div>`;
            }
        }

        /* ── RENDER LIST ──────────────────────────────────── */
        function renderList() {
            const container = document.getElementById('srList');

            if (!allRequests.length) {
                const emptyMsg = currentTab === 'all'
                    ? 'Nenhuma solicitação ainda. Use o campo acima para criar a primeira!'
                    : `Nenhuma solicitação com status "${currentTab}".`;
                container.innerHTML = `
                    <div class="eos-empty">
                        <div class="eos-empty__title">Tudo vazio por aqui</div>
                        <p class="eos-empty__sub">${emptyMsg}</p>
                    </div>`;
                return;
            }

            // Sort: newest first
            const sorted = [...allRequests].sort((a,b) => new Date(b.createdAt) - new Date(a.createdAt));
            container.innerHTML = `<div class="sr-list">${sorted.map(renderCard).join('')}</div>`;
        }

        function renderCard(req) {
            const statusMap = {
                pending:            { label: 'Pendente', cls: 'sr-status--pending', bar: 'sr-card__bar--pending' },
                needs_more_info:    { label: 'Precisa de informações', cls: 'sr-status--processing', bar: 'sr-card__bar--processing' },
                needs_confirmation: { label: 'Aguardando confirmação', cls: 'sr-status--extracted', bar: 'sr-card__bar--success' },
                suggesting_times:   { label: 'Sugerindo horários', cls: 'sr-status--processing', bar: 'sr-card__bar--processing' },
                confirmed:          { label: 'Confirmada', cls: 'sr-status--confirmed', bar: 'sr-card__bar--confirmed' },
                completed:          { label: 'Concluída', cls: 'sr-status--confirmed', bar: 'sr-card__bar--confirmed' },
                cancelled:          { label: 'Cancelada', cls: 'sr-status--failed', bar: 'sr-card__bar--failed' },
                failed:             { label: 'Com erro', cls: 'sr-status--failed', bar: 'sr-card__bar--failed' },
            };
            const st = statusMap[req.status] || { label: req.status, cls: '', bar: '' };
            const createdAt = req.createdAt ? new Date(req.createdAt).toLocaleString('pt-BR') : '';

            let extractedHtml = '';
            if (req.extractedTitle || req.extractedStartAt) {
                extractedHtml = `<div class="sr-extracted">
                    ${req.extractedTitle ? `<div class="sr-field"><div class="sr-field__label">Título</div><div class="sr-field__value">${escHtml(req.extractedTitle)}</div></div>` : ''}
                    ${req.extractedStartAt ? `<div class="sr-field"><div class="sr-field__label">Início</div><div class="sr-field__value">${formatDt(req.extractedStartAt)}</div></div>` : ''}
                    ${req.extractedEndAt ? `<div class="sr-field"><div class="sr-field__label">Fim</div><div class="sr-field__value">${formatDt(req.extractedEndAt)}</div></div>` : ''}
                    ${req.extractedDescription ? `<div class="sr-field" style="grid-column:1/-1"><div class="sr-field__label">Descrição</div><div class="sr-field__value">${escHtml(req.extractedDescription)}</div></div>` : ''}
                    ${req.extractedParticipants?.length ? `<div class="sr-field" style="grid-column:1/-1"><div class="sr-field__label">Participantes</div><div class="sr-participants">${req.extractedParticipants.map(p=>`<span class="sr-participant-chip">👤 ${escHtml(p)}</span>`).join('')}</div></div>` : ''}
                </div>`;
            }

            const errorHtml = req.errorMessage
                ? `<div class="sr-error-msg">⚠️ ${escHtml(req.errorMessage)}</div>`
                : '';

            const canEdit    = ['needs_more_info', 'needs_confirmation', 'pending', 'failed'].includes(req.status);
            const canConfirm = req.status === 'needs_confirmation';
            const confirmed  = ['confirmed', 'completed'].includes(req.status);

            return `
            <div class="sr-card" id="sr-card-${req.id}">
                <div class="sr-card__bar ${st.bar}"></div>
                <div class="sr-card__body">
                    <div class="sr-card__top">
                        <div class="sr-card__raw">"${escHtml(req.rawText)}"</div>
                        <span class="sr-status-badge ${st.cls}">${st.label}</span>
                    </div>
                    ${extractedHtml}
                    ${errorHtml}
                </div>
                <div class="sr-card__footer">
                    ${canEdit    ? `<button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="openEditModal(${req.id})">Revisar</button>` : ''}
                    ${canConfirm ? `<button class="eos-btn eos-btn--success eos-btn--sm" onclick="quickConfirm(${req.id})">Confirmar</button>` : ''}
                    ${confirmed  ? `<span style="font-weight:800;font-size:.8rem;color:#16a34a">Evento criado com sucesso ✓</span>` : ''}
                    <button class="eos-btn eos-btn--danger eos-btn--sm" onclick="deleteRequest(${req.id})">🗑️</button>
                    <div class="sr-card__time">${createdAt}</div>
                </div>
            </div>`;
        }

        function formatDt(iso) {
            if (!iso) return '—';
            return new Date(iso).toLocaleString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
        }
        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ── TABS ─────────────────────────────────────────── */
        async function switchTab(btn) {
            document.querySelectorAll('.sr-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            currentTab = btn.dataset.status;
            await loadRequests();
        }

        /* ── COMPOSE ──────────────────────────────────────── */
        const rawTextEl = document.getElementById('rawText');
        rawTextEl.addEventListener('input', () => {
            document.getElementById('charCount').textContent = rawTextEl.value.length;
            // Auto-resize
            rawTextEl.style.height = 'auto';
            rawTextEl.style.height = rawTextEl.scrollHeight + 'px';
        });

        function fillExample(chip) {
            rawTextEl.value = chip.textContent;
            rawTextEl.dispatchEvent(new Event('input'));
            rawTextEl.focus();
        }

        document.getElementById('sendBtn').addEventListener('click', async () => {
            const text = rawTextEl.value.trim();
            if (!text) { toast('Escreva uma solicitação antes de enviar', 'error'); return; }

            const btn  = document.getElementById('sendBtn');
            const icon = document.getElementById('sendBtnIcon');
            btn.disabled = true;
            try {
                await api('/api/smart-requests', {
                    method: 'POST',
                    body: JSON.stringify({ rawText: text }),
                });
                rawTextEl.value = '';
                rawTextEl.style.height = 'auto';
                document.getElementById('charCount').textContent = '0';
                toast('Solicitação enviada!', 'info');
                setTimeout(loadRequests, 800);
            } catch (e) {
                toast('Erro ao enviar solicitação', 'error');
            } finally {
                btn.disabled = false;
            }
        });

        /* ── QUICK CONFIRM ────────────────────────────────── */
        async function quickConfirm(id) {
            try {
                await api(`/api/smart-requests/${id}/confirm`, { method: 'POST' });
                toast('Evento confirmado e criado no calendário!');
                loadRequests();
            } catch { toast('Erro ao confirmar', 'error'); }
        }

        /* ── DELETE ───────────────────────────────────────── */
        async function deleteRequest(id) {
            if (!confirm('Excluir esta solicitação?')) return;
            try {
                await api(`/api/smart-requests/${id}`, { method: 'DELETE' });
                toast('Solicitação excluída');
                allRequests = allRequests.filter(r => r.id !== id);
                renderList();
            } catch { toast('Erro ao excluir', 'error'); }
        }

        /* ── EDIT MODAL ───────────────────────────────────── */
        function openEditModal(id) {
            const req = allRequests.find(r => r.id === id);
            if (!req) return;

            document.getElementById('editId').value = id;
            document.getElementById('editRaw').value = req.rawText;
            document.getElementById('editTitle').value = req.extractedTitle || '';
            document.getElementById('editDescription').value = req.extractedDescription || '';
            document.getElementById('editStartAt').value = toDatetimeLocal(req.extractedStartAt);
            document.getElementById('editEndAt').value = toDatetimeLocal(req.extractedEndAt);

            document.getElementById('confirmBtn').style.display = req.status === 'needs_confirmation' ? '' : 'none';

            document.getElementById('editModal').classList.add('open');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('open');
        }

        function toDatetimeLocal(iso) {
            if (!iso) return '';
            const d = new Date(iso);
            const pad = n => String(n).padStart(2,'0');
            return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        }

        async function saveEdit() {
            const id = document.getElementById('editId').value;
            const payload = {
                extractedTitle:       document.getElementById('editTitle').value || null,
                extractedDescription: document.getElementById('editDescription').value || null,
                extractedStartAt:     document.getElementById('editStartAt').value || null,
                extractedEndAt:       document.getElementById('editEndAt').value || null,
            };
            try {
                const { data } = await api(`/api/smart-requests/${id}`, {
                    method: 'PATCH',
                    body: JSON.stringify(payload),
                });
                // Update local state
                const idx = allRequests.findIndex(r => r.id == id);
                if (idx !== -1) allRequests[idx] = { ...allRequests[idx], ...data };
                closeEditModal();
                toast('Solicitação atualizada ✓');
                renderList();
            } catch { toast('Erro ao salvar revisão', 'error'); }
        }

        async function confirmRequest() {
            const id = document.getElementById('editId').value;
            await saveEdit();
            await quickConfirm(id);
        }

        document.getElementById('editModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeEditModal();
        });

        /* ── INIT ──────────────────────────────────────────── */
        loadCalendars();
        loadRequests();
    </script>
</x-app-layout>
