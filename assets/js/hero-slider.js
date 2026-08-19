(() => {
  const slider = document.querySelector('[data-hero-slider]');
  if (!slider) return;

  const photos = [...slider.querySelectorAll('.hero-photo[data-hero-slide]')];
  if (photos.length < 2) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const intervalMs = 5000;
  const fadeMs = 1100;
  let index = 0;

  setInterval(() => {
    const nextIndex = (index + 1) % photos.length;
    photos[index].classList.remove('is-active');
    photos[nextIndex].classList.add('is-active');
    index = nextIndex;

    slider.classList.add('is-transitioning');
    setTimeout(() => slider.classList.remove('is-transitioning'), fadeMs);
  }, intervalMs);
})();
