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
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $name = trim(init('name'));
        if ($name == '') {
            throw new Exception('{{Nom du design obligatoire}}');
        }

        $planHeader = new planHeader();
        $planHeader->setName($name);
        $planHeader->save();

        ajax::success(array(
            'id' => $planHeader->getId(),
            'name' => $planHeader->getName()
        ));
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
            $plans = plan::byLinkTypeLinkId('eqLogic', $eqLogic->getId());
            foreach ($plans as $plan) {
                if (is_object($plan)) {
                    $plan->remove();
                    $removed++;
                }
            }

            $eqLogic->setConfiguration('target_planHeader_id', '');
            $eqLogic->save();
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
            $plan = new plan();
            $plan->setPlanHeader_id($planHeader->getId());
            $plan->setLink_type('eqLogic');
            $plan->setLink_id($eqLogic->getId());
        }

        $plan->setPosition('left', $x);
        $plan->setPosition('top', $y);
        $plan->setDisplay('name', 0);
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

    if (init('action') == 'createFromDesign') {
        if (!isConnect('admin')) {
            throw new Exception('{{401 - Accès non autorisé}}');
        }

        $planHeader_id = intval(init('planHeader_id'));
        $title = trim(init('title'));
        $message = trim(init('message'));
        $color = trim(init('color'));
        $rotate = intval(init('rotate'));
        $x = intval(init('x'));
        $y = intval(init('y'));
        $width = intval(init('width'));
        $height = intval(init('height'));

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

        if ($title == '') {
            $title = 'Nouveau post-it';
        }

        $colors = array(
            'jaune' => '#fff475',
            'vert' => '#b8f7ad',
            'rose' => '#ffc0cb',
            'bleu' => '#bfe7ff'
        );

        $colorKey = strtolower($color);
        if (isset($colors[$colorKey])) {
            $color = $colors[$colorKey];
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#fff475';
        }

        if ($rotate < -15) { $rotate = -15; }
        if ($rotate > 15) { $rotate = 15; }

        if ($width < 120) { $width = 220; }
        if ($width > 900) { $width = 900; }

        if ($height < 80) { $height = 160; }
        if ($height > 700) { $height = 700; }

        if ($x < 0) { $x = 0; }
        if ($y < 0) { $y = 0; }

        $eqLogic = new postitdesign();
        $eqLogic->setName($title);
        $eqLogic->setEqType_name('postitdesign');
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
        $plan->setDisplay('name', 0);
        $plan->save();

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'planHeader_id' => $planHeader->getId(),
            'x' => $x,
            'y' => $y,
            'rotate' => $rotate
        ));
    }

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));

} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
