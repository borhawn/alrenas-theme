const productGallery = document.querySelector('[data-product-gallery]');
if (productGallery) {
  const mainButton = productGallery.querySelector('[data-gallery-main]');
  const mainImage = mainButton?.querySelector('img');
  const thumbs = [...productGallery.querySelectorAll('[data-gallery-src]')];
  const lightbox = document.querySelector('[data-lightbox]');
  const lightboxImage = document.querySelector('[data-lightbox-image]');
  const closeLightbox = document.querySelector('[data-lightbox-close]');

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      thumbs.forEach(item => item.classList.remove('is-active'));
      thumb.classList.add('is-active');
      if (mainImage) {
        mainImage.src = thumb.dataset.gallerySrc;
        mainImage.alt = thumb.dataset.galleryAlt || 'Al Balance Dyn product image';
      }
    });
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
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && lightbox && !lightbox.hidden) close();
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
      item.classList.toggle('is-open', !open);
      trigger.setAttribute('aria-expanded', String(!open));
      content.hidden = open;
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
      item.classList.toggle('is-open', !open);
      trigger.setAttribute('aria-expanded', String(!open));
      answer.hidden = open;
    });
  });
});
