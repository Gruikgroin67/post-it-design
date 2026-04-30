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

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));

} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
