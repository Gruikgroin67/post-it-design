<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

echo '<!doctype html><html><head><meta charset="utf-8"><title>Post-it Design</title></head><body style="font-family:Arial;padding:20px">';
echo '<h3>Post-it Design</h3>';
echo '<p>Outil de placement désactivé : le plugin est cantonné à son rendu et ne modifie plus les Designs Jeedom.</p>';
echo '</body></html>';
