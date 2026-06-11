<x-app-layout>
    <x-slot name="header">
        <div class="eos-page-header">
            <div class="eos-page-header__left">
                <h2 class="eos-page-title">Meus Calendários</h2>
            </div>
            <button id="openCreateModal" class="eos-btn eos-btn--primary">
                <span>＋</span> Novo Calendário
            </button>
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

        /* ── PAGE HEADER ──────────────────────────────────── */
        .eos-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .eos-page-header__left { display: flex; flex-direction: column; gap: 4px; }
        .eos-page-tag {
            display: inline-flex; align-items: center; gap: 6px;
            font-weight: 900; font-size: .72rem; text-transform: uppercase; letter-spacing: 2px;
            color: var(--teal); background: var(--teal-l);
            padding: 4px 14px; border-radius: 50px; border: 2px solid var(--teal);
            width: fit-content;
        }
        .eos-page-title {
            
            font-size: 1.6rem; color: var(--dark); margin: 0; font-weight: 700;
        }

        /* ── BUTTONS ──────────────────────────────────────── */
        .eos-btn {
            font-family: 'Nunito', sans-serif; font-weight: 900; font-size: .9rem;
            padding: 10px 22px; border-radius: 50px; cursor: pointer;
            border: var(--border); transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
        }
        .eos-btn--primary {
            background: var(--teal); color: var(--white);
            box-shadow: var(--shadow);
        }
        .eos-btn--primary:hover { transform: translate(-2px,-2px); box-shadow: 7px 7px 0 var(--dark); }
        .eos-btn--ghost {
            background: var(--white); color: var(--dark);
            box-shadow: 3px 3px 0 var(--dark);
        }
        .eos-btn--ghost:hover { background: var(--teal-l); transform: translate(-2px,-2px); }
        .eos-btn--danger {
            background: #fff0f0; color: #c0392b;
            border-color: #c0392b; box-shadow: 3px 3px 0 #c0392b;
        }
        .eos-btn--danger:hover { background: #ffe0e0; transform: translate(-1px,-1px); }
        .eos-btn--sm { padding: 6px 14px; font-size: .78rem; }

        /* ── PAGE BODY ────────────────────────────────────── */
        .eos-page-body {
            max-width: 1280px; margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── TOAST ────────────────────────────────────────── */
        #eos-toast {
            position: fixed; top: 24px; right: 24px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        .toast-item {
            font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .88rem;
            padding: 12px 20px; border-radius: 14px; border: var(--border);
            box-shadow: var(--shadow); color: var(--dark);
            animation: toastIn .25s ease both;
            pointer-events: all;
        }
        .toast-item--success { background: var(--teal-l); }
        .toast-item--error   { background: #fff0f0; }
        @keyframes toastIn { from { opacity:0; transform: translateY(-10px) scale(.94); } to { opacity:1; transform: none; } }

        /* ── EMPTY STATE ──────────────────────────────────── */
        .eos-empty {
            text-align: center; padding: 80px 24px;
            display: flex; flex-direction: column; align-items: center; gap: 16px;
        }
        .eos-empty__icon {
            width: 80px; height: 80px; border-radius: 20px;
            background: var(--teal-l); border: var(--border);
            box-shadow: var(--shadow);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.4rem;
        }
        .eos-empty__title {  font-size: 1.4rem; color: var(--dark); }
        .eos-empty__sub   { font-weight: 700; opacity: .6; max-width: 320px; line-height: 1.5; }

        /* ── LOADING SKELETON ─────────────────────────────── */
        .eos-skeleton-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .eos-skeleton-card {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            padding: 24px; box-shadow: var(--shadow);
            animation: pulse 1.4s ease-in-out infinite;
        }
        .skeleton-line {
            background: var(--teal-l); border-radius: 8px; height: 14px; margin-bottom: 10px;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

        /* ── CALENDAR GRID ────────────────────────────────── */
        .eos-cal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 22px;
        }

        /* ── CALENDAR CARD ────────────────────────────────── */
        .eos-cal-card {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow); cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .eos-cal-card:focus-within { outline: none; }
        .eos-cal-card__stripe {
            height: 8px; width: 100%;
            background: var(--teal);
        }
        .eos-cal-card__body { padding: 20px 22px; flex: 1; }
        .eos-cal-card__row  { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .eos-cal-card__name {
             font-size: 1.15rem;
             font-weight: 600;
            color: var(--dark); margin-bottom: 4px;
        }
        .eos-cal-card__desc {
            font-weight: 700; font-size: .83rem; opacity: .6;
            line-height: 1.5; margin-bottom: 14px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .eos-cal-card__badges { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 14px; }
        .eos-badge {
            font-weight: 900; font-size: .68rem; text-transform: uppercase; letter-spacing: .5px;
            padding: 4px 10px; border-radius: 50px; border: 2px solid var(--dark);
        }
        .eos-badge--default  { background: var(--sun);   color: var(--dark); }
        .eos-badge--inactive { background: #ffe0e0;     color: #c0392b; border-color: #c0392b; }
        .eos-badge--count    { background: var(--teal-l); color: var(--teal); }

        .eos-cal-card__footer {
            padding: 12px 22px 16px;
            display: flex; gap: 6px; flex-wrap: wrap;
            border-top: 2px dashed var(--teal-l);
        }

        /* ── MODAL ────────────────────────────────────────── */
        .eos-modal-backdrop {
            position: fixed; inset: 0;
            background: rgba(13,43,43,.55);
            backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            z-index: 500; padding: 20px;
        }
        .eos-modal-backdrop.open { display: flex; }
        .eos-modal {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: 10px 10px 0 var(--dark);
            width: 100%; max-width: 480px;
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes modalIn { from{opacity:0;transform:scale(.9) translateY(16px)}to{opacity:1;transform:none} }
        .eos-modal__header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px 0;
        }
        .eos-modal__title {  font-size: 1.3rem; color: var(--dark); }
        .eos-modal__close {
            background: var(--teal-l); border: var(--border); border-radius: 10px;
            width: 36px; height: 36px; cursor: pointer; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            transition: .15s;
        }
        .eos-modal__close:hover { background: #ffe0e0; border-color: #c0392b; }
        .eos-modal__body { padding: 20px 24px 24px; }

        /* ── FORM FIELDS ──────────────────────────────────── */
        .eos-field { margin-bottom: 16px; }
        .eos-field label {
            display: block; font-weight: 900; font-size: .82rem;
            text-transform: uppercase; letter-spacing: .5px;
            color: var(--teal); margin-bottom: 6px;
        }
        .eos-field input[type="text"],
        .eos-field textarea {
            width: 100%; font-family: 'Nunito', sans-serif; font-weight: 700;
            font-size: .95rem; padding: 10px 14px;
            border: var(--border); border-radius: 12px;
            background: var(--teal-xl); color: var(--dark);
            transition: box-shadow .15s;
            outline: none;
        }
        .eos-field input:focus,
        .eos-field textarea:focus {
            box-shadow: 0 0 0 3px var(--teal-l), var(--shadow);
        }
        .eos-field textarea { resize: vertical; min-height: 72px; }

        .eos-color-row {
            display: flex; align-items: center; gap: 14px;
        }
        .eos-color-swatch {
            width: 44px; height: 44px; border-radius: 12px;
            border: var(--border); cursor: pointer; overflow: hidden;
            box-shadow: 3px 3px 0 var(--dark); flex-shrink: 0;
        }
        .eos-color-swatch input[type="color"] {
            width: 200%; height: 200%; margin: -50%; border: none;
            cursor: pointer; background: none;
        }
        .eos-color-presets { display: flex; gap: 8px; flex-wrap: wrap; }
        .eos-color-preset {
            width: 28px; height: 28px; border-radius: 8px;
            border: 2px solid transparent; cursor: pointer;
            box-shadow: 2px 2px 0 var(--dark);
        }
        .eos-color-preset.active {
            border-color: var(--dark); transform: scale(1.18);
        }

        .eos-checkbox-row {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px; border-radius: 12px;
            border: 2px dashed var(--teal); background: var(--teal-xl);
        }
        .eos-checkbox-row input[type="checkbox"] {
            width: 20px; height: 20px; accent-color: var(--teal);
            cursor: pointer;
        }
        .eos-checkbox-row span { font-weight: 800; font-size: .88rem; }

        .eos-modal__actions {
            display: flex; justify-content: flex-end; gap: 10px;
            padding-top: 8px;
        }

        /* ── CONFIRM DIALOG ───────────────────────────────── */
        .eos-confirm {
            background: var(--white); border: var(--border); border-radius: var(--radius);
            box-shadow: 10px 10px 0 var(--dark);
            width: 100%; max-width: 360px; padding: 28px 28px 24px;
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1) both;
            text-align: center;
        }
        .eos-confirm__icon { font-size: 2.8rem; margin-bottom: 10px; }
        .eos-confirm__title {  font-size: 1.2rem; margin-bottom: 6px; }
        .eos-confirm__sub { font-weight: 700; font-size: .88rem; opacity: .65; margin-bottom: 22px; }
        .eos-confirm__actions { display: flex; gap: 10px; justify-content: center; }

        /* ── RESPONSIVE ───────────────────────────────────── */
        @media (max-width: 640px) {
            .eos-page-body { padding: 20px 16px; }
            .eos-cal-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="eos-page-body">
        <!-- Toast container -->
        <div id="eos-toast"></div>

        <!-- Calendar list -->
        <div id="calendarsList">
            <!-- Loading skeleton -->
            <div class="eos-skeleton-grid" id="skeletonGrid">
                <div class="eos-skeleton-card">
                    <div class="skeleton-line" style="width:60%"></div>
                    <div class="skeleton-line" style="width:90%"></div>
                    <div class="skeleton-line" style="width:40%"></div>
                </div>
                <div class="eos-skeleton-card">
                    <div class="skeleton-line" style="width:50%"></div>
                    <div class="skeleton-line" style="width:80%"></div>
                    <div class="skeleton-line" style="width:35%"></div>
                </div>
                <div class="eos-skeleton-card">
                    <div class="skeleton-line" style="width:70%"></div>
                    <div class="skeleton-line" style="width:85%"></div>
                    <div class="skeleton-line" style="width:50%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── CALENDAR MODAL ──────────────────────────────────── -->
    <div id="calendarModal" class="eos-modal-backdrop">
        <div class="eos-modal">
            <div class="eos-modal__header">
                <h3 id="modalTitle" class="eos-modal__title">Novo Calendário</h3>
                <button class="eos-modal__close" id="closeModal" aria-label="Fechar">✕</button>
            </div>
            <div class="eos-modal__body">
                <form id="calendarForm">
                    <input type="hidden" id="calendarId">

                    <div class="eos-field">
                        <label for="name">Nome do calendário</label>
                        <input type="text" id="name" placeholder="Ex: Trabalho, Pessoal, Estudos…" required>
                    </div>

                    <div class="eos-field">
                        <label for="description">Descrição</label>
                        <textarea id="description" placeholder="Uma descrição breve (opcional)…"></textarea>
                    </div>

                    <div class="eos-field">
                        <label>Cor</label>
                        <div class="eos-color-row">
                            <div class="eos-color-swatch">
                                <input type="color" id="color" value="#008f91">
                            </div>
                            <div class="eos-color-presets">
                                <div class="eos-color-preset" style="background:#008f91" data-color="#008f91" title="Teal"></div>
                                <div class="eos-color-preset" style="background:#ff6bb3" data-color="#ff6bb3" title="Rosa"></div>
                                <div class="eos-color-preset" style="background:#ffb76b" data-color="#ffb76b" title="Laranja"></div>
                                <div class="eos-color-preset" style="background:#ffe14d" data-color="#ffe14d" title="Amarelo"></div>
                                <div class="eos-color-preset" style="background:#7c3aed" data-color="#7c3aed" title="Roxo"></div>
                                <div class="eos-color-preset" style="background:#16a34a" data-color="#16a34a" title="Verde"></div>
                                <div class="eos-color-preset" style="background:#dc2626" data-color="#dc2626" title="Vermelho"></div>
                                <div class="eos-color-preset" style="background:#0d2b2b" data-color="#0d2b2b" title="Dark"></div>
                            </div>
                        </div>
                    </div>

                    <div class="eos-field">
                        <label class="eos-checkbox-row" for="isDefault">
                            <input type="checkbox" id="isDefault">
                            <span>Definir como calendário padrão</span>
                        </label>
                    </div>

                    <div class="eos-modal__actions">
                        <button type="button" id="closeModalBtn" class="eos-btn eos-btn--ghost eos-btn--sm">Cancelar</button>
                        <button type="submit" id="submitBtn" class="eos-btn eos-btn--primary eos-btn--sm">Salvar </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── CONFIRM DELETE MODAL ──────────────────────────── -->
    <div id="confirmModal" class="eos-modal-backdrop">
        <div class="eos-confirm">
            <div class="eos-confirm__icon">🗑️</div>
            <div class="eos-confirm__title">Excluir calendário?</div>
            <div class="eos-confirm__sub">Esta ação não pode ser desfeita. Os eventos vinculados podem ser afetados.</div>
            <div class="eos-confirm__actions">
                <button class="eos-btn eos-btn--ghost eos-btn--sm" id="confirmCancel">Cancelar</button>
                <button class="eos-btn eos-btn--danger eos-btn--sm" id="confirmDelete">Sim, excluir</button>
            </div>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let pendingDeleteId = null;

        /* ── TOAST ─────────────────────────────────────────── */
        function toast(msg, type = 'success') {
            const el = document.createElement('div');
            el.className = `toast-item toast-item--${type}`;
            el.textContent = msg;
            document.getElementById('eos-toast').appendChild(el);
            setTimeout(() => el.remove(), 3200);
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

        /* ── RENDER CALENDARS ─────────────────────────────── */
        async function loadCalendars() {
            try {
                const { data } = await api('/api/calendars');
                const container = document.getElementById('calendarsList');

                if (!data.length) {
                    container.innerHTML = `
                        <div class="eos-empty">
                            <div class="eos-empty__icon"></div>
                            <div class="eos-empty__title">Nenhum calendário ainda</div>
                            <p class="eos-empty__sub">Crie seu primeiro calendário para começar a organizar seus eventos.</p>
                            <button class="eos-btn eos-btn--primary" onclick="openCreateModal()">＋ Criar</button>
                        </div>`;
                    return;
                }

                container.innerHTML = `<div class="eos-cal-grid">${data.map(renderCard).join('')}</div>`;
            } catch (e) {
                toast('Erro ao carregar calendários', 'error');
            }
        }

        function renderCard(cal) {
            const stripe = cal.color || 'var(--teal)';
            const countLabel = cal.eventsCount !== undefined ? `${cal.eventsCount} evento${cal.eventsCount !== 1 ? 's' : ''}` : '';
            return `
            <div class="eos-cal-card" onclick="window.location.href='/calendars/${cal.id}'">
                <div class="eos-cal-card__stripe" style="background:${stripe}"></div>
                <div class="eos-cal-card__body">
                    <div class="eos-cal-card__name">${escHtml(cal.name)}</div>
                    <div class="eos-cal-card__desc">${cal.description ? escHtml(cal.description) : 'Sem descrição'}</div>
                    <div class="eos-cal-card__badges">
                        ${cal.isDefault ? '<span class="eos-badge eos-badge--default">⭐ Padrão</span>' : ''}
                        ${!cal.isActive ? '<span class="eos-badge eos-badge--inactive">Inativo</span>' : ''}
                        ${countLabel ? `<span class="eos-badge eos-badge--count">📅 ${countLabel}</span>` : ''}
                    </div>
                </div>
                <div class="eos-cal-card__footer" onclick="event.stopPropagation()">
                    <button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="openEditModal(${cal.id})">Editar</button>
                    ${!cal.isDefault ? `<button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="makeDefault(${cal.id})">Tornar padrão</button>` : ''}
                    <button class="eos-btn eos-btn--danger eos-btn--sm" onclick="confirmDeleteModal(${cal.id})">Excluir</button>
                </div>
            </div>`;
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        /* ── ACTIONS ──────────────────────────────────────── */
        async function makeDefault(id) {
            try {
                await api(`/api/calendars/${id}/make-default`, { method: 'POST' });
                toast('Calendário definido como padrão');
                loadCalendars();
            } catch { toast('Erro ao definir padrão', 'error'); }
        }

        function confirmDeleteModal(id) {
            pendingDeleteId = id;
            document.getElementById('confirmModal').classList.add('open');
        }

        async function deleteCalendar(id) {
            try {
                await api(`/api/calendars/${id}`, { method: 'DELETE' });
                toast('Calendário excluído');
                loadCalendars();
            } catch { toast('Erro ao excluir', 'error'); }
        }

        /* ── MODAL ────────────────────────────────────────── */
        function openModal() {
            document.getElementById('calendarModal').classList.add('open');
        }
        function closeModal() {
            document.getElementById('calendarModal').classList.remove('open');
        }

        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Novo Calendário';
            document.getElementById('calendarForm').reset();
            document.getElementById('calendarId').value = '';
            document.getElementById('color').value = '#008f91';
            syncColorPresets('#008f91');
            document.getElementById('submitBtn').textContent = 'Criar calendário';
            openModal();
        }

        async function openEditModal(id) {
            try {
                const { data } = await api(`/api/calendars/${id}`);
                document.getElementById('modalTitle').textContent = 'Editar Calendário';
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description || '';
                document.getElementById('color').value = data.color || '#008f91';
                document.getElementById('isDefault').checked = data.isDefault;
                document.getElementById('calendarId').value = id;
                document.getElementById('submitBtn').textContent = 'Salvar alterações';
                syncColorPresets(data.color || '#008f91');
                openModal();
            } catch { toast('Erro ao carregar calendário', 'error'); }
        }

        /* ── COLOR PRESETS ────────────────────────────────── */
        function syncColorPresets(hex) {
            document.querySelectorAll('.eos-color-preset').forEach(el => {
                el.classList.toggle('active', el.dataset.color === hex);
            });
        }

        document.querySelectorAll('.eos-color-preset').forEach(el => {
            el.addEventListener('click', () => {
                document.getElementById('color').value = el.dataset.color;
                syncColorPresets(el.dataset.color);
            });
        });

        document.getElementById('color').addEventListener('input', e => {
            syncColorPresets(e.target.value);
        });

        /* ── FORM SUBMIT ──────────────────────────────────── */
        document.getElementById('calendarForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Salvando…';

            const payload = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value || null,
                color: document.getElementById('color').value || null,
                isDefault: document.getElementById('isDefault').checked,
            };
            const id = document.getElementById('calendarId').value;
            const url = id ? `/api/calendars/${id}` : '/api/calendars';
            const method = id ? 'PATCH' : 'POST';

            try {
                await api(url, { method, body: JSON.stringify(payload) });
                closeModal();
                toast(id ? 'Calendário atualizado ✓' : 'Calendário criado ✓');
                loadCalendars();
            } catch {
                toast('Erro ao salvar calendário', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = id ? 'Salvar alterações' : 'Criar calendário';
            }
        });

        /* ── EVENT BINDINGS ───────────────────────────────── */
        document.getElementById('openCreateModal').addEventListener('click', openCreateModal);
        document.getElementById('closeModal').addEventListener('click', closeModal);
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);

        document.getElementById('calendarModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeModal();
        });

        document.getElementById('confirmCancel').addEventListener('click', () => {
            document.getElementById('confirmModal').classList.remove('open');
            pendingDeleteId = null;
        });

        document.getElementById('confirmDelete').addEventListener('click', async () => {
            document.getElementById('confirmModal').classList.remove('open');
            if (pendingDeleteId) await deleteCalendar(pendingDeleteId);
            pendingDeleteId = null;
        });

        document.getElementById('confirmModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) {
                document.getElementById('confirmModal').classList.remove('open');
                pendingDeleteId = null;
            }
        });

        // Init
        loadCalendars();
    </script>
</x-app-layout>