/**
 * Ліцей «Імідж» — a11y toolbar, mobile nav, mega-menu keyboard support.
 */
(function () {
  'use strict';

  var STORAGE_CONTRAST = 'imidzh-a11y-contrast';
  var STORAGE_FONT = 'imidzh-a11y-font';
  var MQ_DESKTOP = window.matchMedia('(min-width: 769px)');
  var MQ_HOVER = window.matchMedia('(hover: hover) and (pointer: fine)');

  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  /* ---------- Accessibility toolbar ---------- */
  function initA11y() {
    var btnContrast = document.getElementById('btn-contrast');
    var btnFontUp = document.getElementById('btn-font-up');
    var btnFontReset = document.getElementById('btn-font-reset');
    var root = document.documentElement;

    if (localStorage.getItem(STORAGE_CONTRAST) === 'true') {
      document.body.classList.add('high-contrast');
      if (btnContrast) {
        btnContrast.setAttribute('aria-pressed', 'true');
      }
    }

    var savedFont = parseInt(localStorage.getItem(STORAGE_FONT) || '100', 10);
    if (!isNaN(savedFont) && savedFont !== 100) {
      root.style.fontSize = savedFont + '%';
    }

    if (btnContrast) {
      btnContrast.addEventListener('click', function () {
        var on = document.body.classList.toggle('high-contrast');
        btnContrast.setAttribute('aria-pressed', on ? 'true' : 'false');
        localStorage.setItem(STORAGE_CONTRAST, on ? 'true' : 'false');
      });
    }

    if (btnFontUp) {
      btnFontUp.addEventListener('click', function () {
        var scale = parseInt(root.style.fontSize || '100', 10);
        if (isNaN(scale)) scale = 100;
        if (scale < 140) {
          scale += 10;
          root.style.fontSize = scale + '%';
          localStorage.setItem(STORAGE_FONT, String(scale));
        }
      });
    }

    if (btnFontReset) {
      btnFontReset.addEventListener('click', function () {
        root.style.fontSize = '100%';
        localStorage.setItem(STORAGE_FONT, '100');
      });
    }
  }

  /* ---------- Mobile drawer toggle ---------- */
  function initMobileToggle() {
    var toggle = document.getElementById('menu-toggle');
    var nav = document.getElementById('primary-nav');
    if (!toggle || !nav) return;

    var i18n = (window.imidzhTheme && window.imidzhTheme.i18n) || {};

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? (i18n.closeMenu || 'Закрити меню') : (i18n.openMenu || 'Відкрити меню'));
      nav.classList.toggle('is-open', open);
      document.body.classList.toggle('nav-open', open);
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('click', function (e) {
      if (!nav.classList.contains('is-open')) return;
      if (toggle.contains(e.target) || nav.contains(e.target)) return;
      setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        setOpen(false);
        toggle.focus();
      }
    });

    MQ_DESKTOP.addEventListener('change', function (ev) {
      if (ev.matches) setOpen(false);
    });
  }

  /* ---------- Mega menu: accordion + keyboard ---------- */
  function initMegaMenu() {
    var nav = document.getElementById('primary-nav');
    if (!nav) return;

    var menu = nav.querySelector('.mega-menu');
    if (!menu) return;

    var topItems = Array.prototype.slice.call(menu.children);

    function isDesktop() {
      return MQ_DESKTOP.matches;
    }

    function closeAll(except) {
      topItems.forEach(function (li) {
        if (except && li === except) return;
        li.classList.remove('is-open');
        var btn = li.querySelector(':scope > .mega-menu__trigger');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      });
    }

    function openItem(li) {
      closeAll(li);
      li.classList.add('is-open');
      var btn = li.querySelector(':scope > .mega-menu__trigger');
      if (btn) btn.setAttribute('aria-expanded', 'true');
    }

    function toggleItem(li) {
      if (li.classList.contains('is-open')) {
        li.classList.remove('is-open');
        var btn = li.querySelector(':scope > .mega-menu__trigger');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      } else {
        openItem(li);
      }
    }

    function getFocusable(container) {
      return Array.prototype.slice.call(
        container.querySelectorAll('a[href], button:not([disabled])')
      ).filter(function (el) {
        return el.offsetParent !== null || container.contains(el);
      });
    }

    topItems.forEach(function (li, index) {
      var trigger = li.querySelector(':scope > .mega-menu__trigger');
      var panel = li.querySelector(':scope > .mega-menu__panel');
      var link = li.querySelector(':scope > a');

      if (trigger) {
        trigger.addEventListener('click', function (e) {
          e.preventDefault();
          toggleItem(li);
        });

        trigger.addEventListener('keydown', function (e) {
          var key = e.key;

          if (key === 'Escape') {
            e.preventDefault();
            closeAll();
            trigger.focus();
            return;
          }

          if (key === 'ArrowDown') {
            e.preventDefault();
            openItem(li);
            if (panel) {
              var first = panel.querySelector('a, button');
              if (first) first.focus();
            }
            return;
          }

          if (key === 'ArrowRight' && isDesktop()) {
            e.preventDefault();
            closeAll();
            var next = topItems[index + 1];
            if (next) {
              var nextFocus = next.querySelector(':scope > .mega-menu__trigger, :scope > a');
              if (nextFocus) nextFocus.focus();
            }
            return;
          }

          if (key === 'ArrowLeft' && isDesktop()) {
            e.preventDefault();
            closeAll();
            var prev = topItems[index - 1];
            if (prev) {
              var prevFocus = prev.querySelector(':scope > .mega-menu__trigger, :scope > a');
              if (prevFocus) prevFocus.focus();
            }
            return;
          }

          if (key === 'Enter' || key === ' ') {
            e.preventDefault();
            toggleItem(li);
          }
        });
      }

      if (link) {
        link.addEventListener('keydown', function (e) {
          if (!isDesktop()) return;
          if (e.key === 'ArrowRight') {
            e.preventDefault();
            var n = topItems[index + 1];
            if (n) {
              var nf = n.querySelector(':scope > .mega-menu__trigger, :scope > a');
              if (nf) nf.focus();
            }
          }
          if (e.key === 'ArrowLeft') {
            e.preventDefault();
            var p = topItems[index - 1];
            if (p) {
              var pf = p.querySelector(':scope > .mega-menu__trigger, :scope > a');
              if (pf) pf.focus();
            }
          }
        });
      }

      if (panel) {
        panel.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') {
            e.preventDefault();
            closeAll();
            if (trigger) trigger.focus();
            return;
          }

          if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') return;

          var focusables = getFocusable(panel);
          var i = focusables.indexOf(document.activeElement);
          if (i < 0) return;

          e.preventDefault();
          if (e.key === 'ArrowDown') {
            focusables[(i + 1) % focusables.length].focus();
          } else {
            focusables[(i - 1 + focusables.length) % focusables.length].focus();
          }
        });
      }
    });

    // Close on outside click (desktop panels)
    document.addEventListener('click', function (e) {
      if (!menu.contains(e.target)) {
        closeAll();
      }
    });

    // Hover intent: sync aria-expanded when CSS :hover opens panel
    if (MQ_HOVER.matches) {
      topItems.forEach(function (li) {
        var trigger = li.querySelector(':scope > .mega-menu__trigger');
        if (!trigger) return;
        li.addEventListener('mouseenter', function () {
          if (!isDesktop()) return;
          trigger.setAttribute('aria-expanded', 'true');
          li.classList.add('is-open');
        });
        li.addEventListener('mouseleave', function () {
          if (!isDesktop()) return;
          if (li.contains(document.activeElement)) return;
          trigger.setAttribute('aria-expanded', 'false');
          li.classList.remove('is-open');
        });
      });
    }
  }

  ready(function () {
    initA11y();
    initMobileToggle();
    initMegaMenu();
  });
})();
