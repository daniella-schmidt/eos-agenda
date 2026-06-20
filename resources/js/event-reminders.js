// EOS - Event Reminders UI controller

const el = (id) => document.getElementById(id);

function safeJson(json) {
  try {
    return JSON.parse(json);
  } catch {
    return null;
  }
}

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '<')
    .replace(/>/g, '>')
    .replace(/"/g, '"');
}

function mountEventReminders() {
  const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfTokenEl?.content;

  const eventSelect = el('event-id');
  const reminderSelect = el('reminder-id');
  const output = el('output');
  const statusBadge = el('last-status');

  if (!csrfToken || !eventSelect || !reminderSelect || !output || !statusBadge) {
    // Not the expected page
    return;
  }

  const typeLabels = { notification: 'Notificação', email: 'E-mail', whatsapp: 'WhatsApp' };
  let reminders = [];

  function setStatus(text, successful = null) {
    statusBadge.textContent = text;
    statusBadge.className = 'inline-flex w-fit rounded px-3 py-1 text-xs font-medium';
    statusBadge.classList.add(
      successful === true
        ? 'bg-green-100'
        : successful === false
          ? 'bg-red-100'
          : 'bg-gray-100',
      successful === true
        ? 'text-green-800'
        : successful === false
          ? 'text-red-800'
          : 'text-gray-700',
    );
  }

  function selectedEventId() {
    if (!eventSelect.value) throw new Error('Selecione um evento.');
    return eventSelect.value;
  }

  function selectedReminderId() {
    if (!reminderSelect.value) throw new Error('Selecione um lembrete.');
    return reminderSelect.value;
  }

  async function request(method, url, body = null) {
    setStatus('Carregando...');

    const response = await fetch(url, {
      method,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: body === null ? null : JSON.stringify(body),
    });

    let data = null;
    try {
      data = await response.json();
    } catch {
      data = safeJson(await response.text()) ?? null;
    }

    const msg = response.ok
      ? 'Ação concluída com sucesso.'
      : (data?.message || data?.error || 'Não foi possível concluir a ação.');

    output.textContent = msg;
    setStatus(response.ok ? 'Concluído' : 'Erro', response.ok);

    return { response, data };
  }

  async function run(callback) {
    try {
      await callback();
    } catch (error) {
      setStatus('Erro', false);
      output.textContent = error?.message || 'Erro inesperado.';
    }
  }

  async function loadEvents() {
    const result = await request('GET', '/api/events?perPage=100');
    if (!result.response.ok) return;

    const current = eventSelect.value;
    const events = result.data?.data ?? [];

    eventSelect.innerHTML = '<option value="">Selecione um evento</option>';
    events.forEach((event) => eventSelect.add(new Option(event.title, event.id)));

    if ([...eventSelect.options].some((option) => option.value === current)) eventSelect.value = current;
  }

  function renderReminders(items, preferredId = null) {
    reminders = items;
    const current = preferredId ? String(preferredId) : reminderSelect.value;

    reminderSelect.innerHTML = '';

    if (!items.length) {
      reminderSelect.add(new Option('Nenhum lembrete encontrado', ''));
      fillUpdateForm();
      return;
    }

    reminderSelect.add(new Option('Selecione um lembrete', ''));
    items.forEach((reminder) => {
      const sent = reminder.isSent ? 'enviado' : 'pendente';
      const label = `${typeLabels[reminder.type] ?? reminder.type}, ${reminder.minutesBefore} min antes (${sent})`;
      reminderSelect.add(new Option(label, reminder.id));
    });

    if ([...reminderSelect.options].some((option) => option.value === current)) reminderSelect.value = current;
    fillUpdateForm();
  }

  function fillUpdateForm() {
    const reminder = reminders.find((item) => String(item.id) === reminderSelect.value);
    el('update-type').value = reminder?.type ?? 'notification';
    el('update-minutes').value = reminder?.minutesBefore ?? '';
  }

  async function loadReminders(preferredId = null) {
    const result = await request('GET', `/api/events/${selectedEventId()}/reminders`);
    if (result.response.ok) renderReminders(result.data?.data ?? [], preferredId);
    return result;
  }

  // Bindings
  el('reload-events').addEventListener('click', () => run(loadEvents));
  el('list-button').addEventListener('click', () => run(loadReminders));

  eventSelect.addEventListener('change', () => {
    reminders = [];
    renderReminders([]);
    if (eventSelect.value) run(loadReminders);
  });

  reminderSelect.addEventListener('change', fillUpdateForm);

  el('create-form').addEventListener('submit', (event) => {
    event.preventDefault();
    run(async () => {
      const payload = {
        type: el('create-type').value,
        minutesBefore: Number(el('create-minutes').value),
      };
      const result = await request('POST', `/api/events/${selectedEventId()}/reminders`, payload);
      if (result.response.ok) await loadReminders(result.data?.data?.id);
    });
  });

  el('show-button').addEventListener('click', () => run(() => request('GET', `/api/event-reminders/${selectedReminderId()}`)));

  el('update-form').addEventListener('submit', (event) => {
    event.preventDefault();
    run(async () => {
      const id = selectedReminderId();
      const payload = {
        type: el('update-type').value,
        minutesBefore: Number(el('update-minutes').value),
      };
      const result = await request('PATCH', `/api/event-reminders/${id}`, payload);
      if (result.response.ok) await loadReminders(id);
    });
  });

  el('sent-button').addEventListener('click', () => run(async () => {
    const id = selectedReminderId();
    const result = await request('POST', `/api/event-reminders/${id}/mark-as-sent`, {});
    if (result.response.ok) await loadReminders(id);
  }));

  el('delete-button').addEventListener('click', () => run(async () => {
    if (!confirm('Excluir este lembrete? Esta ação não pode ser desfeita.')) return;
    const result = await request('DELETE', `/api/event-reminders/${selectedReminderId()}`);
    if (result.response.ok) await loadReminders();
  }));

  el('clear-output').addEventListener('click', () => {
    output.textContent = 'Escolha uma ação para acompanhar o resultado aqui.';
    setStatus('Pronto');
  });

  // Init
  setStatus('Pronto');
  output.textContent = 'Escolha uma ação para acompanhar o resultado aqui.';
  run(loadEvents);
}

document.addEventListener('DOMContentLoaded', mountEventReminders);

window.__mountEventReminders = mountEventReminders;

