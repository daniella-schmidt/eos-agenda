/* ==========================================================================
   EOS AGENDA — script.js
   Funcionalidades: Scroll Reveal, Contadores, Calendário Interativo
   Versão: 2.0 (Refatorado)
   ========================================================================== */

// ==========================================================================
// 1. SCROLL REVEAL (Animações ao rolar a página)
// ==========================================================================
(function initScrollReveal() {
  const revealElements = document.querySelectorAll('[data-r]');
  if (!revealElements.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const delay = parseInt(entry.target.dataset.delay, 10) || 0;
          setTimeout(() => {
            entry.target.classList.add('on');
          }, delay);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.14 }
  );

  revealElements.forEach((el) => observer.observe(el));
})();

// ==========================================================================
// 2. CONTADORES NUMÉRICOS (para seção de estatísticas)
// ==========================================================================
(function initCounters() {
  const counters = document.querySelectorAll('[data-target]');
  if (!counters.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const el = entry.target;
        const target = parseInt(el.dataset.target, 10);
        const suffix = el.dataset.suf || '';
        let current = 0;
        const step = target / (1500 / 16);

        const timer = setInterval(() => {
          current = Math.min(current + step, target);
          el.textContent = Math.floor(current).toLocaleString('pt-BR') + suffix;
          if (current >= target) clearInterval(timer);
        }, 16);

        observer.unobserve(el);
      });
    },
    { threshold: 0.5 }
  );

  counters.forEach((counter) => observer.observe(counter));
})();

// ==========================================================================
// 3. CALENDÁRIO INTERATIVO (Eventos, navegação, tooltips)
// ==========================================================================
(function initCalendar() {
  // Elementos do DOM
  const gridContainer = document.getElementById('cal-grid');
  const monthLabel = document.getElementById('cal-lbl');
  const prevBtn = document.getElementById('cal-prev');
  const nextBtn = document.getElementById('cal-next');

  if (!gridContainer || !monthLabel) return;

  // Dados do calendário
  const months = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
  ];

  // Eventos de exemplo (dia -> lista de eventos)
  const events = {
    3:  [{ emoji: '📚', title: 'Entrega de Projeto — Programação III' }],
    7:  [{ emoji: '💼', title: 'Reunião de Equipe às 14h' }],
    12: [{ emoji: '🎂', title: 'Aniversário da Ana' }],
    15: [{ emoji: '📝', title: 'Prova de Redes de Computadores' }],
    19: [{ emoji: '🌟', title: 'Apresentação do TCC' }],
    22: [{ emoji: '☕', title: 'Café com o orientador' }],
    25: [{ emoji: '💡', title: 'Workshop de Laravel' }],
  };

  let currentYear = 2026;
  let currentMonth = 5; // Junho (0-index)

  // Função principal para renderizar o calendário
  function renderCalendar() {
    monthLabel.textContent = `${months[currentMonth]} ${currentYear}`;
    gridContainer.innerHTML = '';

    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const today = new Date();

    // Preencher dias vazios antes do início do mês
    for (let i = 0; i < firstDayOfMonth; i++) {
      const emptyCell = document.createElement('div');
      emptyCell.className = 'cd empty';
      gridContainer.appendChild(emptyCell);
    }

    // Preencher os dias do mês
    for (let day = 1; day <= daysInMonth; day++) {
      const dayCell = document.createElement('div');
      dayCell.className = 'cd';
      dayCell.textContent = day;

      // Destacar dia atual
      const isToday =
        currentYear === today.getFullYear() &&
        currentMonth === today.getMonth() &&
        day === today.getDate();
      if (isToday) dayCell.classList.add('today');

      // Adicionar eventos e tooltip se houver
      if (events[day]) {
        dayCell.classList.add('has-ev');
        const tooltip = document.createElement('div');
        tooltip.className = 'tip';
        const eventsHtml = events[day]
          .map((ev) => `<div class="tip-ev"><span>${ev.emoji}</span><span>${ev.title}</span></div>`)
          .join('');
        tooltip.innerHTML = `
          <div class="tip-date">📅 ${day} de ${months[currentMonth]}</div>
          ${eventsHtml}
        `;
        dayCell.appendChild(tooltip);
        dayCell.style.position = 'relative';
      }

      gridContainer.appendChild(dayCell);
    }
  }

  // Navegação do calendário
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      renderCalendar();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      renderCalendar();
    });
  }

  // Renderização inicial
  renderCalendar();
})();

// ==========================================================================
// 4. SMOOTH SCROLL PARA LINKS INTERNOS (#)
// ==========================================================================
(function initSmoothScroll() {
  const internalLinks = document.querySelectorAll('a[href^="#"]');
  internalLinks.forEach((link) => {
    link.addEventListener('click', (event) => {
      const targetId = link.getAttribute('href');
      if (targetId === '#') return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        event.preventDefault();
        targetElement.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
})();