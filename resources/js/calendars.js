const csrf = document.querySelector('meta[name="csrf-token"]').content;
let pendingDeleteId = null;

function toast(msg, type = 'success') {
    const container = document.getElementById('eos-toast');
    const el = document.createElement('div');
    el.className = `toast-item toast-item--${type}`;
    el.textContent = msg;
    container.appendChild(el);
    setTimeout(() => el.remove(), 3200);
}

async function api(url, opts = {}) {
    const res = await fetch(url, {
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        ...opts,
    });
    if (!res.ok) throw new Error(await res.text());
    if (res.status === 204) return null;
    return res.json();
}

async function loadCalendars() {
    try {
        const { data } = await api('/api/calendars');
        const container = document.getElementById('calendarsList');
        if (!data.length) {
            container.innerHTML = `
                <div class="eos-empty">
                    <div class="eos-empty__icon">📅</div>
                    <div class="eos-empty__title">Nenhum calendário ainda</div>
                    <p class="eos-empty__sub">Crie seu primeiro calendário para começar a organizar seus eventos.</p>
                    <button class="eos-btn eos-btn--primary" onclick="window.openCreateModal()">＋ Criar</button>
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
                <div class="eos-cal-card__name">${escapeHtml(cal.name)}</div>
                <div class="eos-cal-card__desc">${cal.description ? escapeHtml(cal.description) : 'Sem descrição'}</div>
                <div class="eos-cal-card__badges">
                    ${cal.isDefault ? '<span class="eos-badge eos-badge--default">⭐ Padrão</span>' : ''}
                    ${!cal.isActive ? '<span class="eos-badge eos-badge--inactive">Inativo</span>' : ''}
                    ${countLabel ? `<span class="eos-badge eos-badge--count">📅 ${countLabel}</span>` : ''}
                </div>
            </div>
            <div class="eos-cal-card__footer" onclick="event.stopPropagation()">
                <button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="window.openEditModal(${cal.id})">Editar</button>
                ${!cal.isDefault ? `<button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="window.makeDefault(${cal.id})">Tornar padrão</button>` : ''}
                <button class="eos-btn eos-btn--danger eos-btn--sm" onclick="window.confirmDeleteModal(${cal.id})">Excluir</button>
            </div>
        </div>`;
}

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
document.getElementById('color').addEventListener('input', e => syncColorPresets(e.target.value));

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

document.getElementById('openCreateModal').addEventListener('click', openCreateModal);
document.getElementById('closeModal').addEventListener('click', closeModal);
document.getElementById('closeModalBtn').addEventListener('click', closeModal);
document.getElementById('calendarModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });
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

function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

window.openCreateModal = openCreateModal;
window.openEditModal = openEditModal;
window.makeDefault = makeDefault;
window.confirmDeleteModal = confirmDeleteModal;
window.loadCalendars = loadCalendars;

loadCalendars();