<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect()) {
        throw new Exception('{{401 - Accès non autorisé}}');
    }

    ajax::init();

    if (init('action') == 'ping') {
        ajax::success(array(
            'ok' => true,
            'plugin' => 'postitdesign',
            'time' => date('Y-m-d H:i:s')
        ));
    }

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));

} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
