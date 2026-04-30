function postitdesignValue(selector, fallback) {
    var el = $(selector);
    if (!el.length) {
        return fallback || '';
    }
    var v = '';
    try {
        v = (typeof el.value === 'function') ? el.value() : el.val();
    } catch (e) {
        v = el.val();
    }
    if (v === undefined || v === null || v === '') {
        return fallback || '';
    }
    return v;
}

function postitdesignSetValue(selector, value) {
    var el = $(selector);
    if (!el.length) {
        return;
    }
    try {
        if (typeof el.value === 'function') {
            el.value(value);
        } else {
            el.val(value);
        }
    } catch (e) {
        el.val(value);
    }
}

function postitdesignEscapeHtml(text) {
    return $('<div>').text(text || '').html();
}

function postitdesignNl2br(text) {
    return postitdesignEscapeHtml(text || '').replace(/\n/g, '<br>');
}

function postitdesignShowResult(message) {
    $('#postitdesign_design_result').show().text(message);
}

function postitdesignNormalizeInt(value, fallback, minValue, maxValue) {
    var n = parseInt(value, 10);
    if (isNaN(n)) {
        n = fallback;
    }
    if (typeof minValue !== 'undefined' && n < minValue) {
        n = minValue;
    }
    if (typeof maxValue !== 'undefined' && n > maxValue) {
        n = maxValue;
    }
    return n;
}

function postitdesignSetRotate(value) {
    var rotate = postitdesignNormalizeInt(value, -1, -15, 15);
    postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_rotate]', rotate);
    $('#postitdesign_rotate_range').val(rotate);
    modifyWithoutSave = true;
    postitdesignUpdatePreview();
}

function postitdesignSyncRotateRange() {
    var rotate = postitdesignNormalizeInt(
        postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_rotate]', '-1'),
        -1,
        -15,
        15
    );
    $('#postitdesign_rotate_range').val(rotate);
}

function postitdesignInitRotateHandle() {
    var $preview = $('#postitdesign_live_preview');
    var $handle = $preview.find('.postitdesign-rotate-handle');

    if (!$preview.length || !$handle.length) {
        return;
    }

    if ($handle.data('postitdesign-rotate-init')) {
        return;
    }

    function angleFromEvent(e) {
        var ev = e.originalEvent && e.originalEvent.touches && e.originalEvent.touches.length ? e.originalEvent.touches[0] : e;
        var offset = $preview.offset();
        var cx = offset.left + ($preview.outerWidth() / 2);
        var cy = offset.top + ($preview.outerHeight() / 2);
        var dx = ev.pageX - cx;
        var dy = ev.pageY - cy;
        var deg = Math.atan2(dy, dx) * 180 / Math.PI + 90;
        deg = Math.round(deg);
        if (deg < -15) {
            deg = -15;
        }
        if (deg > 15) {
            deg = 15;
        }
        return deg;
    }

    $handle.on('mousedown.postitdesign touchstart.postitdesign', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $preview.addClass('postitdesign-rotating');

        $(document).on('mousemove.postitdesignRotate touchmove.postitdesignRotate', function(ev) {
            ev.preventDefault();
            postitdesignSetRotate(angleFromEvent(ev));
        });

        $(document).on('mouseup.postitdesignRotate touchend.postitdesignRotate touchcancel.postitdesignRotate', function() {
            $preview.removeClass('postitdesign-rotating');
            $(document).off('.postitdesignRotate');
        });
    });

    $handle.data('postitdesign-rotate-init', 1);
}

function postitdesignUpdatePreview() {
    var title = postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_title]', 'Titre');
    var message = postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_message]', 'Ton message ici');
    var color = postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_color]', '#fff475');
    var width = postitdesignNormalizeInt(postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_width]', '220'), 220, 120, 1200);
    var height = postitdesignNormalizeInt(postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_height]', '160'), 160, 80, 1200);
    var rotate = postitdesignNormalizeInt(postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_rotate]', '-1'), -1, -15, 15);

    var $preview = $('#postitdesign_live_preview');
    if (!$preview.length) {
        return;
    }

    $preview.css({
        width: width + 'px',
        minHeight: height + 'px',
        background: color,
        transform: 'rotate(' + rotate + 'deg)'
    });

    $preview.find('.postitdesign-live-preview-title').text(title || 'Titre');
    $preview.find('.postitdesign-live-preview-message').html(postitdesignNl2br(message || 'Ton message ici'));
    postitdesignSyncRotateRange();

    if ($preview.data('ui-resizable')) {
        $preview.resizable('option', 'minWidth', 120);
        $preview.resizable('option', 'minHeight', 80);
    }
}

function postitdesignInitResizablePreview() {
    var $preview = $('#postitdesign_live_preview');
    if (!$preview.length) {
        return;
    }

    if (typeof $preview.resizable !== 'function') {
        return;
    }

    if ($preview.data('postitdesign-resizable-init')) {
        return;
    }

    $preview.resizable({
        handles: 'n,e,s,w,se,sw,ne,nw',
        minWidth: 120,
        minHeight: 80,
        start: function() {
            $preview.addClass('postitdesign-resizing');
        },
        stop: function(event, ui) {
            $preview.removeClass('postitdesign-resizing');
            postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_width]', Math.round(ui.size.width));
            postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_height]', Math.round(ui.size.height));
            modifyWithoutSave = true;
            postitdesignUpdatePreview();
        },
        resize: function(event, ui) {
            postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_width]', Math.round(ui.size.width));
            postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_height]', Math.round(ui.size.height));
        }
    });

    $preview.data('postitdesign-resizable-init', 1);
}

function postitdesignRefreshUi() {
    postitdesignInitResizablePreview();
    postitdesignInitRotateHandle();
    postitdesignUpdatePreview();
}

$(document).off('input.postitdesign change.postitdesign', '.eqLogicAttr[data-l1key=configuration][data-l2key=postit_title], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_message], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_color], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_width], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_height], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_rotate]')
.on('input.postitdesign change.postitdesign', '.eqLogicAttr[data-l1key=configuration][data-l2key=postit_title], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_message], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_color], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_width], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_height], .eqLogicAttr[data-l1key=configuration][data-l2key=postit_rotate]', function () {
    modifyWithoutSave = true;
    postitdesignUpdatePreview();
});


$(document).off('input.postitdesignRotateRange change.postitdesignRotateRange', '#postitdesign_rotate_range')
.on('input.postitdesignRotateRange change.postitdesignRotateRange', '#postitdesign_rotate_range', function () {
    postitdesignSetRotate($(this).val());
});

$('.postitdesignRotateQuick').off('click').on('click', function () {
    postitdesignSetRotate($(this).attr('data-rotate'));
});

$('.postitColor').off('click').on('click', function () {
    var color = $(this).attr('data-color');
    postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_color]', color);
    modifyWithoutSave = true;
    postitdesignUpdatePreview();
});

$('#bt_postitdesign_create_design').off('click').on('click', function () {
    var name = $('#postitdesign_new_design_name').val();

    if (!name) {
        alert('Indique un nom de Design, par exemple Frigo.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'plugins/postitdesign/core/ajax/postitdesign.ajax.php',
        data: {
            action: 'createDesign',
            name: name
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            alert('Erreur création Design : ' + error);
        },
        success: function (data) {
            if (data.state !== 'ok') {
                alert(data.result || 'Erreur création Design');
                return;
            }

            var id = data.result.id;
            var label = data.result.name;

            if ($('#postitdesign_target_planHeader_id option[value="' + id + '"]').length === 0) {
                $('#postitdesign_target_planHeader_id').append($('<option>', {
                    value: id,
                    text: label
                }));
            }

            postitdesignSetValue('#postitdesign_target_planHeader_id', id);
            $('#postitdesign_new_design_name').val('');
            postitdesignShowResult('Design créé : ' + label + ' (#' + id + '). Sauvegarde le post-it puis clique sur Coller.');
            modifyWithoutSave = true;
        }
    });
});

$('#bt_postitdesign_stick_design').off('click').on('click', function () {
    var eqLogicId = postitdesignValue('.eqLogicAttr[data-l1key=id]', '');
    var planHeaderId = postitdesignValue('#postitdesign_target_planHeader_id', '');
    var x = postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=target_x]', '30');
    var y = postitdesignValue('.eqLogicAttr[data-l1key=configuration][data-l2key=target_y]', '30');

    if (!eqLogicId) {
        alert('Sauvegarde d’abord le post-it avant de le coller sur un Design.');
        return;
    }

    if (!planHeaderId) {
        alert('Choisis un Design cible.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'plugins/postitdesign/core/ajax/postitdesign.ajax.php',
        data: {
            action: 'stickToDesign',
            eqLogic_id: eqLogicId,
            planHeader_id: planHeaderId,
            x: x,
            y: y
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            alert('Erreur collage Design : ' + error);
        },
        success: function (data) {
            if (data.state !== 'ok') {
                alert(data.result || 'Erreur collage Design');
                return;
            }

            postitdesignShowResult(
                'Post-it collé sur le Design "' +
                data.result.planHeader_name +
                '" à la position X=' +
                data.result.x +
                ', Y=' +
                data.result.y +
                '.'
            );

            alert('Post-it collé sur le Design : ' + data.result.planHeader_name);
        }
    });
});

$('a[href="#postittab"]').off('shown.bs.tab.postitdesign').on('shown.bs.tab.postitdesign', function () {
    setTimeout(function () {
        postitdesignRefreshUi();
    }, 50);
});

$('.eqLogicDisplayCard, .eqLogicAction[data-action="add"]').off('click.postitdesign').on('click.postitdesign', function () {
    setTimeout(function () {
        postitdesignRefreshUi();
    }, 250);
});

setTimeout(function () {
    postitdesignRefreshUi();
}, 100);

setTimeout(function () {
    postitdesignRefreshUi();
}, 500);

$(document).off('click.postitdesignOpenPlacer', '#bt_postitdesign_open_placer').on('click.postitdesignOpenPlacer', '#bt_postitdesign_open_placer', function () {
    var eqLogicId = '';
    try {
        eqLogicId = $('.eqLogicAttr[data-l1key=id]').value();
    } catch (e) {
        eqLogicId = $('.eqLogicAttr[data-l1key=id]').val();
    }

    if (!eqLogicId) {
        alert('Sauvegarde d’abord le post-it avant d’ouvrir le placement dynamique.');
        return;
    }

    window.open('/plugins/postitdesign/desktop/php/postitdesign_placer.php?id=' + encodeURIComponent(eqLogicId), '_blank');
});
