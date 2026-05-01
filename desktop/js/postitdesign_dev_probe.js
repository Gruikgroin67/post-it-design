(function () {
  'use strict';

  if (window.__postitdesignDevProbeLoaded) return;
  window.__postitdesignDevProbeLoaded = true;

  var panel = null;
  var lastInfo = null;

  function closest(el, selector) {
    while (el && el.nodeType === 1) {
      if (el.matches && el.matches(selector)) return el;
      el = el.parentNode;
    }
    return null;
  }

  function makePanel() {
    if (panel) return panel;

    panel = document.createElement('div');
    panel.id = 'postitdesign-dev-probe-panel';
    panel.style.position = 'fixed';
    panel.style.left = '8px';
    panel.style.bottom = '8px';
    panel.style.zIndex = '2147483647';
    panel.style.background = 'rgba(0,0,0,.88)';
    panel.style.color = '#fff';
    panel.style.font = '12px/1.35 monospace';
    panel.style.padding = '8px 10px';
    panel.style.borderRadius = '8px';
    panel.style.maxWidth = '92vw';
    panel.style.maxHeight = '42vh';
    panel.style.overflow = 'auto';
    panel.style.whiteSpace = 'pre-wrap';
    panel.style.pointerEvents = 'auto';
    panel.textContent = 'POSTIT DEV PROBE: chargé';
    document.body.appendChild(panel);

    panel.addEventListener('click', function () {
      if (lastInfo) {
        navigator.clipboard && navigator.clipboard.writeText(lastInfo).catch(function () {});
      }
    });

    return panel;
  }

  function short(el) {
    if (!el) return 'null';

    var out = el.tagName ? el.tagName.toLowerCase() : String(el);
    if (el.id) out += '#' + el.id;

    if (el.className && typeof el.className === 'string') {
      var cls = el.className.trim().split(/\s+/).slice(0, 8).join('.');
      if (cls) out += '.' + cls;
    }

    ['data-eqlogic_id', 'data-eqlogic-id', 'data-cmd_id', 'data-id', 'data-action', 'onclick', 'title', 'aria-label'].forEach(function (a) {
      var v = el.getAttribute && el.getAttribute(a);
      if (v) out += ' [' + a + '="' + String(v).slice(0, 80) + '"]';
    });

    return out;
  }

  function isPostitCandidate(el) {
    if (!el) return false;
    var txt = (
      (el.id || '') + ' ' +
      (el.className || '') + ' ' +
      (el.getAttribute && (el.getAttribute('data-eqlogic_id') || el.getAttribute('data-eqlogic-id') || '') || '') + ' ' +
      (el.innerHTML || '').slice(0, 500)
    ).toLowerCase();

    return txt.indexOf('postit') !== -1 || txt.indexOf('post-it') !== -1;
  }

  function findPostitRoot(target) {
    var root =
      closest(target, '.postitdesign-widget') ||
      closest(target, '[class*="postitdesign"]') ||
      closest(target, '[id*="postitdesign"]') ||
      closest(target, '.eqLogic-widget') ||
      closest(target, '.eqLogic') ||
      closest(target, '[data-eqlogic_id]') ||
      closest(target, '[data-eqlogic-id]');

    if (root && isPostitCandidate(root)) return root;

    var p = target;
    for (var i = 0; i < 8 && p; i++, p = p.parentNode) {
      if (isPostitCandidate(p)) return p;
    }

    return root;
  }

  function inspect(target, eventName) {
    var root = findPostitRoot(target);
    var control = closest(target, 'button,a,[role="button"],[onclick],[data-action],.btn');

    var lines = [];
    lines.push('POSTIT DEV PROBE OK');
    lines.push('event=' + eventName);
    lines.push('time=' + new Date().toLocaleTimeString());
    lines.push('');
    lines.push('target=' + short(target));
    lines.push('control=' + short(control));
    lines.push('root=' + short(root));

    if (root) {
      var st = getComputedStyle(root);
      lines.push('');
      lines.push('root position=' + st.position);
      lines.push('root z-index=' + st.zIndex);
      lines.push('root pointer-events=' + st.pointerEvents);
      lines.push('root touch-action=' + st.touchAction);
      lines.push('root transform=' + st.transform);
      lines.push('root left/top=' + st.left + ' / ' + st.top);

      var controls = root.querySelectorAll('button,a,[role="button"],[onclick],[data-action],.btn');
      lines.push('');
      lines.push('controls count=' + controls.length);

      Array.prototype.slice.call(controls, 0, 12).forEach(function (c, i) {
        var cs = getComputedStyle(c);
        lines.push('control[' + i + ']=' + short(c));
        lines.push('  pe=' + cs.pointerEvents + ' ta=' + cs.touchAction + ' z=' + cs.zIndex + ' display=' + cs.display);
      });
    }

    lastInfo = lines.join('\n');
    makePanel().textContent = lastInfo;
  }

  function markPostits() {
    document.querySelectorAll('.postitdesign-widget,[class*="postitdesign"],[id*="postitdesign"],.eqLogic-widget,.eqLogic,[data-eqlogic_id],[data-eqlogic-id]').forEach(function (el) {
      if (!isPostitCandidate(el)) return;
      el.style.outline = '2px dashed #00d1ff';
      el.style.outlineOffset = '2px';
      el.setAttribute('data-postit-dev-probe', '1');
    });
  }

  function boot() {
    makePanel();
    markPostits();

    ['touchstart', 'touchmove', 'touchend', 'pointerdown', 'pointermove', 'pointerup', 'click'].forEach(function (ev) {
      document.addEventListener(ev, function (e) {
        var root = findPostitRoot(e.target);
        if (!root || !isPostitCandidate(root)) return;
        inspect(e.target, ev);
      }, { capture: true, passive: true });
    });

    new MutationObserver(markPostits).observe(document.documentElement, {
      childList: true,
      subtree: true
    });

    setInterval(markPostits, 2000);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
