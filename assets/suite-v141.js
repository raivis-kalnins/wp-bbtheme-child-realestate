/* Real Estate 3.8.11.41 — image hydration + Events-style mega-menu anchoring. */
(function (W, D) {
  'use strict';
  function q(sel, root) { return (root || D).querySelector(sel); }
  function qa(sel, root) { return Array.prototype.slice.call((root || D).querySelectorAll(sel)); }

  function hydrateImage(img, eager) {
    if (!img) return;
    var lazy = img.getAttribute('data-src') || img.getAttribute('data-lazy-src');
    var current = img.getAttribute('src') || '';
    if (lazy && (!current || /placeholder|transparent|data:image/i.test(current))) img.setAttribute('src', lazy);
    var lazySet = img.getAttribute('data-srcset') || img.getAttribute('data-lazy-srcset');
    if (lazySet && !img.getAttribute('srcset')) img.setAttribute('srcset', lazySet);
    img.setAttribute('decoding', 'async');
    if (eager) img.setAttribute('loading', 'eager');
  }

  function prepareImages(root) {
    root = root || D;
    qa('.wpbb-re141-hero__media img,.wp-theme-property-card__image img', root).forEach(function (img, index) {
      hydrateImage(img, true);
      if (index === 0) img.setAttribute('fetchpriority', 'high');
    });
    qa('.wpbb-re141-split__media img,.wpbb-re141-gallery img,.wpbb-v139-blog-card__media img', root).forEach(function (img) {
      hydrateImage(img, false);
    });
  }

  /* Same trigger-anchored menu model used by the working Events recovery. */
  function menuTrigger(menu) {
    var li = menu && menu.parentElement;
    if (!li) return null;
    try { return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link') || li; }
    catch (e) { return li.querySelector('a,button') || li; }
  }
  function desktop() { return W.matchMedia('(min-width: 992px)').matches; }
  function positionMega(menu) {
    if (!menu) return;
    if (!desktop()) { menu.style.removeProperty('top'); return; }
    var trigger = menuTrigger(menu);
    if (!trigger || !trigger.getBoundingClientRect) return;
    var r = trigger.getBoundingClientRect();
    var li = menu.parentElement;
    var lr = li && li.getBoundingClientRect ? li.getBoundingClientRect() : r;
    var bottom = Math.ceil(Math.max(r.bottom, lr.bottom) - 6);
    if (bottom > 0) {
      menu.style.setProperty('top', bottom + 'px', 'important');
      D.documentElement.style.setProperty('--wpbb-re141-mega-top', bottom + 'px');
    }
  }
  function positionMegas() { qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega); }
  function bindMegas() {
    qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function (menu) {
      if (menu.dataset.wpbbRe141Bound) return;
      menu.dataset.wpbbRe141Bound = '1';
      var li = menu.parentElement;
      if (li) {
        li.addEventListener('pointerenter', function () { positionMega(menu); }, { passive: true });
        li.addEventListener('focusin', function () { positionMega(menu); });
      }
      menu.addEventListener('pointerenter', function () { positionMega(menu); }, { passive: true });
    });
    positionMegas();
  }

  function run() {
    if (D.body) D.body.classList.add('wpbb-v141', 'wpbb-v141-theme-realestate', 'wpbb-v139-theme-realestate');
    prepareImages(D);
    bindMegas();
  }

  var timer = 0;
  function schedule() { W.clearTimeout(timer); timer = W.setTimeout(run, 40); }
  if (D.readyState === 'loading') D.addEventListener('DOMContentLoaded', schedule, { once: true });
  else schedule();
  W.addEventListener('load', function () { run(); W.setTimeout(run, 350); });
  W.addEventListener('resize', function () { W.clearTimeout(timer); timer = W.setTimeout(run, 100); }, { passive: true });
  W.addEventListener('scroll', function () { W.requestAnimationFrame(positionMegas); }, { passive: true });

  if (W.MutationObserver) {
    var observer = new MutationObserver(function (mutations) {
      if (!mutations.some(function (m) { return m.addedNodes && m.addedNodes.length; })) return;
      schedule();
    });
    observer.observe(D.documentElement, { childList: true, subtree: true });
    W.setTimeout(function () { observer.disconnect(); }, 7000);
  }
}(window, document));
