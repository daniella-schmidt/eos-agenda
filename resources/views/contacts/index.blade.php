<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-[#f6fbfb]">
        <style>
            .contacts-page {
                max-width: 1280px;
                margin: 0 auto;
                padding: 24px;
            }

            .contacts-shell {
                display: grid;
                grid-template-columns: minmax(320px, 430px) minmax(0, 1fr);
                gap: 20px;
                align-items: start;
            }

            .contacts-card {
                background: #ffffff;
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                box-shadow: 0 14px 35px rgba(13, 43, 43, .06);
            }

            .contacts-card__header {
                padding: 18px;
                border-bottom: 1px solid #dbe7e7;
            }

            .contacts-card__body {
                padding: 18px;
            }

            .contacts-eyebrow {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .18em;
                text-transform: uppercase;
            }

            .contacts-title {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: 1.35rem;
                font-weight: 900;
            }

            .contacts-muted {
                color: #647878;
                font-size: .9rem;
                font-weight: 600;
            }

            .contacts-search,
            .contacts-field input,
            .contacts-field textarea {
                width: 100%;
                border: 1px solid #cfe0e0;
                border-radius: 8px;
                background: #ffffff;
                color: #0d2b2b;
                padding: 10px 12px;
                font-size: .92rem;
                font-weight: 700;
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .contacts-search:focus,
            .contacts-field input:focus,
            .contacts-field textarea:focus {
                border-color: #008f91;
                box-shadow: 0 0 0 3px rgba(0, 143, 145, .12);
            }

            .contacts-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-height: 680px;
                overflow: auto;
            }

            .contact-item {
                width: 100%;
                border: 1px solid #dbe7e7;
                border-left: 6px solid #008f91;
                border-radius: 8px;
                background: #ffffff;
                padding: 12px;
                text-align: left;
                cursor: pointer;
                transition: border-color .15s ease, background .15s ease, transform .15s ease;
            }

            .contact-item:hover,
            .contact-item.is-selected {
                border-color: #008f91;
                background: #fafdff;
                transform: translateY(-1px);
            }

            .contact-item__name {
                color: #0d2b2b;
                font-size: .96rem;
                font-weight: 900;
            }

            .contact-item__meta {
                margin-top: 4px;
                color: #647878;
                font-size: .84rem;
                font-weight: 700;
            }

            .contacts-form-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }

            .contacts-field {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .contacts-field label {
                color: #008f91;
                font-size: .72rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .contacts-field textarea {
                min-height: 110px;
                resize: vertical;
            }

            .contacts-field--full {
                grid-column: 1 / -1;
            }

            .contacts-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                align-items: center;
            }

            .contacts-btn {
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

            .contacts-btn:hover {
                transform: translate(-1px, -1px);
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .contacts-btn:disabled {
                cursor: not-allowed;
                opacity: .55;
                transform: none;
                box-shadow: none;
            }

            .contacts-btn--primary {
                background: #008f91;
                color: #ffffff;
                box-shadow: 3px 3px 0 #0d2b2b;
            }

            .contacts-btn--ghost {
                background: #ffffff;
                color: #0d2b2b;
            }

            .contacts-btn--danger {
                background: #fff0f0;
                border-color: #c0392b;
                color: #c0392b;
            }

            .contacts-empty,
            .contacts-feedback {
                border: 1px dashed #cfe0e0;
                border-radius: 8px;
                padding: 16px;
                background: #ffffff;
                color: #647878;
                font-size: .9rem;
                font-weight: 700;
            }

            .contacts-feedback {
                display: none;
                border-style: solid;
                border-color: #b8eeee;
                background: #e5ffff;
                color: #0d2b2b;
                font-weight: 800;
            }

            .contacts-feedback.is-visible {
                display: block;
            }

            .contacts-feedback.is-error {
                border-color: #f3b4b4;
                background: #fff0f0;
                color: #a32222;
            }

            .contact-detail {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .contact-detail__item {
                border: 1px solid #dbe7e7;
                border-radius: 8px;
                background: #fafdff;
                padding: 12px;
            }

            .contact-detail__label {
                color: #008f91;
                font-size: .7rem;
                font-weight: 900;
                letter-spacing: .12em;
                text-transform: uppercase;
            }

            .contact-detail__value {
                margin-top: 4px;
                color: #0d2b2b;
                font-size: .94rem;
                font-weight: 800;
                word-break: break-word;
            }

            .contact-detail__item--full {
                grid-column: 1 / -1;
            }

            @media (max-width: 980px) {
                .contacts-shell,
                .contacts-form-grid,
                .contact-detail {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="contacts-page">
            <div class="mb-5">
                <p class="contacts-eyebrow">Contatos</p>
                <h1 class="contacts-title">Gerencie seus contatos</h1>
            </div>

            <div class="contacts-shell">
                <section class="contacts-card">
                    <div class="contacts-card__header">
                        <p class="contacts-eyebrow">Lista</p>
                        <h2 class="contacts-title">Contatos cadastrados</h2>
                    </div>

                    <div class="contacts-card__body space-y-4">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input id="searchInput" class="contacts-search" type="search" placeholder="Buscar por nome, email ou empresa">
                            <button id="searchBtn" class="contacts-btn contacts-btn--ghost" type="button">Buscar</button>
                        </div>

                        <div id="contactsList" class="contacts-list">
                            <div class="contacts-empty">Carregando contatos...</div>
                        </div>
                    </div>
                </section>

                <div class="space-y-5">
                    <section class="contacts-card">
                        <div class="contacts-card__header">
                            <p class="contacts-eyebrow">Cadastro</p>
                            <h2 id="formTitle" class="contacts-title">Novo contato</h2>
                        </div>

                        <div class="contacts-card__body">
                            <form id="contactForm" class="space-y-5">
                                <input id="contactId" type="hidden">

                                <div class="contacts-form-grid">
                                    <div class="contacts-field">
                                        <label for="name">Nome</label>
                                        <input id="name" maxlength="120" required type="text">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="email">E-mail</label>
                                        <input id="email" maxlength="180" type="email">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="phone">Telefone</label>
                                        <input id="phone" maxlength="40" type="text">
                                    </div>

                                    <div class="contacts-field">
                                        <label for="company">Empresa</label>
                                        <input id="company" maxlength="120" type="text">
                                    </div>

                                    <div class="contacts-field contacts-field--full">
                                        <label for="notes">Observacoes</label>
                                        <textarea id="notes" maxlength="1000"></textarea>
                                    </div>
                                </div>

                                <div id="formFeedback" class="contacts-feedback" role="status"></div>

                                <div class="contacts-actions">
                                    <button id="saveBtn" class="contacts-btn contacts-btn--primary" type="submit">Salvar contato</button>
                                    <button id="newBtn" class="contacts-btn contacts-btn--ghost" type="button">Novo</button>
                                    <button id="deleteBtn" class="contacts-btn contacts-btn--danger" type="button" disabled>Excluir</button>
                                </div>
                            </form>
                        </div>
                    </section>

                    <section class="contacts-card">
                        <div class="contacts-card__header">
                            <p class="contacts-eyebrow">Detalhes</p>
                            <h2 class="contacts-title">Contato selecionado</h2>
                        </div>

                        <div class="contacts-card__body">
                            <div id="emptyDetail" class="contacts-empty">
                                Selecione um contato para visualizar seus dados.
                            </div>

                            <div id="contactDetail" class="contact-detail hidden"></div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            let contacts = [];
            let selectedContact = null;

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

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function nullable(value) {
                const text = value.trim();
                return text.length ? text : null;
            }

            function showFeedback(message, type = 'success') {
                const feedback = document.getElementById('formFeedback');
                feedback.textContent = message;
                feedback.className = `contacts-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
            }

            function clearFeedback() {
                const feedback = document.getElementById('formFeedback');
                feedback.textContent = '';
                feedback.className = 'contacts-feedback';
            }

            async function loadContacts(preferredId = null) {
                const params = new URLSearchParams({ perPage: '100' });
                const search = document.getElementById('searchInput').value.trim();

                if (search) {
                    params.set('search', search);
                }

                const list = document.getElementById('contactsList');
                list.innerHTML = '<div class="contacts-empty">Carregando contatos...</div>';

                try {
                    const response = await api(`/api/contacts?${params.toString()}`);
                    contacts = response.data || [];
                    renderContacts();

                    const nextSelected = preferredId
                        ? contacts.find(contact => Number(contact.id) === Number(preferredId))
                        : contacts.find(contact => selectedContact && Number(contact.id) === Number(selectedContact.id));

                    if (nextSelected) {
                        selectContact(nextSelected.id);
                    } else if (!contacts.length) {
                        selectContact(null);
                    }
                } catch (error) {
                    list.innerHTML = `<div class="contacts-empty">${escapeHtml(error.message)}</div>`;
                }
            }

            function renderContacts() {
                const list = document.getElementById('contactsList');

                if (!contacts.length) {
                    list.innerHTML = '<div class="contacts-empty">Nenhum contato encontrado.</div>';
                    return;
                }

                list.innerHTML = contacts.map(contact => `
                    <button
                        class="contact-item ${selectedContact?.id === contact.id ? 'is-selected' : ''}"
                        type="button"
                        data-contact-id="${contact.id}"
                    >
                        <div class="contact-item__name">${escapeHtml(contact.name)}</div>
                        <div class="contact-item__meta">
                            ${escapeHtml(contact.email || contact.phone || 'Sem contato principal')}
                        </div>
                        ${contact.company ? `<div class="contact-item__meta">${escapeHtml(contact.company)}</div>` : ''}
                    </button>
                `).join('');
            }

            function fillForm(contact = null) {
                document.getElementById('contactId').value = contact?.id || '';
                document.getElementById('name').value = contact?.name || '';
                document.getElementById('email').value = contact?.email || '';
                document.getElementById('phone').value = contact?.phone || '';
                document.getElementById('company').value = contact?.company || '';
                document.getElementById('notes').value = contact?.notes || '';
                document.getElementById('formTitle').textContent = contact ? 'Editar contato' : 'Novo contato';
                document.getElementById('deleteBtn').disabled = !contact;
            }

            function renderDetail(contact = null) {
                const empty = document.getElementById('emptyDetail');
                const detail = document.getElementById('contactDetail');

                empty.classList.toggle('hidden', Boolean(contact));
                detail.classList.toggle('hidden', !contact);

                if (!contact) {
                    detail.innerHTML = '';
                    return;
                }

                detail.innerHTML = `
                    ${detailItem('Nome', contact.name)}
                    ${detailItem('E-mail', contact.email || 'Nao informado')}
                    ${detailItem('Telefone', contact.phone || 'Nao informado')}
                    ${detailItem('Empresa', contact.company || 'Nao informado')}
                    ${detailItem('Observacoes', contact.notes || 'Sem observacoes', true)}
                `;
            }

            function detailItem(label, value, full = false) {
                return `
                    <div class="contact-detail__item ${full ? 'contact-detail__item--full' : ''}">
                        <div class="contact-detail__label">${label}</div>
                        <div class="contact-detail__value">${escapeHtml(value)}</div>
                    </div>
                `;
            }

            function selectContact(id) {
                selectedContact = contacts.find(contact => Number(contact.id) === Number(id)) || null;
                fillForm(selectedContact);
                renderDetail(selectedContact);
                renderContacts();
                clearFeedback();
            }

            function resetForm() {
                selectedContact = null;
                fillForm(null);
                renderDetail(null);
                renderContacts();
                clearFeedback();
                document.getElementById('name').focus();
            }

            function formPayload() {
                return {
                    name: document.getElementById('name').value,
                    email: nullable(document.getElementById('email').value),
                    phone: nullable(document.getElementById('phone').value),
                    company: nullable(document.getElementById('company').value),
                    notes: nullable(document.getElementById('notes').value),
                };
            }

            async function saveContact(event) {
                event.preventDefault();
                clearFeedback();

                const id = document.getElementById('contactId').value;
                const button = document.getElementById('saveBtn');
                button.disabled = true;

                try {
                    const response = await api(id ? `/api/contacts/${id}` : '/api/contacts', {
                        method: id ? 'PATCH' : 'POST',
                        body: JSON.stringify(formPayload()),
                    });

                    showFeedback(id ? 'Contato atualizado.' : 'Contato criado.');
                    await loadContacts(response.data?.id);
                } catch (error) {
                    showFeedback(error.message, 'error');
                } finally {
                    button.disabled = false;
                }
            }

            async function deleteContact() {
                if (!selectedContact || !confirm('Excluir este contato?')) {
                    return;
                }

                clearFeedback();
                const button = document.getElementById('deleteBtn');
                button.disabled = true;

                try {
                    await api(`/api/contacts/${selectedContact.id}`, { method: 'DELETE' });
                    showFeedback('Contato excluido.');
                    contacts = contacts.filter(contact => contact.id !== selectedContact.id);
                    resetForm();
                    renderContacts();
                } catch (error) {
                    showFeedback(error.message, 'error');
                } finally {
                    button.disabled = false;
                }
            }

            document.getElementById('contactForm').addEventListener('submit', saveContact);
            document.getElementById('newBtn').addEventListener('click', resetForm);
            document.getElementById('deleteBtn').addEventListener('click', deleteContact);
            document.getElementById('searchBtn').addEventListener('click', () => loadContacts());
            document.getElementById('searchInput').addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    loadContacts();
                }
            });
            document.getElementById('contactsList').addEventListener('click', event => {
                const button = event.target.closest('[data-contact-id]');

                if (button) {
                    selectContact(button.dataset.contactId);
                }
            });

            loadContacts();
        </script>
    </div>
</x-app-layout>
