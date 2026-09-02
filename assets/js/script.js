const body = document.body;
const header = document.querySelector('[data-header]');
const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');

const updateHeader = () => {
  header?.classList.toggle('is-scrolled', window.scrollY > 18);
};
updateHeader();
window.addEventListener('scroll', updateHeader, { passive: true });

const mobileNavQuery = window.matchMedia('(max-width: 860px)');

const closeMobileSubmenus = () => {
  nav?.querySelectorAll('.menu-item-has-children.is-open').forEach(li => li.classList.remove('is-open'));
};

const closeNav = () => {
  body.classList.remove('menu-open');
  navToggle?.setAttribute('aria-expanded', 'false');
  closeMobileSubmenus();
};

navToggle?.addEventListener('click', () => {
  const open = body.classList.toggle('menu-open');
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

nav?.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', (e) => {
    if (e.defaultPrevented) return;
    closeNav();
  });
});

document.addEventListener('click', (e) => {
  if (!body.classList.contains('menu-open')) return;
  if (nav?.contains(e.target) || navToggle?.contains(e.target)) return;
  closeNav();
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && body.classList.contains('menu-open')) {
    closeNav();
  }
});

// Desktop dropdown hover-intent: a short close delay lets the cursor cross the
// gap between the trigger and the panel without the hover chain breaking,
// while still closing promptly once the pointer genuinely leaves both.
const DROPDOWN_CLOSE_DELAY = 250;
nav?.querySelectorAll(':scope > ul > li.menu-item-has-children').forEach(li => {
  let closeTimer = null;
  const open = () => {
    if (mobileNavQuery.matches) return;
    clearTimeout(closeTimer);
    li.classList.add('hover-open');
  };
  const scheduleClose = () => {
    if (mobileNavQuery.matches) return;
    clearTimeout(closeTimer);
    closeTimer = setTimeout(() => li.classList.remove('hover-open'), DROPDOWN_CLOSE_DELAY);
  };
  li.addEventListener('mouseenter', open);
  li.addEventListener('mouseleave', scheduleClose);
});

const revealItems = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      obs.unobserve(entry.target);
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -6% 0px' });
  revealItems.forEach((item, index) => {
    item.style.transitionDelay = `${Math.min(index % 3, 2) * 45}ms`;
    observer.observe(item);
  });
} else {
  revealItems.forEach(item => item.classList.add('is-visible'));
}

// Generic tab system used by the homepage specialties, product workflow,
// and the product software showcase. A key can have more than one
// trigger pointing at it (e.g. a thumbnail and a dot both for the same
// slide) -- matched by data-tab value, not by identity, so every
// trigger sharing that value stays in sync regardless of which one was
// actually clicked.
document.querySelectorAll('[data-tabs]').forEach(tabRoot => {
  const tabs = [...tabRoot.querySelectorAll('[data-tab]')];
  const panels = [...tabRoot.querySelectorAll('[data-panel]')];
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const key = tab.dataset.tab;
      tabs.forEach(item => {
        const active = item.dataset.tab === key;
        item.classList.toggle('is-active', active);
        item.setAttribute('aria-selected', String(active));
      });
      panels.forEach(panel => {
        const active = panel.dataset.panel === key;
        panel.hidden = !active;
        panel.classList.remove('is-fading-in');
        if (active) {
          void panel.offsetWidth;
          panel.classList.add('is-fading-in');
        }
      });
    });
  });
});

// Static prototype form feedback.
document.querySelectorAll('[data-demo-form]').forEach(form => {
  form.addEventListener('submit', event => {
    event.preventDefault();
    const note = form.querySelector('[data-form-note]');
    if (note) note.textContent = 'Prototype only — connect this form to the Alrenas WordPress form endpoint during integration.';
  });
});
