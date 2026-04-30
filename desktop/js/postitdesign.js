function postitdesignValue(selector, fallback) {
    var el = $(selector);
    if (!el.length) {
        return fallback || '';
    }
    var v = '';
    try {
        v = el.value();
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
        el.value(value);
    } catch (e) {
        el.val(value);
    }
}

function postitdesignShowResult(message) {
    $('#postitdesign_design_result').show().text(message);
}

$('.postitColor').off('click').on('click', function () {
    var color = $(this).attr('data-color');
    postitdesignSetValue('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_color]', color);
    modifyWithoutSave = true;
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
