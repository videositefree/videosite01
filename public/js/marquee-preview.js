/* Marquee theme v3 — interakcje layoutu */
(function () {
  'use strict';

  /* ---------- Dark mode (jeden system, oba przełączniki: desktop + drawer) ---------- */
  var themeSwitches = Array.prototype.slice.call(document.querySelectorAll('.mode-switch input[type="checkbox"]'));
  var savedTheme = null;
  try { savedTheme = localStorage.getItem('theme'); } catch (e) {}
  var isDark = savedTheme ? savedTheme === 'dark' : true; // domyślnie ciemny motyw

  function applyTheme(dark) {
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
    themeSwitches.forEach(function (s) { s.checked = dark; });
    try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
  }
  applyTheme(isDark);
  themeSwitches.forEach(function (s) {
    s.addEventListener('change', function () { applyTheme(s.checked); });
  });

  /* ---------- Drawer (mobile off-canvas nav) ---------- */
  var drawer = document.getElementById('mq-drawer');
  var backdrop = document.getElementById('mq-drawer-backdrop');
  var openBtn = document.getElementById('mq-hamburger');
  var closeBtn = document.getElementById('mq-drawer-close');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    backdrop.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    openBtn && openBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    backdrop.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    openBtn && openBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }
  openBtn && openBtn.addEventListener('click', openDrawer);
  closeBtn && closeBtn.addEventListener('click', closeDrawer);
  backdrop && backdrop.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeDrawer(); closeSearch(); }
  });

  /* accordion "Tagi" inside drawer */
  document.querySelectorAll('.drawer__accordion').forEach(function (acc) {
    var btn = acc.querySelector('.drawer__accordion-btn');
    btn && btn.addEventListener('click', function () {
      acc.classList.toggle('is-open');
      var expanded = acc.classList.contains('is-open');
      btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    });
  });

  /* ---------- Search overlay ---------- */
  var searchOverlay = document.getElementById('mq-search-overlay');
  var searchToggleBtns = document.querySelectorAll('[data-search-toggle]');
  var searchInput = document.getElementById('mq-search-input');

  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('is-open');
    setTimeout(function () { searchInput && searchInput.focus(); }, 150);
  }
  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('is-open');
  }
  function toggleSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.contains('is-open') ? closeSearch() : openSearch();
  }
  searchToggleBtns.forEach(function (b) { b.addEventListener('click', toggleSearch); });
  document.addEventListener('click', function (e) {
    if (!searchOverlay || !searchOverlay.classList.contains('is-open')) return;
    if (searchOverlay.contains(e.target)) return;
    if (e.target.closest && e.target.closest('[data-search-toggle]')) return;
    closeSearch();
  });

  /* ---------- Scroll-to-top button ---------- */
  var topBtn = document.getElementById('myBtn');
  window.addEventListener('scroll', function () {
    if (!topBtn) return;
    var y = document.body.scrollTop || document.documentElement.scrollTop;
    topBtn.classList.toggle('is-visible', y > 400);
  });
  window.topFunction = function () {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  };

  /* ---------- Przeciąganie odblokowanego podglądu wideo (add_films) ---------- */
  function initDraggablePreview(card) {
    var handle = card.querySelector('[data-drag-handle]');
    if (!handle) return;
    var dragging = false, offsetX = 0, offsetY = 0;

    function point(e) { return e.touches ? e.touches[0] : e; }

    function onDown(e) {
      if (!card.classList.contains('is-floating')) return;
      var p = point(e);
      var rect = card.getBoundingClientRect();
      // pierwsze uchwycenie: zamień z position:sticky/relative na fixed w bieżącym miejscu
      card.style.width = rect.width + 'px';
      card.style.left = rect.left + 'px';
      card.style.top = rect.top + 'px';
      offsetX = p.clientX - rect.left;
      offsetY = p.clientY - rect.top;
      dragging = true;
      card.classList.add('is-dragging');
      e.preventDefault();
    }
    function onMove(e) {
      if (!dragging) return;
      var p = point(e);
      var rect = card.getBoundingClientRect();
      var x = p.clientX - offsetX;
      var y = p.clientY - offsetY;
      x = Math.max(8, Math.min(x, window.innerWidth - rect.width - 8));
      y = Math.max(8, Math.min(y, window.innerHeight - rect.height - 8));
      card.style.left = x + 'px';
      card.style.top = y + 'px';
      if (e.touches) e.preventDefault();
    }
    function onUp() { dragging = false; card.classList.remove('is-dragging'); }

    handle.addEventListener('mousedown', onDown);
    handle.addEventListener('touchstart', onDown, { passive: false });
    document.addEventListener('mousemove', onMove);
    document.addEventListener('touchmove', onMove, { passive: false });
    document.addEventListener('mouseup', onUp);
    document.addEventListener('touchend', onUp);
  }
  document.querySelectorAll('.upload-preview__card').forEach(initDraggablePreview);

  /* ---------- Hover-to-preview on film cards ---------- */
  var HOVER_DELAY = 220;
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var current = { card: null, video: null, timer: null };

  function stopPreview(entry) {
    if (!entry || !entry.card) return;
    clearTimeout(entry.timer);
    entry.card.classList.remove('is-playing');
    if (entry.video) {
      entry.video.pause();
      try { entry.video.currentTime = 0; } catch (e) {}
    }
  }
  function startPreview(card, video) {
    if (current.card && current.card !== card) stopPreview(current);
    current = { card: card, video: video, timer: current.timer };
    clearTimeout(current.timer);
    current.timer = setTimeout(function () {
      var p = video.play();
      if (p && typeof p.then === 'function') {
        p.then(function () { card.classList.add('is-playing'); }).catch(function () {});
      } else {
        card.classList.add('is-playing');
      }
    }, HOVER_DELAY);
  }
  function initPreviewCards() {
    if (reduceMotion) return;
    document.querySelectorAll('[data-preview="true"]').forEach(function (card) {
      var video = card.querySelector('video');
      if (!video) return;
      card.addEventListener('mouseenter', function () { startPreview(card, video); });
      card.addEventListener('mouseleave', function () { stopPreview({ card: card, video: video, timer: current.timer }); });
      card.addEventListener('focusin', function () { startPreview(card, video); });
      card.addEventListener('focusout', function () { stopPreview({ card: card, video: video, timer: current.timer }); });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPreviewCards);
  } else {
    initPreviewCards();
  }
})();
