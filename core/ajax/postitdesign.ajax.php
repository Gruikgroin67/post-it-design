<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');
    include_file('core', 'postitdesign', 'class', 'postitdesign');

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

    if (init('action') == 'listDesigns') {
        $return = array();

        foreach (planHeader::all() as $planHeader) {
            if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('r')) {
                continue;
            }

            $return[] = array(
                'id' => $planHeader->getId(),
                'name' => $planHeader->getName()
            );
        }

        ajax::success($return);
    }

    if (init('action') == 'createDesign') {
        ajax::error('Action createDesign désactivée : le plugin Post-it ne doit pas créer de Design.');
    }

    if (init('action') == 'stickToDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $planHeader_id = intval(init('planHeader_id'));

        if ($eqLogic_id <= 0) {
            throw new Exception('{{Sauvegarde le post-it avant de le coller sur un Design}}');
        }

        if ($planHeader_id <= 0) {
            throw new Exception('{{Design cible obligatoire}}');
        }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $planHeader = planHeader::byId($planHeader_id);
        if (!is_object($planHeader)) {
            throw new Exception('{{Design cible introuvable}}');
        }

        if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('w')) {
            throw new Exception('{{Vous n’avez pas le droit de modifier ce Design}}');
        }

        $x = intval(init('x'));
        $y = intval(init('y'));
        $width = intval($eqLogic->getConfiguration('postit_width', 220));
        $height = intval($eqLogic->getConfiguration('postit_height', 160));

        if ($x < 0) { $x = 20; }
        if ($y < 0) { $y = 20; }
        if ($width < 120) { $width = 220; }
        if ($height < 80) { $height = 160; }

        $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
        if (!is_object($plan)) {
            $plan = new plan();
            $plan->setPlanHeader_id($planHeader->getId());
            $plan->setLink_type('eqLogic');
            $plan->setLink_id($eqLogic->getId());
        }

        $plan->setPosition('left', $x);
        $plan->setPosition('top', $y);
        $plan->setPosition('width', $width);
        $plan->setPosition('height', $height);
        $plan->setDisplay('name', 0);
        if (method_exists($plan, 'setCss')) {
            /* Mode bandeau fixed : pas de z-index écrit dans la table plan. */
        }
        $plan->save();

        $eqLogic->setConfiguration('target_planHeader_id', $planHeader->getId());
        $eqLogic->setConfiguration('target_x', $x);
        $eqLogic->setConfiguration('target_y', $y);
        $eqLogic->save();

        ajax::success(array(
            'plan_id' => $plan->getId(),
            'planHeader_id' => $planHeader->getId(),
            'planHeader_name' => $planHeader->getName(),
            'eqLogic_id' => $eqLogic->getId(),
            'x' => $x,
            'y' => $y
        ));
    }


    if (init('action') == 'removeFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $planHeader_id = intval(init('planHeader_id'));

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

        $removed = 0;

        if ($planHeader_id > 0) {
            $planHeader = planHeader::byId($planHeader_id);
            if (!is_object($planHeader)) {
                throw new Exception('{{Design cible introuvable}}');
            }

            if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('w')) {
                throw new Exception('{{Vous n’avez pas le droit de modifier ce Design}}');
            }

            plan::removeByLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
            $removed = 1;

            if (intval($eqLogic->getConfiguration('target_planHeader_id', 0)) == $planHeader_id) {
                $eqLogic->setConfiguration('target_planHeader_id', '');
                $eqLogic->save();
            }
        } else {
            throw new Exception('{{Design cible obligatoire : décollage limité au Design courant}}');
        }

        ajax::success(array(
            'ok' => true,
            'removed' => $removed,
            'eqLogic_id' => $eqLogic->getId(),
            'planHeader_id' => $planHeader_id
        ));
    }


    if (init('action') == 'savePositionFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $planHeader_id = intval(init('planHeader_id'));
        $x = intval(init('x'));
        $y = intval(init('y'));

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

        if ($planHeader_id <= 0) {
            $planHeader_id = intval($eqLogic->getConfiguration('target_planHeader_id', 0));
        }

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

        if ($x < 0) { $x = 0; }
        if ($y < 0) { $y = 0; }

        $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
        if (!is_object($plan)) {
            throw new Exception('{{Ligne Design existante introuvable : ajoute le post-it au Design via Jeedom natif puis recharge}}');
        }
        /* Mode bandeau fixed : on ne modifie plus left/top dans la table plan au déplacement. */

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


    if (init('action') == 'completeFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $text = trim(init('text'));

        if ($eqLogic_id <= 0) {
            throw new Exception('{{Post-it invalide}}');
        }

        if ($text == '') {
            throw new Exception('{{Texte vide}}');
        }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $current = trim((string) $eqLogic->getConfiguration('postit_message', ''));
        if ($current == '') {
            $newMessage = $text;
        } else {
            $newMessage = $current . "\n" . $text;
        }

        $eqLogic->setConfiguration('postit_message', $newMessage);
        $eqLogic->save();

        $messageHtml = nl2br(htmlspecialchars($newMessage, ENT_QUOTES, 'UTF-8'));

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'message' => $newMessage,
            'message_html' => $messageHtml
        ));
    }


    if (init('action') == 'saveRotationFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $rotate = intval(init('rotate'));

        if ($eqLogic_id <= 0) {
            throw new Exception('{{Post-it invalide}}');
        }

        if ($rotate < -15) { $rotate = -15; }
        if ($rotate > 15) { $rotate = 15; }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $eqLogic->setConfiguration('postit_rotate', $rotate);
        $eqLogic->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'rotate' => $rotate
        ));
    }


    if (init('action') == 'setMessageFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $text = trim((string) init('text'));

        if ($eqLogic_id <= 0) {
            throw new Exception('{{Post-it invalide}}');
        }

        if (strlen($text) > 5000) {
            $text = substr($text, 0, 5000);
        }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $eqLogic->setConfiguration('postit_message', $text);
        $eqLogic->save();

        $messageHtml = nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'message' => $text,
            'message_html' => $messageHtml
        ));
    }

    if (init('action') == 'createFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $planHeader_id = intval(init('planHeader_id'));
        if ($planHeader_id <= 0) {
            throw new Exception('{{Design cible obligatoire}}');
        }

        $planHeader = planHeader::byId($planHeader_id);
        if (!is_object($planHeader)) {
            throw new Exception('{{Design cible introuvable}}');
        }

        if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('w')) {
            throw new Exception('{{Vous n’avez pas le droit de modifier ce Design}}');
        }

        $title = trim((string) init('title'));
        $message = trim((string) init('message'));
        $color = trim((string) init('color'));
        $rotate = intval(init('rotate'));
        $x = intval(init('x'));
        $y = intval(init('y'));
        $width = intval(init('width'));
        $height = intval(init('height'));

        if ($title == '') { $title = 'Nouveau post-it'; }
        if ($message == '') { $message = 'Nouveau post-it'; }

        if (strlen($title) > 80) { $title = substr($title, 0, 80); }
        if (strlen($message) > 5000) { $message = substr($message, 0, 5000); }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        if ($rotate < -15) { $rotate = -15; }
        if ($rotate > 15) { $rotate = 15; }

        if ($x < 0) { $x = 20; }
        if ($y < 0) { $y = 20; }
        if ($x > 5000) { $x = 5000; }
        if ($y > 5000) { $y = 5000; }

        if ($width < 120) { $width = 220; }
        if ($width > 900) { $width = 900; }
        if ($height < 80) { $height = 160; }
        if ($height > 700) { $height = 700; }

        $eqLogic = new postitdesign();
        $eqLogic->setEqType_name('postitdesign');
        $eqLogic->setName($title . ' ' . date('His'));
        $eqLogic->setIsEnable(1);
        $eqLogic->setIsVisible(1);
        $eqLogic->setConfiguration('postit_title', $title);
        $eqLogic->setConfiguration('postit_message', $message);
        $eqLogic->setConfiguration('postit_color', $color);
        $eqLogic->setConfiguration('postit_width', $width);
        $eqLogic->setConfiguration('postit_height', $height);
        $eqLogic->setConfiguration('postit_rotate', $rotate);
        $eqLogic->setConfiguration('target_planHeader_id', $planHeader->getId());
        $eqLogic->setConfiguration('target_x', $x);
        $eqLogic->setConfiguration('target_y', $y);
        $eqLogic->save();

        $plan = new plan();
        $plan->setPlanHeader_id($planHeader->getId());
        $plan->setLink_type('eqLogic');
        $plan->setLink_id($eqLogic->getId());
        $plan->setPosition('left', $x);
        $plan->setPosition('top', $y);
        $plan->setPosition('width', $width);
        $plan->setPosition('height', $height);
        $plan->setDisplay('name', 0);
        $plan->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'plan_id' => $plan->getId(),
            'planHeader_id' => $planHeader->getId(),
            'x' => $x,
            'y' => $y
        ));
    }



    if (init('action') == 'toggleStrikeLineFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $eqLogic_id = intval(init('eqLogic_id'));
        $lineIndex = intval(init('line_index'));
        $struck = intval(init('struck'));

        if ($eqLogic_id <= 0) {
            throw new Exception('{{Post-it invalide}}');
        }

        if ($lineIndex < 0 || $lineIndex > 500) {
            throw new Exception('{{Ligne invalide}}');
        }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic)) {
            throw new Exception('{{Post-it introuvable}}');
        }

        if ($eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $current = array();
        foreach (explode(',', (string) $eqLogic->getConfiguration('postit_strikes', '')) as $idx) {
            $idx = trim($idx);
            if ($idx !== '' && ctype_digit($idx)) {
                $current[] = intval($idx);
            }
        }
        $current = array_values(array_unique($current));

        if ($struck === 1) {
            if (!in_array($lineIndex, $current, true)) {
                $current[] = $lineIndex;
            }
        } else {
            $current = array_values(array_diff($current, array($lineIndex)));
        }

        sort($current);
        $eqLogic->setConfiguration('postit_strikes', implode(',', $current));
        $eqLogic->save();

        if (method_exists($eqLogic, 'emptyCacheWidget')) {
            $eqLogic->emptyCacheWidget();
        }

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'line_index' => $lineIndex,
            'struck' => $struck,
            'postit_strikes' => implode(',', $current)
        ));
    }

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));

} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
