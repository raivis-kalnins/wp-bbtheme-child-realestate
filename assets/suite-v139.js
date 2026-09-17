(function () {
  'use strict';

  function hydrateImage(img) {
    if (!img) return;
    var lazy = img.getAttribute('data-src') || img.getAttribute('data-lazy-src');
    if (lazy && (!img.getAttribute('src') || /placeholder|transparent|data:image/i.test(img.getAttribute('src')))) {
      img.setAttribute('src', lazy);
    }
    var lazySet = img.getAttribute('data-srcset') || img.getAttribute('data-lazy-srcset');
    if (lazySet && !img.getAttribute('srcset')) img.setAttribute('srcset', lazySet);
    img.setAttribute('decoding', 'async');
  }

  function prepare(root) {
    root = root || document;
    root.querySelectorAll('.wpbb-v139-home-hero img, .wp-theme-property-card__image img').forEach(function (img, index) {
      hydrateImage(img);
      img.setAttribute('loading', 'eager');
      if (index === 0) img.setAttribute('fetchpriority', 'high');
    });
    root.querySelectorAll('.wpbb-v139-gallery-image img, .wpbb-v139-blog-card__media img, .wpbb-v139-feature-image img').forEach(hydrateImage);
  }

  function init() {
    document.documentElement.classList.add('wpbb-v139-ready');
    prepare(document);

    var results = document.querySelector('.wp-theme-property-results');
    if (results && 'MutationObserver' in window) {
      new MutationObserver(function () { prepare(results); }).observe(results, { childList: true, subtree: true });
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true });
  else init();
}());
