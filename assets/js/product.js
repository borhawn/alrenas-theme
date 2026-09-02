const productGallery = document.querySelector('[data-product-gallery]');
if (productGallery) {
  const mainButton = productGallery.querySelector('[data-gallery-main]');
  const mainImage = mainButton?.querySelector('img');
  const thumbs = [...productGallery.querySelectorAll('[data-gallery-src]')];
  const lightbox = document.querySelector('[data-lightbox]');
  const lightboxImage = document.querySelector('[data-lightbox-image]');
  const closeLightbox = document.querySelector('[data-lightbox-close]');
  const prevButton = document.querySelector('[data-lightbox-prev]');
  const nextButton = document.querySelector('[data-lightbox-next]');

  let activeIndex = Math.max(0, thumbs.findIndex(thumb => thumb.classList.contains('is-active')));

  // Quick fade-out/fade-in instead of an instant src swap when changing
  // images (by thumbnail, arrow key, or lightbox nav) -- fast enough to
  // stay snappy, smooth enough to feel deliberate rather than a jump cut.
  const FADE_MS = 130;
  let mainFadeTimer = null;
  let lightboxFadeTimer = null;

  const crossfade = imgEl => (src, alt) => {
    if (!imgEl) return null;
    imgEl.style.opacity = '0';
    return window.setTimeout(() => {
      imgEl.src = src;
      imgEl.alt = alt;
      imgEl.style.opacity = '1';
    }, FADE_MS);
  };
  const fadeMainImage = crossfade(mainImage);
  const fadeLightboxImage = crossfade(lightboxImage);

  const setActive = (index, { scroll } = {}) => {
    if (!thumbs.length) return;
    activeIndex = (index + thumbs.length) % thumbs.length;
    const thumb = thumbs[activeIndex];
    const src = thumb.dataset.gallerySrc;
    const alt = thumb.dataset.galleryAlt || '';

    thumbs.forEach(item => item.classList.remove('is-active'));
    thumb.classList.add('is-active');

    if (mainImage) {
      window.clearTimeout(mainFadeTimer);
      mainFadeTimer = fadeMainImage(src, alt);
    }
    if (lightbox && !lightbox.hidden && lightboxImage) {
      window.clearTimeout(lightboxFadeTimer);
      lightboxFadeTimer = fadeLightboxImage(src, alt);
    }
    if (scroll) {
      thumb.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
    }
  };

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener('click', () => setActive(index));
  });

  const openLightbox = () => {
    if (!lightbox || !lightboxImage || !mainImage) return;
    lightboxImage.src = mainImage.src;
    lightboxImage.alt = mainImage.alt;
    lightbox.hidden = false;
    document.body.style.overflow = 'hidden';
  };

  const close = () => {
    if (!lightbox) return;
    lightbox.hidden = true;
    document.body.style.overflow = '';
  };

  mainButton?.addEventListener('click', openLightbox);
  closeLightbox?.addEventListener('click', close);
  lightbox?.addEventListener('click', event => {
    if (event.target === lightbox) close();
  });
  prevButton?.addEventListener('click', () => setActive(activeIndex - 1, { scroll: true }));
  nextButton?.addEventListener('click', () => setActive(activeIndex + 1, { scroll: true }));

  // Left/right arrow keys step through the gallery whether or not the
  // lightbox is open, as long as the visitor isn't typing somewhere else.
  document.addEventListener('keydown', event => {
    const active = document.activeElement;
    const isTyping = active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable);

    if (event.key === 'Escape' && lightbox && !lightbox.hidden) {
      close();
      return;
    }
    if (isTyping) return;

    if (event.key === 'ArrowLeft') {
      setActive(activeIndex - 1, { scroll: true });
    } else if (event.key === 'ArrowRight') {
      setActive(activeIndex + 1, { scroll: true });
    }
  });
}

const accordionRoot = document.querySelector('[data-accordions]');
if (accordionRoot) {
  accordionRoot.querySelectorAll('.detail-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.detail-item');
      const content = item?.querySelector('.detail-content');
      if (!item || !content) return;
      const open = item.classList.contains('is-open');
      // No [hidden] toggle here on purpose -- hidden forces display:none,
      // which can't be transitioned. Visibility is driven entirely by the
      // is-open class (max-height/opacity in product.css) so it animates.
      item.classList.toggle('is-open', !open);
      trigger.setAttribute('aria-expanded', String(!open));
      content.setAttribute('aria-hidden', String(open));
    });
  });
}

const productForm = document.querySelector('[data-product-form]');
if (productForm) {
  const typeButtons = [...productForm.querySelectorAll('[data-inquiry-type]')];
  const typeValue = productForm.querySelector('[data-inquiry-value]');
  const submitLabel = productForm.querySelector('[data-submit-label]');

  const setInquiryType = type => {
    typeButtons.forEach(button => button.classList.toggle('is-active', button.dataset.inquiryType === type));
    if (typeValue) typeValue.value = type;
    if (submitLabel) submitLabel.textContent = type === 'demo' ? 'Request a Demo' : 'Request a Quote';
  };

  typeButtons.forEach(button => button.addEventListener('click', () => setInquiryType(button.dataset.inquiryType)));

  document.querySelectorAll('[data-inquiry-intent]').forEach(link => {
    link.addEventListener('click', () => setInquiryType(link.dataset.inquiryIntent));
  });
}

document.querySelectorAll('[data-faq]').forEach(faqRoot => {
  faqRoot.querySelectorAll('.faq-item button').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.faq-item');
      const answer = item?.querySelector('.faq-answer');
      if (!item || !answer) return;
      const open = item.classList.contains('is-open');
      // Same reasoning as the details accordion above: no [hidden] toggle,
      // the is-open class alone drives the animated expand/collapse.
      item.classList.toggle('is-open', !open);
      trigger.setAttribute('aria-expanded', String(!open));
      answer.setAttribute('aria-hidden', String(open));
    });
  });
});

// Software showcase pinned-scroll (desktop only -- see the 900px
// breakpoint in product.css for the plain stacked fallback below that,
// which needs none of this). GSAP ScrollTrigger locks the section once
// it reaches the center of the viewport, then steps the title/
// description/image through each software screen as the visitor keeps
// scrolling, starting from the section's own intro copy.
const softwarePin = document.querySelector('[data-software-pin]');
const softwareStepsScript = document.querySelector('[data-software-steps]');

if (softwarePin && softwareStepsScript && window.gsap && window.ScrollTrigger) {
  gsap.registerPlugin(ScrollTrigger);

  const titleEl = softwarePin.querySelector('[data-software-title]');
  const descriptionEl = softwarePin.querySelector('[data-software-description]');
  const imageEl = softwarePin.querySelector('[data-software-image]');

  let steps = [];
  try {
    steps = JSON.parse(softwareStepsScript.textContent);
  } catch (error) {
    steps = [];
  }

  if (titleEl && descriptionEl && imageEl && steps.length) {
    const introState = {
      title: titleEl.textContent,
      description: descriptionEl.textContent,
      image: imageEl.getAttribute('src'),
      alt: imageEl.getAttribute('alt'),
    };

    const states = [introState].concat(
      steps.map(step => ({
        title: step.title || '',
        description: step.description || '',
        image: step.image || introState.image,
        alt: step.alt || step.title || '',
      }))
    );

    let activeIndex = 0;

    const applyState = index => {
      const state = states[index];
      if (!state) return;

      gsap
        .timeline()
        .to([titleEl, descriptionEl, imageEl], { opacity: 0, duration: 0.18, ease: 'power1.out' })
        .call(() => {
          titleEl.textContent = state.title;
          descriptionEl.textContent = state.description;
          if (state.image) {
            imageEl.setAttribute('src', state.image);
            imageEl.setAttribute('alt', state.alt);
          }
        })
        .to([titleEl, descriptionEl, imageEl], { opacity: 1, duration: 0.28, ease: 'power1.in' });
    };

    ScrollTrigger.matchMedia({
      '(min-width: 901px)': function () {
        const distancePerStep = window.innerHeight * 0.9;

        const trigger = ScrollTrigger.create({
          trigger: softwarePin,
          start: 'center center',
          end: '+=' + Math.max(1, states.length - 1) * distancePerStep,
          pin: true,
          anticipatePin: 1,
          onUpdate(self) {
            const index = Math.min(states.length - 1, Math.floor(self.progress * states.length));
            if (index !== activeIndex) {
              activeIndex = index;
              applyState(index);
            }
          },
          onLeaveBack() {
            if (activeIndex !== 0) {
              activeIndex = 0;
              applyState(0);
            }
          },
        });

        // Runs when this matchMedia query stops matching (resized below
        // 901px) -- ScrollTrigger's own cleanup for that case.
        return () => {
          trigger.kill();
          activeIndex = 0;
          titleEl.textContent = introState.title;
          descriptionEl.textContent = introState.description;
          imageEl.setAttribute('src', introState.image);
          imageEl.setAttribute('alt', introState.alt);
          gsap.set([titleEl, descriptionEl, imageEl], { clearProps: 'opacity' });
        };
      },
    });
  }
}
