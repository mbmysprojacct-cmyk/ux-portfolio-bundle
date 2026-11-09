/* Made by Mystie — Case Study Layout behaviour (UX Portfolio Bundle).
   Lens filter, contents list, keep reading, expand/collapse all, panel modes.
   No dependencies. Safe to load more than once. */
(function () {
  'use strict';
  if (window.__mcsLayout) return;
  window.__mcsLayout = true;

  var INITIAL_HASH = (window.location.hash || '').slice(1);
  var DOCKED = window.matchMedia('(min-width: 1100px)');
  var MOBILE = window.matchMedia('(max-width: 719px)');

  function $(s, r) { return (r || document).querySelector(s); }
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
  function tokens(el) { return (el.getAttribute('data-lens') || '').split(/\s+/).filter(Boolean); }

  // Containers that start with a Section Header widget become sections.
  // On the live page, widgets after a Keep Reading widget move into a hidden body.
  function setupContainers() {
    var editing = document.body.classList.contains('elementor-editor-active');
    $$('.mcs-sechead').forEach(function (h) {
      var sec = h.closest('.e-con');
      if (!sec || sec.dataset.mcsSec) return;
      sec.dataset.mcsSec = '1';
      sec.classList.add('mcs-sec');
      sec.setAttribute('data-lens', h.dataset.lens || 'all');
      sec.dataset.n = h.dataset.n || '';
      sec.dataset.short = h.dataset.short || '';
      var anchor = h.dataset.anchor || ('s' + sec.dataset.n);
      if (!sec.id) sec.id = 'cs-' + anchor;
      var t = $('.mcs-sec__title', h);
      if (t) { sec.setAttribute('role', 'region'); sec.setAttribute('aria-labelledby', t.id); }
      if (editing) return;
      var moreW = $$('.elementor-widget-ux-cs-more', sec).filter(function (w) { return w.closest('.mcs-sec') === sec; })[0];
      if (!moreW) return;
      var parent = moreW.parentElement, body = document.createElement('div');
      body.className = 'mcs-sec__body';
      body.id = 'body-' + anchor;
      body.hidden = true;
      var n = moreW.nextElementSibling;
      while (n) { var nx = n.nextElementSibling; body.appendChild(n); n = nx; }
      parent.insertBefore(body, moreW);
      parent.appendChild(moreW);
      var btn = $('.mcs-more', moreW);
      if (btn) { btn.setAttribute('aria-controls', body.id); btn.dataset.mcsMain = '1'; }
      // Nested folds: a Keep Reading inside an inner container hides only the widgets after it in that container.
      $$('.elementor-widget-ux-cs-more', sec).forEach(function (w, i) {
        if (w === moreW || w.dataset.mcsFold) return;
        w.dataset.mcsFold = '1';
        var p = w.parentElement, b = document.createElement('div');
        b.className = 'mcs-sec__body mcs-fold__body';
        b.id = 'body-' + anchor + '-f' + i;
        b.hidden = true;
        var m = w.nextElementSibling;
        while (m) { var mx = m.nextElementSibling; b.appendChild(m); m = mx; }
        p.insertBefore(b, w);
        p.appendChild(w);
        var fb = $('.mcs-more', w);
        if (fb) fb.setAttribute('aria-controls', b.id);
      });
    });
  }

  // Rail views drive an Elementor Tabs widget in the post. A rail tab whose
  // link is "#some-id" selects the tab that contains that element; "case"
  // selects the first tab. The template hero and panel show on the first tab only.
  function setupViews() {
    var tabs = $$('[data-mcs-view]');
    if (!tabs.length || document.documentElement.dataset.mcsViews) return;
    var editing = document.body.classList.contains('elementor-editor-active');
    function panelFor(t) {
      var id = t.getAttribute('aria-controls'), el = id ? document.getElementById(id) : null;
      return el ? el.closest('[role="tabpanel"]') : null;
    }
    var ntabs = null;
    tabs.forEach(function (t) { var p = panelFor(t); if (p && !ntabs) ntabs = p.closest('.e-n-tabs'); });
    if (!ntabs) return;
    document.documentElement.dataset.mcsViews = '1';
    function buttons() { return $$('[role="tab"]', ntabs).filter(function (b) { return b.closest('.e-n-tabs') === ntabs; }); }
    function btnFor(t) {
      if (t.dataset.mcsView === 'case') return buttons()[0] || null;
      var p = panelFor(t);
      return p ? ntabs.querySelector('[role="tab"][aria-controls="' + p.id + '"]') : null;
    }
    // Stop audio/video in a view when the visitor leaves it (Vimeo, YouTube, Drive, <video>, <audio>).
    function pauseIn(root) {
      $$('video, audio', root).forEach(function (m) { try { if (!m.paused) m.pause(); } catch (err) {} });
      $$('iframe', root).forEach(function (f) {
        var s = f.getAttribute('src') || '';
        try {
          if (/^https:\/\/player\.vimeo\.com\//.test(s)) {
            f.contentWindow.postMessage('{"method":"pause"}', 'https://player.vimeo.com');
          } else if (/^https:\/\/www\.youtube(-nocookie)?\.com\/embed\//.test(s) && /[?&]enablejsapi=1/.test(s)) {
            f.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', s.match(/^https:\/\/[^\/]+/)[0]);
          } else if (/^https:\/\/(www\.youtube(-nocookie)?\.com\/embed\/|drive\.google\.com\/)/.test(s)) {
            f.setAttribute('src', s); // No player API: reloading the frame stops playback.
          }
        } catch (err) {}
      });
    }
    var lastActive = null;
    function sync() {
      var bs = buttons(), active = bs.filter(function (b) { return b.getAttribute('aria-selected') === 'true'; })[0];
      if (lastActive && active && lastActive !== active) {
        var lp = document.getElementById(lastActive.getAttribute('aria-controls') || '');
        if (lp) pauseIn(lp);
      }
      if (active) lastActive = active;
      var v = 'case';
      if (active && active !== bs[0]) {
        v = 'other';
        tabs.forEach(function (t) { if (btnFor(t) === active) v = t.dataset.mcsView; });
      }
      document.documentElement.setAttribute('data-mcs-view', v);
      tabs.forEach(function (t) {
        var on = t.dataset.mcsView === v;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        if (on) t.setAttribute('aria-current', 'page'); else t.removeAttribute('aria-current');
      });
    }
    tabs.forEach(function (t) {
      t.addEventListener('click', function (e) {
        e.preventDefault();
        if (editing && !document.body.contains(ntabs)) { tabs.forEach(function (x) { var p = panelFor(x); if (p) ntabs = p.closest('.e-n-tabs') || ntabs; }); }
        var b = btnFor(t);
        if (b) b.click();
        setTimeout(function () { sync(); window.scrollTo(0, 0); }, 30);
        if (!editing) try {
          var u = new URL(window.location.href);
          u.hash = t.dataset.mcsView === 'case' ? '' : (t.getAttribute('aria-controls') || '');
          history.replaceState(null, '', u.toString());
        } catch (err) {}
      });
    });
    // In-content #links (e.g. "Try the prototype") that point into another tab:
    // open that tab, then scroll to the target.
    document.addEventListener('click', function (e) {
      var a = e.target.closest ? e.target.closest('a[href^="#"]') : null;
      if (!a || a.hasAttribute('data-mcs-view') || e.defaultPrevented) return;
      var id = '';
      try { id = decodeURIComponent(a.getAttribute('href').slice(1)); } catch (err) { return; }
      var target = id ? document.getElementById(id) : null, p = target ? target.closest('[role="tabpanel"]') : null;
      if (!p || p.closest('.e-n-tabs') !== ntabs) return;
      var b = ntabs.querySelector('[role="tab"][aria-controls="' + p.id + '"]');
      if (!b) return;
      e.preventDefault();
      if (b.getAttribute('aria-selected') !== 'true') b.click();
      setTimeout(function () { sync(); target.scrollIntoView({ block: 'start' }); }, 60);
      try { var u = new URL(window.location.href); u.hash = id; history.replaceState(null, '', u.toString()); } catch (err) {}
    });
    if (window.MutationObserver) new MutationObserver(sync).observe(ntabs, { subtree: true, attributes: true, attributeFilter: ['aria-selected'] });
    // Open the right tab for #id or Accordion Link (#accordion-ID-item-N) links.
    // Elementor's tab script may not be ready yet, so try again after load.
    var h = INITIAL_HASH, acc = h.match(/^accordion-(.+)-item-\d+$/);
    if (acc) h = acc[1];
    function openFromHash() {
      var target = h ? document.getElementById(h) : null, p = target ? target.closest('[role="tabpanel"]') : null;
      if (!p || p.closest('.e-n-tabs') !== ntabs) return;
      var b = ntabs.querySelector('[role="tab"][aria-controls="' + p.id + '"]');
      if (b && b.getAttribute('aria-selected') !== 'true') b.click();
      sync();
    }
    openFromHash();
    window.addEventListener('load', function () { setTimeout(openFromHash, 150); setTimeout(openFromHash, 900); });
    sync();
  }

  function init() {
    setupContainers();
    setupViews();
    var panel = $('.mcs-panel');
    var secs = $$('.mcs-sec');
    if (!secs.length && !panel) return;

    var cfg = panel ? panel.dataset : {};
    var allKey = 'all';
    var state = { lens: allKey, tocOpen: true };

    // ---- Keep reading toggles -------------------------------------------
    function setOpen(sec, open) {
      var btn = $('.mcs-more[data-mcs-main]', sec) || $('.mcs-more', sec);
      var body = btn ? document.getElementById(btn.getAttribute('aria-controls') || '') || $('.mcs-sec__body', sec) : null;
      if (!btn || !body) return;
      body.hidden = !open;
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      btn.textContent = open ? (btn.dataset.less || 'less ↑') : (btn.dataset.more || 'keep reading ↓');
    }
    secs.forEach(function (sec) {
      $$('.mcs-more', sec).forEach(function (btn) {
        if (btn.dataset.mcsBound) return;
        btn.dataset.mcsBound = '1';
        btn.addEventListener('click', function () {
          var open = btn.getAttribute('aria-expanded') !== 'true';
          if (btn.dataset.mcsMain || !btn.getAttribute('aria-controls')) { setOpen(sec, open); return; }
          var b = document.getElementById(btn.getAttribute('aria-controls'));
          if (!b) return;
          b.hidden = !open;
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
          btn.textContent = open ? (btn.dataset.less || 'less ↑') : (btn.dataset.more || 'keep reading ↓');
        });
      });
    });

    if (!panel || panel.dataset.mcsBound) return;
    panel.dataset.mcsBound = '1';

    // ---- Lenses -----------------------------------------------------------
    var lensBtns = $$('.mcs-lens', panel);
    var used = {};
    secs.forEach(function (s) { tokens(s).forEach(function (t) { used[t] = true; }); });
    if (cfg.which !== 'all') {
      lensBtns.forEach(function (b) { var k = b.dataset.lens; if (k !== allKey && !used[k]) b.hidden = true; });
    }
    function lensName(k) {
      var b = lensBtns.filter(function (x) { return x.dataset.lens === k; })[0];
      return b ? b.textContent.trim() : k;
    }
    function matches(s) {
      var t = tokens(s);
      return state.lens === allKey || t.indexOf('all') > -1 || t.indexOf(state.lens) > -1;
    }

    // ---- Contents list ------------------------------------------------------
    var tocList = $('.mcs-toc', panel);
    function go(sec) {
      var off = MOBILE.matches ? 64 : 16;
      window.scrollTo({ top: sec.getBoundingClientRect().top + window.scrollY - off, behavior: 'smooth' });
      if (!DOCKED.matches) closeOverlay();
      var h = $('.mcs-sec__title', sec);
      if (h) { h.setAttribute('tabindex', '-1'); setTimeout(function () { h.focus({ preventScroll: true }); }, 400); }
    }
    function renderToc() {
      if (!tocList) return;
      tocList.textContent = '';
      secs.filter(matches).forEach(function (s) {
        var li = document.createElement('li');
        var b = document.createElement('button');
        b.type = 'button';
        var n = document.createElement('span'); n.className = 'mcs-tw'; n.textContent = s.dataset.n || '';
        var t = document.createElement('span'); t.className = 'mcs-toc__t'; t.textContent = s.dataset.short || '';
        b.appendChild(n); b.appendChild(t);
        b.addEventListener('click', function () { go(s); });
        li.appendChild(b); tocList.appendChild(li);
      });
    }

    // ---- Apply a lens -------------------------------------------------------
    var note = $('[data-mcs-status]');
    function apply(k, sync) {
      if (k !== allKey && !lensBtns.some(function (b) { return b.dataset.lens === k && !b.hidden; })) k = allKey;
      state.lens = k;
      var shown = 0;
      secs.forEach(function (s) { var m = matches(s); s.classList.toggle('is-out', !m); if (m) shown++; });
      lensBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.dataset.lens === k ? 'true' : 'false'); });
      var label = lensName(k);
      $$('[data-mcs-lenslabel]').forEach(function (el) { el.textContent = label; });
      if (note) {
        note.hidden = k === allKey;
        var txt = $('.mcs-status__text', note);
        if (txt) txt.textContent = label + ': showing ' + shown + ' of ' + secs.length + ' sections';
      }
      document.documentElement.setAttribute('data-mcs-lens', k);
      renderToc();
      if (sync !== false && cfg.sync === 'yes' && window.history && history.replaceState) {
        try {
          var u = new URL(window.location.href);
          if (k === allKey) u.searchParams.delete('lens'); else u.searchParams.set('lens', k);
          history.replaceState(null, '', u.toString());
        } catch (e) {}
      }
    }
    lensBtns.forEach(function (b) { b.addEventListener('click', function () { apply(b.dataset.lens); }); });
    $$('[data-mcs-showall]').forEach(function (b) { b.addEventListener('click', function () { apply(allKey); }); });

    // ---- Contents toggle, expand / collapse all ---------------------------
    var tt = $('.mcs-panel__tt', panel), tools = $('.mcs-panel__tools', panel);
    // Expand / collapse all only does something when a section has a "keep reading" body.
    if (tools && !document.querySelector('.mcs-sec__body')) { tools.parentNode.removeChild(tools); tools = null; }
    if (tt) tt.addEventListener('click', function () {
      state.tocOpen = !state.tocOpen;
      tt.setAttribute('aria-expanded', state.tocOpen ? 'true' : 'false');
      $('span:last-child', tt).textContent = state.tocOpen ? '−' : '+';
      if (tocList) tocList.hidden = !state.tocOpen;
      if (tools) tools.hidden = !state.tocOpen;
    });
    $$('[data-mcs-expand]', panel).forEach(function (b) {
      b.addEventListener('click', function () { var o = b.dataset.mcsExpand === '1'; secs.forEach(function (s) { setOpen(s, o); }); });
    });

    // ---- Panel modes: docked (collapse) · drawer / sheet (open, close) -----
    var x = $('.mcs-panel__x', panel), strip = $('.mcs-panel__strip', panel);
    var fab = $('.mcs-fab'), openers = $$('[data-mcs-open]');
    var lastOpener = null;
    function syncX() {
      if (!x) return;
      var overlay = !DOCKED.matches;
      x.textContent = overlay ? '×' : '→';
      x.setAttribute('aria-label', overlay ? (cfg.closeLabel || 'Close') : (cfg.collapseLabel || 'Collapse panel'));
    }
    function openOverlay(btn) {
      lastOpener = btn || null;
      panel.classList.add('is-open');
      if (fab) fab.classList.add('is-hidden');
      openers.forEach(function (o) { o.setAttribute('aria-expanded', 'true'); });
      var first = $('.mcs-lens[aria-pressed="true"]', panel) || x;
      if (first) first.focus();
    }
    function closeOverlay() {
      panel.classList.remove('is-open');
      if (fab) fab.classList.remove('is-hidden');
      openers.forEach(function (o) { o.setAttribute('aria-expanded', 'false'); });
      if (lastOpener) { lastOpener.focus(); lastOpener = null; }
    }
    openers.forEach(function (o) { o.addEventListener('click', function () { openOverlay(o); }); });
    if (x) x.addEventListener('click', function () {
      if (DOCKED.matches) { panel.classList.add('is-collapsed'); if (strip) strip.focus(); }
      else closeOverlay();
    });
    if (strip) strip.addEventListener('click', function () { panel.classList.remove('is-collapsed'); if (x) x.focus(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && panel.classList.contains('is-open')) closeOverlay(); });
    function onMode() { syncX(); if (DOCKED.matches) closeOverlay(); }
    (DOCKED.addEventListener ? DOCKED.addEventListener('change', onMode) : DOCKED.addListener(onMode));
    syncX();

    // ---- Start ---------------------------------------------------------------
    var start = allKey;
    try { var q = new URL(window.location.href).searchParams.get('lens'); if (q) start = q.toLowerCase().replace(/[^a-z0-9_-]/g, ''); } catch (e) {}
    apply(start, false);
  }

  // Phone prototype scale: the screen shrinks to fit short laptop windows, so
  // render the page at real phone size (390 x 844) and scale it down instead
  // of squeezing the layout.
  function fitPhones() {
    document.querySelectorAll('.mcs-pane .prototype-display.device-mobile .device-screen, .mcs-proto--iphone8 .prototype-display.device-mobile .device-screen').forEach(function (sc) {
      var f = sc.querySelector('.prototype-embed'); if (!f) return;
      // Options (widget CSS classes): mcs-proto--iphone8 = 375x667 screens;
      // mcs-proto--fill = render Figma's embed padding (48px) outside the visible screen.
      var w8 = sc.closest('.mcs-proto--iphone8'), fill = sc.closest('.mcs-proto--fill') && sc.closest('.mode-interactive');
      var W = w8 ? 375 : 390, H = w8 ? 667 : 844, P = fill ? 48 : 0;
      var s = Math.min(1, sc.clientWidth / W);
      sc.style.overflow = 'hidden';
      ['width:' + (W + 2 * P) + 'px', 'height:' + (H + 2 * P) + 'px', 'transform:translate(' + (-P * s) + 'px,' + (-P * s) + 'px) scale(' + s + ')', 'transform-origin:0 0', 'max-width:none'].forEach(function (d) {
        var p = d.split(':'); f.style.setProperty(p[0], p.slice(1).join(':'), 'important');
      });
    });
    fitDesktops();
  }
  // Desktop prototype scale (opt-in with the ux-proto-fit-desktop class): render
  // at a real 1440px desktop width and scale it down, so the page shows its
  // desktop layout instead of reflowing to the narrow frame.
  function fitDesktops() {
    document.querySelectorAll('.mcs-pane .ux-proto-fit-desktop .prototype-display.device-desktop .device-screen').forEach(function (sc) {
      var f = sc.querySelector('.prototype-embed'); if (!f || !sc.clientWidth) return;
      var s = Math.min(1, sc.clientWidth / 1440), h = Math.round(sc.clientHeight / s);
      sc.style.overflow = 'hidden';
      ['width:1440px', 'height:' + h + 'px', 'transform:scale(' + s + ')', 'transform-origin:0 0', 'max-width:none'].forEach(function (d) {
        var p = d.split(':'); f.style.setProperty(p[0], p.slice(1).join(':'), 'important');
      });
    });
  }
  window.addEventListener('resize', fitPhones);
  window.addEventListener('load', fitPhones);
  document.addEventListener('click', function () { setTimeout(fitPhones, 120); });
  if (window.ResizeObserver) document.addEventListener('DOMContentLoaded', function () {
    var ro = new ResizeObserver(fitPhones);
    document.querySelectorAll('.mcs-pane .prototype-display .device-screen').forEach(function (sc) { ro.observe(sc); });
  });
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
  // Elementor editor preview re-renders widgets; re-run once they are ready.
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
      elementorFrontend.hooks.addAction('frontend/element_ready/ux-cs-panel.default', function () { window.__mcsLayout = false; init(); });
    }
  });
})();
