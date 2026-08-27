const quoteModal = document.querySelector('[data-quote-modal]');

if (quoteModal) {
  const card = quoteModal.querySelector('.quote-modal-card');
  const form = quoteModal.querySelector('[data-quote-form]');
  const productIdField = quoteModal.querySelector('[data-quote-product-id]');
  const productLabel = quoteModal.querySelector('[data-quote-modal-product]');
  const formPanel = quoteModal.querySelector('[data-quote-modal-panel="form"]');
  const successPanel = quoteModal.querySelector('[data-quote-modal-panel="success"]');
  const successMessage = quoteModal.querySelector('[data-quote-success-message]');
  const errorEl = quoteModal.querySelector('[data-quote-form-error]');
  const submitButton = quoteModal.querySelector('[data-quote-submit]');
  const submitLabel = quoteModal.querySelector('[data-quote-submit-label]');
  const submitLabelDefault = submitLabel ? submitLabel.textContent : '';

  let lastFocused = null;

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

    if (productIdField) {
      productIdField.value = trigger?.dataset.productId || '';
    }
    if (productLabel) {
      const name = trigger?.dataset.productName || '';
      productLabel.textContent = name ? `For: ${name}` : '';
      productLabel.hidden = !name;
    }

    if (formPanel) formPanel.hidden = false;
    if (successPanel) successPanel.hidden = true;
    clearError();
    form?.reset();
    if (productIdField) productIdField.value = trigger?.dataset.productId || '';

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
