<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('{{401 - Accès non autorisé}}');
    }

    ajax::init();

    function postitdesign_ajax_eqlogic($eqLogic_id)
    {
        $eqLogic_id = intval($eqLogic_id);
        if ($eqLogic_id <= 0) {
            throw new Exception('{{Post-it invalide}}');
        }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        return $eqLogic;
    }

    function postitdesign_ajax_plan_header($planHeader_id)
    {
        $planHeader_id = intval($planHeader_id);
        if ($planHeader_id <= 0) {
            throw new Exception('{{Design cible introuvable}}');
        }

        $planHeader = planHeader::byId($planHeader_id);
        if (!is_object($planHeader)) {
            throw new Exception('{{Design cible introuvable}}');
        }

        if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('w')) {
            throw new Exception('{{Vous n’avez pas le droit de modifier ce Design}}');
        }

        return $planHeader;
    }

    if (init('action') == 'ping') {
        ajax::success(array(
            'ok' => true,
            'plugin' => 'postitdesign',
            'mode' => 'scoped_move',
            'time' => date('Y-m-d H:i:s')
        ));
    }

    if (init('action') == 'savePositionFromDesign') {
        $eqLogic = postitdesign_ajax_eqlogic(init('eqLogic_id'));
        $planHeader = postitdesign_ajax_plan_header(init('planHeader_id'));

        $x = intval(init('x'));
        $y = intval(init('y'));

        if ($x < 0) {
            $x = 0;
        }
        if ($y < 0) {
            $y = 0;
        }

        $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
        if (!is_object($plan)) {
            throw new Exception('{{Ligne Design existante introuvable : position non sauvegardée}}');
        }

        $plan->setPosition('left', $x);
        $plan->setPosition('top', $y);
        $plan->save();

        $eqLogic->setConfiguration('target_planHeader_id', $planHeader->getId());
        $eqLogic->setConfiguration('target_x', $x);
        $eqLogic->setConfiguration('target_y', $y);
        $eqLogic->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'planHeader_id' => $planHeader->getId(),
            'x' => $x,
            'y' => $y
        ));
    }

    if (init('action') == 'saveRotationFromDesign') {
        $eqLogic = postitdesign_ajax_eqlogic(init('eqLogic_id'));
        $rotate = intval(init('rotate'));

        if ($rotate < -15) {
            $rotate = -15;
        }
        if ($rotate > 15) {
            $rotate = 15;
        }

        $eqLogic->setConfiguration('postit_rotate', $rotate);
        $eqLogic->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'rotate' => $rotate
        ));
    }

    if (init('action') == 'completeFromDesign') {
        $eqLogic = postitdesign_ajax_eqlogic(init('eqLogic_id'));
        $text = trim(init('text'));

        if ($text == '') {
            throw new Exception('{{Texte vide}}');
        }

        $current = trim((string) $eqLogic->getConfiguration('postit_message', ''));
        $newMessage = ($current == '') ? $text : $current . "\n" . $text;

        $eqLogic->setConfiguration('postit_message', $newMessage);
        $eqLogic->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'message' => $newMessage,
            'message_html' => nl2br(htmlspecialchars($newMessage, ENT_QUOTES, 'UTF-8'))
        ));
    }

    if (init('action') == 'removeFromDesign') {
        $eqLogic = postitdesign_ajax_eqlogic(init('eqLogic_id'));
        $planHeader = postitdesign_ajax_plan_header(init('planHeader_id'));

        $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
        if (!is_object($plan)) {
            throw new Exception('{{Ligne Design existante introuvable}}');
        }

        $plan->remove();

        if (intval($eqLogic->getConfiguration('target_planHeader_id', 0)) == intval($planHeader->getId())) {
            $eqLogic->setConfiguration('target_planHeader_id', '');
            $eqLogic->save();
        }

        ajax::success(array(
            'ok' => true,
            'removed' => 1,
            'eqLogic_id' => $eqLogic->getId(),
            'planHeader_id' => $planHeader->getId()
        ));
    }

    if (init('action') == 'createDesign') {
        throw new Exception('{{Action createDesign désactivée en version safe}}');
    }

    if (init('action') == 'createFromDesign') {
        throw new Exception('{{Action createFromDesign désactivée en version safe}}');
    }

    if (init('action') == 'stickToDesign') {
        throw new Exception('{{Action stickToDesign désactivée en version safe : utiliser l’ajout natif Jeedom}}');
    }

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
