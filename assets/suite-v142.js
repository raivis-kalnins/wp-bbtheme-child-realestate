/* Real Estate 3.8.11.42 — deterministic hero carousel + polish runtime. */
(function (W, D) {
  'use strict';
  function q(sel, root) { return (root || D).querySelector(sel); }
  function qa(sel, root) { return Array.prototype.slice.call((root || D).querySelectorAll(sel)); }

  function initHero(hero) {
    if (!hero || hero.dataset.wpbbRe142Bound) return;
    var slides = qa('[data-wpbb-re142-slide]', hero);
    var bullets = qa('[data-wpbb-re142-go]', hero);
    if (!slides.length) return;
    hero.dataset.wpbbRe142Bound = '1';
    var index = 0;
    var timer = 0;
    var reduced = W.matchMedia && W.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function paint(next, focusBullet) {
      next = ((next % slides.length) + slides.length) % slides.length;
      index = next;
      slides.forEach(function (slide, i) {
        var active = i === index;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', active ? 'false' : 'true');
      });
      bullets.forEach(function (bullet, i) {
        var active = i === index;
        bullet.classList.toggle('is-active', active);
        if (active) bullet.setAttribute('aria-current', 'true');
        else bullet.removeAttribute('aria-current');
      });
      if (focusBullet && bullets[index]) bullets[index].focus({ preventScroll: true });
    }

    function stop() {
      if (timer) W.clearInterval(timer);
      timer = 0;
    }
    function start() {
      stop();
      if (reduced || slides.length < 2) return;
      timer = W.setInterval(function () { paint(index + 1, false); }, 8500);
    }

    bullets.forEach(function (bullet) {
      bullet.addEventListener('click', function () {
        var next = parseInt(bullet.getAttribute('data-wpbb-re142-go'), 10);
        paint(isNaN(next) ? 0 : next, false);
        start();
      });
    });
    hero.addEventListener('mouseenter', stop, { passive: true });
    hero.addEventListener('mouseleave', start, { passive: true });
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', function (event) {
      if (!hero.contains(event.relatedTarget)) start();
    });
    hero.addEventListener('keydown', function (event) {
      if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
      event.preventDefault();
      paint(index + (event.key === 'ArrowRight' ? 1 : -1), true);
      start();
    });

    paint(0, false);
    start();
  }

  function run() {
    if (D.body) D.body.classList.add('wpbb-v142', 'wpbb-v142-theme-realestate');
    qa('[data-wpbb-re142-hero]').forEach(initHero);
  }

  if (D.readyState === 'loading') D.addEventListener('DOMContentLoaded', run, { once: true });
  else run();
  W.addEventListener('load', run);
}(window, document));
