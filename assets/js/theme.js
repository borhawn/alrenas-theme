(() => {
  const header = document.querySelector('[data-header]');
  const navToggle = document.querySelector('[data-nav-toggle]');
  const nav = document.querySelector('[data-nav]');

  const setHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 12);
  setHeader();
  addEventListener('scroll', setHeader, { passive: true });

  const mobileNavQuery = window.matchMedia('(max-width: 860px)');

  const closeMobileSubmenus = () => {
    nav?.querySelectorAll('.menu-item-has-children.is-open').forEach(li => li.classList.remove('is-open'));
  };

  const closeNav = () => {
    document.body.classList.remove('menu-open');
    navToggle?.setAttribute('aria-expanded', 'false');
    closeMobileSubmenus();
  };

  navToggle?.addEventListener('click', () => {
    const open = document.body.classList.toggle('menu-open');
    navToggle.setAttribute('aria-expanded', String(open));
    if (!open) closeMobileSubmenus();
  });
  nav?.querySelectorAll('.menu-item-has-children > a').forEach(link => {
    link.addEventListener('click', (e) => {
      if (!mobileNavQuery.matches) return;
      const li = link.parentElement;
      if (!li.classList.contains('is-open')) {
        e.preventDefault();
        closeMobileSubmenus();
        li.classList.add('is-open');
      }
    });
  });
  nav?.querySelectorAll('a').forEach(a => a.addEventListener('click', (e) => {
    if (e.defaultPrevented) return;
    closeNav();
  }));
  document.addEventListener('click', (e) => {
    if (!document.body.classList.contains('menu-open')) return;
    if (nav?.contains(e.target) || navToggle?.contains(e.target)) return;
    closeNav();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.body.classList.contains('menu-open')) {
      closeNav();
    }
  });

  const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -30px' });
  document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

  const tabs = document.querySelectorAll('[data-filter]');
  const cards = document.querySelectorAll('[data-category]');
  const empty = document.querySelector('[data-empty]');
  if (tabs.length && cards.length) {
    tabs.forEach(tab => tab.addEventListener('click', () => {
      const filter = tab.dataset.filter;
      tabs.forEach(t => t.classList.toggle('is-active', t === tab));
      let shown = 0;
      cards.forEach(card => {
        const show = filter === 'all' || card.dataset.category?.split(' ').includes(filter);
        card.style.display = show ? '' : 'none';
        if (show) shown++;
      });
      if (empty) empty.style.display = shown ? 'none' : 'block';
    }));
  }

  document.querySelectorAll('[data-contact-intent]').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('[data-contact-intent]').forEach(b => b.classList.remove('is-active'));
      button.classList.add('is-active');
      const intent = button.dataset.contactIntent;
      const select = document.querySelector('[name="inquiryType"]');
      const badge = document.querySelector('[data-intent-badge]');
      if (select) select.value = intent;
      if (badge) badge.textContent = button.querySelector('strong')?.textContent || 'General inquiry';
      document.querySelector('#inquiry')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  const form = document.querySelector('[data-prototype-form]');
  form?.addEventListener('submit', e => {
    e.preventDefault();
    const note = form.querySelector('[data-form-note]');
    if (note) note.textContent = 'Prototype only — connect this form to your WordPress/CRM form handler during implementation.';
  });

  const tocLinks = [...document.querySelectorAll('[data-toc] a')];
  const sections = tocLinks.map(link => document.querySelector(link.getAttribute('href'))).filter(Boolean);
  if (tocLinks.length && sections.length) {
    const tocObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          tocLinks.forEach(link => link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id));
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });
    sections.forEach(section => tocObserver.observe(section));
  }

  document.querySelectorAll('[data-share]').forEach(button => button.addEventListener('click', async () => {
    const url = location.href;
    if (button.dataset.share === 'copy') {
      try { await navigator.clipboard.writeText(url); button.textContent = '✓'; setTimeout(() => button.textContent = '↗', 1200); } catch {}
    }
  }));
})();
