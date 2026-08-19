(() => {
  const slider = document.querySelector('[data-hero-slider]');
  if (!slider) return;

  const photos = [...slider.querySelectorAll('.hero-photo[data-hero-slide]')];
  const cards = [...slider.querySelectorAll('.hero-device-card[data-hero-slide]')];

  if (photos.length < 2) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const intervalMs = 5000;
  let index = 0;

  const cardFor = (slideIndex) => cards.find((card) => Number(card.dataset.heroSlide) === slideIndex);

  setInterval(() => {
    const nextIndex = (index + 1) % photos.length;

    photos[index].classList.remove('is-active');
    photos[nextIndex].classList.add('is-active');

    cardFor(index)?.classList.remove('is-active');
    cardFor(nextIndex)?.classList.add('is-active');

    index = nextIndex;
  }, intervalMs);
})();
