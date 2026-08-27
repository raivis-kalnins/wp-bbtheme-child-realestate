(function () {
  'use strict';

  var savedKey = 'wpbbSavedProperties';

  function readSaved() {
    try {
      return JSON.parse(localStorage.getItem(savedKey) || '[]');
    } catch (error) {
      return [];
    }
  }

  function syncSavedButtons(scope) {
    var saved = readSaved();
    scope.querySelectorAll('[data-save-property]').forEach(function (button) {
      var active = saved.indexOf(button.dataset.saveProperty) !== -1;
      button.setAttribute('aria-pressed', active ? 'true' : 'false');
      button.textContent = active ? '♥' : '♡';
    });
  }

  document.querySelectorAll('[data-property-search]').forEach(function (form) {
    var finder = form.closest('.wp-theme-property-finder');
    var results = finder && finder.querySelector('[data-property-results]');
    var controller;
    var view = 'grid';

    if (!results) return;

    function updateUrl(data) {
      if (!window.history || !window.history.replaceState) return;
      var params = new URLSearchParams();
      data.forEach(function (value, key) {
        if (value && ['action', 'nonce', 'limit'].indexOf(key) === -1) params.set(key, value);
      });
      var url = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + '#properties';
      window.history.replaceState({}, '', url);
    }

    function runSearch() {
      var data = new FormData(form);
      data.set('view', view);
      if (controller) controller.abort();
      controller = new AbortController();
      results.classList.add('is-loading');
      results.setAttribute('aria-busy', 'true');

      fetch(window.wpbbPropertySearch.ajaxUrl, {
        method: 'POST', body: data, credentials: 'same-origin', signal: controller.signal
      }).then(function (response) {
        if (!response.ok) throw new Error('Search failed');
        return response.json();
      }).then(function (payload) {
        if (!payload.success || !payload.data || !payload.data.html) throw new Error('Invalid response');
        results.innerHTML = payload.data.html;
        syncSavedButtons(results);
        updateUrl(data);
      }).catch(function (error) {
        if (error.name !== 'AbortError') {
          results.innerHTML = '<p class="wp-theme-property-error">' + window.wpbbPropertySearch.error + '</p>';
        }
      }).finally(function () {
        results.classList.remove('is-loading');
        results.removeAttribute('aria-busy');
      });
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      runSearch();
    });

    form.querySelectorAll('[data-listing-type]').forEach(function (button) {
      button.addEventListener('click', function () {
        form.elements.listing_type.value = button.dataset.listingType;
        form.querySelectorAll('[data-listing-type]').forEach(function (item) {
          item.setAttribute('aria-pressed', item === button ? 'true' : 'false');
        });
        form.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(function (input) {
          input.value = '';
          input.step = button.dataset.listingType === 'rent' ? '100' : '10000';
        });
        runSearch();
      });
    });

    form.querySelectorAll('select').forEach(function (select) {
      select.addEventListener('change', runSearch);
    });

    var clear = form.querySelector('[data-property-clear]');
    if (clear) {
      clear.addEventListener('click', function () {
        window.setTimeout(function () {
          form.elements.listing_type.value = 'sale';
          form.querySelectorAll('[data-listing-type]').forEach(function (button) {
            button.setAttribute('aria-pressed', button.dataset.listingType === 'sale' ? 'true' : 'false');
          });
          runSearch();
        }, 0);
      });
    }

    var saveSearch = form.querySelector('[data-save-search]');
    if (saveSearch) {
      saveSearch.addEventListener('click', function () {
        try {
          localStorage.setItem('wpbbSavedPropertySearch', new URLSearchParams(new FormData(form)).toString());
          saveSearch.textContent = 'Search saved';
          window.setTimeout(function () { saveSearch.textContent = 'Save this search'; }, 1800);
        } catch (error) {}
      });
    }

    results.addEventListener('click', function (event) {
      var viewButton = event.target.closest('[data-property-view]');
      if (viewButton) {
        view = viewButton.dataset.propertyView;
        var grid = results.querySelector('.wp-theme-property-grid');
        if (grid) grid.className = 'wp-theme-property-grid is-' + view;
        results.querySelectorAll('[data-property-view]').forEach(function (button) {
          button.setAttribute('aria-pressed', button === viewButton ? 'true' : 'false');
        });
        return;
      }

      var saveButton = event.target.closest('[data-save-property]');
      if (!saveButton) return;
      var saved = readSaved();
      var id = saveButton.dataset.saveProperty;
      var index = saved.indexOf(id);
      if (index === -1) saved.push(id); else saved.splice(index, 1);
      try { localStorage.setItem(savedKey, JSON.stringify(saved)); } catch (error) {}
      syncSavedButtons(results);
    });

    syncSavedButtons(results);
  });
}());
