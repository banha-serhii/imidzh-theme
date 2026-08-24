/**
 * Header search: expand / collapse on desktop.
 * Breakpoint must match assets/css/search.css and mega-menu (1100px).
 */
(function () {
  'use strict';

  var cfg = window.imidzhSearch || {};
  var desktopMin = parseInt(cfg.desktopMin, 10) || 1100;
  var MQ_DESKTOP = window.matchMedia('(min-width: ' + desktopMin + 'px)');
  var i18n = cfg.i18n || {};

  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  function initHeaderSearch() {
    var root = document.querySelector('.site-search--header');
    if (!root) return;

    var toggle = root.querySelector('.site-search__toggle');
    var form = root.querySelector('.search-form');
    var field = root.querySelector('input[name="s"]');
    var label = toggle ? toggle.querySelector('.screen-reader-text') : null;

    if (!toggle || !form || !field) return;

    function isDesktop() {
      return MQ_DESKTOP.matches;
    }

    function isOpen() {
      return root.classList.contains('is-open');
    }

    function setOpen(open, restoreFocus) {
      root.classList.toggle('is-open', open);
      form.hidden = false;
      if (open) {
        form.removeAttribute('inert');
      } else {
        form.setAttribute('inert', '');
      }
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (label) {
        label.textContent = open
          ? (i18n.close || 'Закрити пошук')
          : (i18n.open || 'Відкрити пошук');
      }
      if (open) {
        window.requestAnimationFrame(function () {
          field.focus();
        });
      } else if (restoreFocus) {
        toggle.focus();
      }
    }

    form.hidden = false;
    form.setAttribute('inert', '');

    function close(restoreFocus) {
      if (isOpen()) {
        setOpen(false, restoreFocus);
      }
    }

    toggle.addEventListener('click', function () {
      if (!isDesktop()) return;
      var willOpen = !isOpen();
      setOpen(willOpen, false);
      if (willOpen) {
        window.setTimeout(function () {
          field.focus();
        }, 0);
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape' || !isOpen()) return;
      close(true);
    });

    document.addEventListener('click', function (e) {
      if (!isOpen()) return;
      if (root.contains(e.target)) return;
      close(false);
    });

    MQ_DESKTOP.addEventListener('change', function (ev) {
      if (!ev.matches) {
        close(false);
      }
    });

    var menuToggle = document.getElementById('menu-toggle');
    if (menuToggle && typeof MutationObserver !== 'undefined') {
      var observer = new MutationObserver(function () {
        if (menuToggle.getAttribute('aria-expanded') === 'true') {
          close(false);
        }
      });
      observer.observe(menuToggle, { attributes: true, attributeFilter: ['aria-expanded'] });
    }
  }

  ready(initHeaderSearch);
})();
