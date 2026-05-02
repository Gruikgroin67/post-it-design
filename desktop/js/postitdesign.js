(function () {
    'use strict';

    var root = document.getElementById('postitdesign_admin_root');
    if (!root) {
        return;
    }

    function findField(l2key) {
        return root.querySelector('[data-l1key="configuration"][data-l2key="' + l2key + '"]');
    }

    function fieldValue(l2key, fallback) {
        var field = findField(l2key);
        if (!field || field.value === undefined || field.value === null || field.value === '') {
            return fallback;
        }
        return field.value;
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function clampNumber(value, fallback, min, max) {
        var number = parseInt(value, 10);
        if (isNaN(number)) {
            number = fallback;
        }
        return Math.max(min, Math.min(max, number));
    }

    function updatePreview() {
        var preview = root.querySelector('.postitdesign-admin-preview .postitdesign-widget');
        if (!preview) {
            return;
        }

        var title = fieldValue('postit_title', 'Titre');
        var message = fieldValue('postit_message', 'Message');
        var color = fieldValue('postit_color', '#fff475');
        var width = clampNumber(fieldValue('postit_width', 220), 220, 120, 600);
        var height = clampNumber(fieldValue('postit_height', 160), 160, 80, 500);
        var rotate = clampNumber(fieldValue('postit_rotate', -1), -1, -10, 10);
        var visualStyle = fieldValue('visual_style', 'classic');

        if (!/^#[0-9a-fA-F]{6}$/.test(color)) {
            color = '#fff475';
        }
        if (['classic', 'paper', 'tape'].indexOf(visualStyle) === -1) {
            visualStyle = 'classic';
        }

        preview.className = 'postitdesign-widget postitdesign-style-' + visualStyle;
        preview.style.width = width + 'px';
        preview.style.minHeight = height + 'px';
        preview.style.setProperty('--postitdesign-bg', color);
        preview.style.setProperty('--postitdesign-rotate', rotate + 'deg');

        var titleEl = preview.querySelector('.postitdesign-title');
        var messageEl = preview.querySelector('.postitdesign-message');

        if (titleEl) {
            titleEl.innerHTML = escapeHtml(title);
        }
        if (messageEl) {
            messageEl.innerHTML = escapeHtml(message).replace(/\n/g, '<br>');
        }
    }

    root.addEventListener('input', function (event) {
        if (event.target && event.target.classList.contains('postitdesign-preview-field')) {
            updatePreview();
        }
    });

    root.addEventListener('change', function (event) {
        if (event.target && event.target.classList.contains('postitdesign-preview-field')) {
            updatePreview();
        }
    });

    window.printEqLogic = function () {
        window.setTimeout(updatePreview, 50);
        return true;
    };

    window.saveEqLogic = function (_eqLogic) {
        return _eqLogic;
    };

    window.addCmdToTable = function () {
        return true;
    };

    updatePreview();
}());
