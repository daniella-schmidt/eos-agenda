const csrf = document.querySelector('meta[name="csrf-token"]').content;
let currentTab = 'all';
let allRequests = [];

function toast(msg, type = 'success') { /* igual ao anterior */ }

async function api(url, opts = {}) { /* igual ao api do calendars.js */ }

async function loadCalendarsForSelect() {
    const { data } = await api('/api/calendars');
    const select = document.getElementById('calendarSelect');
    const editSelect = document.getElementById('editCalendarSelect');
    const options = data.map(c => `<option value="${c.id}" ${c.isDefault ? 'selected' : ''}>${escapeHtml(c.name)}</option>`).join('');
    select.innerHTML = '<option value="">Sem calendário específico</option>' + options;
    editSelect.innerHTML = '<option value="">Selecione um calendário</option>' + options;
}

async function loadRequests() {
    const statuses = ['pending','processing','extracted','confirmed','failed'];
    const results = await Promise.allSettled(statuses.map(s => api(`/api/smart-requests/status/${s}`)));
    allRequests = results.filter(r => r.status === 'fulfilled' && r.value?.data).flatMap(r => r.value.data);
    renderList();
}

function renderList() {
    const filtered = currentTab === 'all' ? allRequests : allRequests.filter(r => r.status === currentTab);
    const container = document.getElementById('srList');
    if (!filtered.length) {
        container.innerHTML = `<div class="eos-empty"><div class="eos-empty__icon">🤖</div><div class="eos-empty__title">Nada aqui</div><p class="eos-empty__sub">Nenhuma solicitação com este status.</p></div>`;
        return;
    }
    container.innerHTML = `<div class="sr-list">${filtered.sort((a,b)=>new Date(b.createdAt)-new Date(a.createdAt)).map(renderCard).join('')}</div>`;
}

function renderCard(req) {
    const statusMap = {
        pending:    { label: 'Pendente', cls: 'sr-status--pending' },
        processing: { label: 'Processando', cls: 'sr-status--processing' },
        extracted:  { label: 'Extraída',   cls: 'sr-status--extracted' },
        confirmed:  { label: 'Confirmada',  cls: 'sr-status--confirmed' },
        failed:     { label: 'Com erro',    cls: 'sr-status--failed' },
    };
    const st = statusMap[req.status] || { label: req.status, cls: '' };
    return `
        <div class="sr-card">
            <div class="sr-card__bar ${req.status === 'extracted' ? 'sr-card__bar--success' : 'sr-card__bar--pending'}"></div>
            <div class="sr-card__body">
                <div class="sr-card__top">
                    <div class="sr-card__raw">"${escapeHtml(req.rawText)}"</div>
                    <span class="sr-status-badge ${st.cls}">${st.label}</span>
                </div>
                ${req.extractedTitle ? `<div class="sr-extracted"><div class="sr-field"><div class="sr-field__label">Título</div><div class="sr-field__value">${escapeHtml(req.extractedTitle)}</div></div>${req.extractedStartAt ? `<div class="sr-field"><div class="sr-field__label">Início</div><div class="sr-field__value">${new Date(req.extractedStartAt).toLocaleString()}</div></div>` : ''}</div>` : ''}
            </div>
            <div class="sr-card__footer">
                ${['extracted','pending','failed'].includes(req.status) ? `<button class="eos-btn eos-btn--ghost eos-btn--sm" onclick="openEditModal(${req.id})">Revisar</button>` : ''}
                ${req.status === 'extracted' ? `<button class="eos-btn eos-btn--success eos-btn--sm" onclick="quickConfirm(${req.id})">Confirmar</button>` : ''}
                <button class="eos-btn eos-btn--danger eos-btn--sm" onclick="deleteRequest(${req.id})">Excluir</button>
                <div class="sr-card__time">${new Date(req.createdAt).toLocaleString()}</div>
            </div>
        </div>`;
}

async function quickConfirm(id) {
    await api(`/api/smart-requests/${id}/confirm`, { method: 'POST' });
    toast('Evento confirmado!');
    loadRequests();
}

async function deleteRequest(id) {
    if (!confirm('Excluir esta solicitação?')) return;
    await api(`/api/smart-requests/${id}`, { method: 'DELETE' });
    toast('Solicitação excluída');
    allRequests = allRequests.filter(r => r.id !== id);
    renderList();
}

function openEditModal(id) {
    const req = allRequests.find(r => r.id === id);
    if (!req) return;
    document.getElementById('editId').value = id;
    document.getElementById('editRaw').value = req.rawText;
    document.getElementById('editTitle').value = req.extractedTitle || '';
    document.getElementById('editDescription').value = req.extractedDescription || '';
    document.getElementById('editStartAt').value = req.extractedStartAt ? new Date(req.extractedStartAt).toISOString().slice(0,16) : '';
    document.getElementById('editEndAt').value = req.extractedEndAt ? new Date(req.extractedEndAt).toISOString().slice(0,16) : '';
    document.getElementById('confirmBtn').style.display = req.status === 'extracted' ? '' : 'none';
    document.getElementById('editModal').classList.add('open');
}

async function saveEdit() {
    const id = document.getElementById('editId').value;
    const payload = {
        extractedTitle: document.getElementById('editTitle').value || null,
        extractedDescription: document.getElementById('editDescription').value || null,
        extractedStartAt: document.getElementById('editStartAt').value || null,
        extractedEndAt: document.getElementById('editEndAt').value || null,
    };
    await api(`/api/smart-requests/${id}`, { method: 'PATCH', body: JSON.stringify(payload) });
    toast('Solicitação atualizada ✓');
    loadRequests();
    document.getElementById('editModal').classList.remove('open');
}

async function confirmRequest() {
    const id = document.getElementById('editId').value;
    await saveEdit();
    await quickConfirm(id);
}

function switchTab(btn) {
    document.querySelectorAll('.sr-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    currentTab = btn.dataset.status;
    renderList();
}

document.getElementById('sendBtn').addEventListener('click', async () => {
    const text = document.getElementById('rawText').value.trim();
    if (!text) { toast('Escreva uma solicitação', 'error'); return; }
    await api('/api/smart-requests', { method: 'POST', body: JSON.stringify({ rawText: text }) });
    document.getElementById('rawText').value = '';
    toast('Solicitação enviada!', 'info');
    loadRequests();
});
document.querySelectorAll('.sr-tab').forEach(btn => btn.addEventListener('click', () => switchTab(btn)));
document.getElementById('closeModalBtn').addEventListener('click', () => document.getElementById('editModal').classList.remove('open'));
document.getElementById('confirmBtn').addEventListener('click', confirmRequest);
window.openEditModal = openEditModal;
window.quickConfirm = quickConfirm;
window.deleteRequest = deleteRequest;
window.saveEdit = saveEdit;
window.confirmRequest = confirmRequest;
window.switchTab = switchTab;
window.fillExample = (chip) => {
    document.getElementById('rawText').value = chip.textContent;
    document.getElementById('rawText').dispatchEvent(new Event('input'));
};

loadCalendarsForSelect();
loadRequests();