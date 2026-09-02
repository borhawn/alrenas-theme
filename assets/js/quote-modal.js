const quoteModal = document.querySelector('[data-quote-modal]');

if (quoteModal) {
  const card = quoteModal.querySelector('.quote-modal-card');
  const form = quoteModal.querySelector('[data-quote-form]');
  const productIdField = quoteModal.querySelector('[data-quote-product-id]');
  const productCard = quoteModal.querySelector('[data-quote-modal-product]');
  const productImage = quoteModal.querySelector('[data-quote-modal-image]');
  const productNameEl = quoteModal.querySelector('[data-quote-modal-product-name]');
  const quantityField = form?.querySelector('input[name="quantity"]');
  const formPanel = quoteModal.querySelector('[data-quote-modal-panel="form"]');
  const successPanel = quoteModal.querySelector('[data-quote-modal-panel="success"]');
  const successMessage = quoteModal.querySelector('[data-quote-success-message]');
  const successProductCard = quoteModal.querySelector('[data-quote-modal-success-product]');
  const successProductImage = quoteModal.querySelector('[data-quote-modal-success-image]');
  const successProductName = quoteModal.querySelector('[data-quote-modal-success-name]');
  const errorEl = quoteModal.querySelector('[data-quote-form-error]');
  const submitButton = quoteModal.querySelector('[data-quote-submit]');
  const submitLabel = quoteModal.querySelector('[data-quote-submit-label]');
  const submitLabelDefault = submitLabel ? submitLabel.textContent : '';

  let lastFocused = null;
  let currentProduct = { name: '', image: '' };

  const showError = message => {
    if (!errorEl) return;
    errorEl.textContent = message;
    errorEl.hidden = false;
  };

  const clearError = () => {
    if (!errorEl) return;
    errorEl.hidden = true;
    errorEl.textContent = '';
  };

  const setSubmitting = submitting => {
    if (!submitButton) return;
    submitButton.disabled = submitting;
    if (submitLabel) {
      submitLabel.textContent = submitting ? 'Sending...' : submitLabelDefault;
    }
  };

  const openModal = trigger => {
    lastFocused = document.activeElement;

    if (formPanel) formPanel.hidden = false;
    if (successPanel) successPanel.hidden = true;
    clearError();
    form?.reset();

    if (productIdField) {
      productIdField.value = trigger?.dataset.productId || '';
    }
    if (quantityField) {
      quantityField.value = '1';
    }

    const name = trigger?.dataset.productName || '';
    const image = trigger?.dataset.productImage || '';
    currentProduct = { name, image };

    if (productNameEl) productNameEl.textContent = name;
    if (productImage) {
      productImage.src = image;
      productImage.alt = name;
    }
    if (productCard) {
      productCard.hidden = !name;
    }

    quoteModal.classList.add('is-open');
    document.body.style.overflow = 'hidden';

    window.setTimeout(() => {
      form?.querySelector('input[name="name"]')?.focus();
    }, 200);
  };

  const closeModal = () => {
    quoteModal.classList.remove('is-open');
    document.body.style.overflow = '';
    lastFocused?.focus();
  };

  document.querySelectorAll('[data-quote-trigger]').forEach(trigger => {
    trigger.addEventListener('click', event => {
      event.preventDefault();
      openModal(trigger);
    });
  });

  // "Request a quote" links elsewhere on the site (e.g. the /products
  // archive) point here as `<product-url>#get-a-quote` so they land on
  // the product page with the modal already open, instead of just
  // dropping the visitor on the page to go find the button themselves.
  if (window.location.hash === '#get-a-quote') {
    const defaultTrigger = document.querySelector('[data-quote-trigger]');
    if (defaultTrigger) {
      openModal(defaultTrigger);
      history.replaceState(null, '', window.location.pathname + window.location.search);
    }
  }

  quoteModal.querySelectorAll('[data-quote-modal-close]').forEach(closer => {
    closer.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && quoteModal.classList.contains('is-open')) {
      closeModal();
    }
  });

  form?.addEventListener('submit', event => {
    event.preventDefault();

    if (!window.alrenasQuote) {
      showError('Something went wrong. Please try again later.');
      return;
    }

    clearError();
    setSubmitting(true);

    const formData = new FormData(form);
    formData.append('action', 'alrenas_submit_quote_request');
    formData.append('nonce', window.alrenasQuote.nonce);

    fetch(window.alrenasQuote.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        setSubmitting(false);

        if (data && data.success) {
          if (successMessage && data.data && data.data.message) {
            successMessage.textContent = data.data.message;
          }

          const qty = quantityField ? parseInt(quantityField.value, 10) || 1 : 1;
          const label = qty > 1 && currentProduct.name ? `${qty} × ${currentProduct.name}` : currentProduct.name;

          if (successProductName) successProductName.textContent = label;
          if (successProductImage) {
            successProductImage.src = currentProduct.image;
            successProductImage.alt = currentProduct.name;
          }
          if (successProductCard) successProductCard.hidden = !currentProduct.name;

          if (formPanel) formPanel.hidden = true;
          if (successPanel) successPanel.hidden = false;
        } else {
          showError((data && data.data && data.data.message) || 'Something went wrong. Please try again.');
        }
      })
      .catch(() => {
        setSubmitting(false);
        showError('Something went wrong. Please check your connection and try again.');
      });
  });
}
