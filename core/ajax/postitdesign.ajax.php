<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('{{401 - Accès non autorisé}}');
    }

    ajax::init();

    if (init('action') == 'ping') {
        ajax::success(array(
            'ok' => true,
            'plugin' => 'postitdesign',
            'mode' => 'simple_isolated',
            'time' => date('Y-m-d H:i:s')
        ));
    }

    ajax::error('{{Action non disponible en version simple isolée}}');
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
