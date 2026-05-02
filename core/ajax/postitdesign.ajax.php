<?php

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('{{401 - Accès non autorisé}}');
    }

    ajax::init();

    if (init('action') == 'savePositionFromDesign') {
        $eqLogic_id = intval(init('eqLogic_id'));
        $planHeader_id = intval(init('planHeader_id'));
        $x = intval(init('x'));
        $y = intval(init('y'));

        if ($eqLogic_id <= 0) { throw new Exception('{{Post-it invalide}}'); }
        if ($x < 0) { $x = 0; }
        if ($y < 0) { $y = 0; }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        if ($planHeader_id <= 0) {
            $planHeader_id = intval($eqLogic->getConfiguration('target_planHeader_id', 0));
        }

        if ($planHeader_id > 0) {
            $planHeader = planHeader::byId($planHeader_id);
            if (is_object($planHeader)) {
                $plan = plan::byLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader->getId());
                if (is_object($plan)) {
                    $plan->setPosition('left', $x);
                    $plan->setPosition('top', $y);
                    $plan->save();
                }
            }
        }

        $eqLogic->setConfiguration('target_planHeader_id', $planHeader_id);
        $eqLogic->setConfiguration('target_x', $x);
        $eqLogic->setConfiguration('target_y', $y);
        $eqLogic->save();

        ajax::success(array('ok' => true, 'eqLogic_id' => $eqLogic->getId(), 'x' => $x, 'y' => $y));
    }

    if (init('action') == 'saveRotationFromDesign') {
        $eqLogic_id = intval(init('eqLogic_id'));
        $rotate = intval(init('rotate'));

        if ($eqLogic_id <= 0) { throw new Exception('{{Post-it invalide}}'); }
        if ($rotate < -9) { $rotate = -9; }
        if ($rotate > 9) { $rotate = 9; }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $eqLogic->setConfiguration('postit_rotate', $rotate);
        $eqLogic->save();

        if (method_exists($eqLogic, 'emptyCacheWidget')) {
            $eqLogic->emptyCacheWidget(); /* POSTITDESIGN_ROTATION_CACHE_CLEAR_V1 */
        }

        ajax::success(array('ok' => true, 'eqLogic_id' => $eqLogic->getId(), 'rotate' => $rotate));
    }

    if (init('action') == 'toggleStrikeLineFromDesign') {
        $eqLogic_id = intval(init('eqLogic_id'));
        $lineIndex = intval(init('line_index'));
        $struck = intval(init('struck'));

        if ($eqLogic_id <= 0) { throw new Exception('{{Post-it invalide}}'); }
        if ($lineIndex < 0 || $lineIndex > 500) { throw new Exception('{{Ligne invalide}}'); }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $current = array();
        foreach (explode(',', (string)$eqLogic->getConfiguration('postit_strikes', '')) as $idx) {
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

        ajax::success(array(
            'ok' => true,
            'eqLogic_id' => $eqLogic->getId(),
            'line_index' => $lineIndex,
            'struck' => $struck,
            'postit_strikes' => implode(',', $current)
        ));
    }

    if (init('action') == 'setMessageFromDesign') {
        $eqLogic_id = intval(init('eqLogic_id'));
        $text = trim((string)init('text'));

        if ($eqLogic_id <= 0) { throw new Exception('{{Post-it invalide}}'); }
        if (strlen($text) > 5000) { $text = substr($text, 0, 5000); }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        $eqLogic->setConfiguration('postit_message', $text);
        $eqLogic->setConfiguration('postit_strikes', '');
        $eqLogic->save();

        ajax::success(array('ok' => true, 'eqLogic_id' => $eqLogic->getId()));
    }

    if (init('action') == 'createFromDesign') {
        $planHeader_id = intval(init('planHeader_id'));
        $title = trim((string)init('title', 'Nouveau post-it'));
        $message = trim((string)init('message', 'Nouveau post-it'));
        $x = intval(init('x', 40));
        $y = intval(init('y', 40));

        if ($planHeader_id <= 0) { throw new Exception('{{Design cible introuvable}}'); }

        $planHeader = planHeader::byId($planHeader_id);
        if (!is_object($planHeader)) { throw new Exception('{{Design cible introuvable}}'); }

        $eqLogic = new postitdesign();
        $eqLogic->setEqType_name('postitdesign');
        $eqLogic->setName($title . ' ' . date('His'));
        $eqLogic->setIsEnable(1);
        $eqLogic->setIsVisible(1);
        $eqLogic->setConfiguration('postit_title', $title);
        $eqLogic->setConfiguration('postit_message', $message);
        $eqLogic->setConfiguration('postit_color', '#fff4a8');
        $eqLogic->setConfiguration('postit_width', 220);
        $eqLogic->setConfiguration('postit_height', 160);
        $eqLogic->setConfiguration('postit_rotate', -1);
        $eqLogic->setConfiguration('target_planHeader_id', $planHeader_id);
        $eqLogic->setConfiguration('target_x', $x);
        $eqLogic->setConfiguration('target_y', $y);
        $eqLogic->save();

        $plan = new plan();
        $plan->setPlanHeader_id($planHeader->getId());
        $plan->setLink_type('eqLogic');
        $plan->setLink_id($eqLogic->getId());
        $plan->setPosition('left', $x);
        $plan->setPosition('top', $y);
        $plan->setPosition('width', 220);
        $plan->setPosition('height', 160);
        $plan->setDisplay('name', 0);
        $plan->save();

        ajax::success(array('ok' => true, 'eqLogic_id' => $eqLogic->getId()));
    }

    if (init('action') == 'removeFromDesign') {
        $eqLogic_id = intval(init('eqLogic_id'));
        $planHeader_id = intval(init('planHeader_id'));

        if ($eqLogic_id <= 0) { throw new Exception('{{Post-it invalide}}'); }
        if ($planHeader_id <= 0) { throw new Exception('{{Design cible introuvable}}'); }

        $eqLogic = eqLogic::byId($eqLogic_id);
        if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
            throw new Exception('{{Equipement invalide pour Post-it Design}}');
        }

        plan::removeByLinkTypeLinkIdPlanHeaderId('eqLogic', $eqLogic->getId(), $planHeader_id);

        if (intval($eqLogic->getConfiguration('target_planHeader_id', 0)) == $planHeader_id) {
            $eqLogic->setConfiguration('target_planHeader_id', '');
            $eqLogic->save();
        }

        ajax::success(array('ok' => true, 'removed' => 1, 'eqLogic_id' => $eqLogic->getId()));
    }

    throw new Exception('{{Aucune méthode correspondante à}} : ' . init('action'));

} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
