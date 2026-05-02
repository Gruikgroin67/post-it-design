/*
 * POSTITDESIGN_CONFINED_PLUGIN_ONLY
 * Ce JS ne s'exécute que sur la page du plugin.
 * Il ne touche jamais les Designs Jeedom.
 */
(function () {
  'use strict';

  var params = new URLSearchParams(window.location.search || '');
  var page = params.get('p') || '';
  var plugin = params.get('m') || '';

  if (page !== 'postitdesign' && plugin !== 'postitdesign') {
    return;
  }

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else {
      fn();
    }
  }

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function val(sel, fallback) {
    var el = qs(sel);
    if (!el) return fallback;
    return el.value !== undefined ? el.value : fallback;
  }

  function setText(sel, text) {
    var el = qs(sel);
    if (el) el.textContent = text;
  }

  function validColor(color) {
    return /^#[0-9a-fA-F]{6}$/.test(color || '') ? color : '#fff475';
  }

  function clampInt(value, fallback, min, max) {
    var n = parseInt(value, 10);
    if (!Number.isFinite(n)) n = fallback;
    return Math.max(min, Math.min(max, n));
  }

  function updatePreview() {
    var title = val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_title"]', 'Titre');
    var message = val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_message"]', 'Message');
    var color = validColor(val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_color"]', '#fff475'));
    var width = clampInt(val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_width"]', '220'), 220, 120, 800);
    var height = clampInt(val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_height"]', '160'), 160, 80, 600);
    var rotate = clampInt(val('.eqLogicAttr[data-l1key="configuration"][data-l2key="postit_rotate"]', '0'), 0, -8, 8);

    var preview = qs('.postitdesign-preview');
    if (!preview) return;

    preview.style.background = color;
    preview.style.width = width + 'px';
    preview.style.minHeight = height + 'px';
    preview.style.transform = 'rotate(' + rotate + 'deg)';

    setText('.postitdesign-preview-title', title);
    setText('.postitdesign-preview-message', message);
  }

  ready(function () {
    var root = qs('.postitdesign-plugin-page') || document;

    root.addEventListener('input', function (e) {
      if (e.target && e.target.classList && e.target.classList.contains('eqLogicAttr')) {
        updatePreview();
      }
    });

    root.addEventListener('change', function (e) {
      if (e.target && e.target.classList && e.target.classList.contains('eqLogicAttr')) {
        updatePreview();
      }
    });

    updatePreview();
  });
})();
