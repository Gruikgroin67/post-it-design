$('.postitColor').off('click').on('click', function () {
    var color = $(this).attr('data-color');
    $('.eqLogicAttr[data-l1key=configuration][data-l2key=postit_color]').value(color);
    modifyWithoutSave = true;
});
