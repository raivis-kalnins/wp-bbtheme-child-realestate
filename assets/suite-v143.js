/* Real Estate 3.8.11.43 — Travel-style horizontal hero slider. */
(function (W, D) {
  'use strict';
  function q(sel, root) { try { return (root || D).querySelector(sel); } catch (e) { return null; } }
  function qa(sel, root) { try { return Array.prototype.slice.call((root || D).querySelectorAll(sel)); } catch (e) { return []; } }

  function initHero(hero) {
    if (!hero || hero.dataset.wpbbRe143Bound === '1') return;
    var viewport = q('.wpbb-re142-hero__viewport', hero);
    var slides = qa('[data-wpbb-re142-slide]', hero);
    var bullets = qa('[data-wpbb-re142-go]', hero);
    if (!viewport || slides.length < 1) return;

    hero.dataset.wpbbRe143Bound = '1';
    hero.setAttribute('aria-roledescription', 'carousel');

    var track = D.createElement('div');
    track.className = 'wpbb-re143-hero__track';
    slides.forEach(function (slide) {
      slide.setAttribute('data-wpbb-re143-ready', '1');
      track.appendChild(slide);
    });
    viewport.appendChild(track);

    var index = 0;
    var timer = 0;
    var startX = null;
    var reduced = !!(W.matchMedia && W.matchMedia('(prefers-reduced-motion: reduce)').matches);

    function paint(next, focusBullet) {
      next = ((next % slides.length) + slides.length) % slides.length;
      index = next;
      track.style.transform = 'translate3d(' + (-100 * index) + '%,0,0)';
      slides.forEach(function (slide, i) {
        var active = i === index;
        slide.classList.toggle('is-active', active);
        slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        if ('inert' in slide) slide.inert = !active;
      });
      bullets.forEach(function (bullet, i) {
        var active = i === index;
        bullet.classList.toggle('is-active', active);
        if (active) bullet.setAttribute('aria-current', 'true');
        else bullet.removeAttribute('aria-current');
      });
      if (focusBullet && bullets[index] && typeof bullets[index].focus === 'function') {
        try { bullets[index].focus({ preventScroll: true }); } catch (e) { bullets[index].focus(); }
      }
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

    viewport.addEventListener('pointerdown', function (event) {
      if (event.pointerType === 'mouse' && event.button !== 0) return;
      startX = event.clientX;
      stop();
    }, { passive: true });
    viewport.addEventListener('pointerup', function (event) {
      if (startX === null) return;
      var delta = event.clientX - startX;
      startX = null;
      if (Math.abs(delta) >= 42) paint(index + (delta < 0 ? 1 : -1), false);
      start();
    }, { passive: true });
    viewport.addEventListener('pointercancel', function () { startX = null; start(); }, { passive: true });

    paint(0, false);
    start();
  }

  function run() {
    if (D.body) D.body.classList.add('wpbb-v143', 'wpbb-v143-theme-realestate');
    qa('[data-wpbb-re142-hero]').forEach(initHero);
  }

  if (D.readyState === 'loading') D.addEventListener('DOMContentLoaded', run, { once: true });
  else run();
  W.addEventListener('load', run);
}(window, document));
