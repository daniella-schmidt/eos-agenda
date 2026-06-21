(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let contacts = [];
    let selectedContact = null;

    const el = (id) => document.getElementById(id);

    const api = async (url, options = {}) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            ...options,
        });

        if (response.status === 204) return null;

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(payload.message || 'Nao foi possivel concluir a operacao.');
        }

        return payload;
    };

    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/"/g, '"');

    const nullable = (value) => {
        const text = (value ?? '').trim();
        return text.length ? text : null;
    };

    const showFeedback = (message, type = 'success') => {
        const feedback = el('formFeedback');
        if (!feedback) return;
        feedback.textContent = message;
        feedback.className = `contacts-feedback is-visible ${type === 'error' ? 'is-error' : ''}`;
    };

    const clearFeedback = () => {
        const feedback = el('formFeedback');
        if (!feedback) return;
        feedback.textContent = '';
        feedback.className = 'contacts-feedback';
    };

    async function loadContacts(preferredId = null) {
        const params = new URLSearchParams({ perPage: '100' });
        const search = el('searchInput')?.value?.trim();

        if (search) params.set('search', search);

        const list = el('contactsList');
        if (!list) return;

        list.innerHTML = '<div class="contacts-empty">Carregando contatos...</div>';

        try {
            const response = await api(`/api/contacts?${params.toString()}`);
            contacts = response.data || [];
            renderContacts();

            const nextSelected = preferredId
                ? contacts.find((c) => Number(c.id) === Number(preferredId))
                : contacts.find((c) => selectedContact && Number(c.id) === Number(selectedContact.id));

            if (nextSelected) selectContact(nextSelected.id);
            else if (!contacts.length) selectContact(null);
        } catch (error) {
            list.innerHTML = `<div class="contacts-empty">${escapeHtml(error.message)}</div>`;
        }
    }

    function renderContacts() {
        const list = el('contactsList');
        if (!list) return;

        if (!contacts.length) {
            list.innerHTML = '<div class="contacts-empty">Nenhum contato encontrado.</div>';
            return;
        }

        list.innerHTML = contacts
            .map(
                (contact) => `
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
            `
            )
            .join('');
    }

    function fillForm(contact = null) {
        el('contactId').value = contact?.id || '';
        el('name').value = contact?.name || '';
        el('email').value = contact?.email || '';
        el('phone').value = contact?.phone || '';
        el('company').value = contact?.company || '';
        el('notes').value = contact?.notes || '';

        el('formTitle').textContent = contact ? 'Editar contato' : 'Novo contato';
        el('deleteBtn').disabled = !contact;
    }

    function renderDetail(contact = null) {
        const empty = el('emptyDetail');
        const detail = el('contactDetail');

        if (empty) empty.classList.toggle('hidden', Boolean(contact));
        if (detail) detail.classList.toggle('hidden', !contact);

        if (!detail) return;
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
        selectedContact = contacts.find((c) => Number(c.id) === Number(id)) || null;
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
        el('name').focus();
    }

    function formPayload() {
        return {
            name: el('name').value,
            email: nullable(el('email').value),
            phone: nullable(el('phone').value),
            company: nullable(el('company').value),
            notes: nullable(el('notes').value),
        };
    }

    async function saveContact(event) {
        event.preventDefault();
        clearFeedback();

        const id = el('contactId').value;
        const button = el('saveBtn');
        if (button) button.disabled = true;

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
            if (button) button.disabled = false;
        }
    }

    async function deleteContact() {
        if (!selectedContact || !confirm('Excluir este contato?')) return;

        clearFeedback();
        const button = el('deleteBtn');
        if (button) button.disabled = true;

        try {
            await api(`/api/contacts/${selectedContact.id}`, { method: 'DELETE' });
            showFeedback('Contato excluido.');

            contacts = contacts.filter((c) => c.id !== selectedContact.id);
            resetForm();
            renderContacts();
        } catch (error) {
            showFeedback(error.message, 'error');
        } finally {
            if (button) button.disabled = false;
        }
    }

    el('contactForm')?.addEventListener('submit', saveContact);
    el('newBtn')?.addEventListener('click', resetForm);
    el('deleteBtn')?.addEventListener('click', deleteContact);

    el('searchBtn')?.addEventListener('click', () => loadContacts());
    el('searchInput')?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            loadContacts();
        }
    });

    el('contactsList')?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-contact-id]');
        if (!button) return;
        selectContact(button.dataset.contactId);
    });

    loadContacts();
})();

