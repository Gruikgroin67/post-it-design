<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$eqLogic_id = intval(init('id'));
$eqLogic = eqLogic::byId($eqLogic_id);

if (!is_object($eqLogic) || $eqLogic->getEqType_name() != 'postitdesign') {
    throw new Exception('{{Post-it introuvable}}');
}

$planHeaders = planHeader::all();

$title = htmlspecialchars($eqLogic->getConfiguration('postit_title', $eqLogic->getName()), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($eqLogic->getConfiguration('postit_message', 'Message'), ENT_QUOTES, 'UTF-8');
$color = $eqLogic->getConfiguration('postit_color', '#fff475');
$width = intval($eqLogic->getConfiguration('postit_width', 220));
$height = intval($eqLogic->getConfiguration('postit_height', 160));
$rotate = intval($eqLogic->getConfiguration('postit_rotate', -1));
$x = intval($eqLogic->getConfiguration('target_x', 30));
$y = intval($eqLogic->getConfiguration('target_y', 30));
$targetPlan = intval($eqLogic->getConfiguration('target_planHeader_id', 0));

if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
    $color = '#fff475';
}
if ($width < 80) { $width = 220; }
if ($height < 60) { $height = 160; }
if ($rotate < -15) { $rotate = -15; }
if ($rotate > 15) { $rotate = 15; }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Placement post-it</title>
    <style>
        body {
            margin: 0;
            padding: 18px;
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            color: #222;
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            padding: 12px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
        }

        select, input {
            height: 34px;
            padding: 5px 8px;
            border: 1px solid #bbb;
            border-radius: 4px;
        }

        button {
            height: 36px;
            padding: 0 14px;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
        }

        .btn-primary {
            background: #337ab7;
            color: #fff;
        }

        .btn-success {
            background: #3cae45;
            color: #fff;
        }

        .btn-default {
            background: #ddd;
            color: #222;
        }

        .stage-wrap {
            background: #fff;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
            overflow: auto;
        }

        #stage {
            position: relative;
            width: 1000px;
            height: 560px;
            background: #f7f7f7;
            border: 2px dashed rgba(0,0,0,.28);
            border-radius: 8px;
            overflow: hidden;
            touch-action: none;
        }

        #stage:after {
            content: "Zone Design Jeedom";
            position: absolute;
            right: 14px;
            top: 12px;
            color: rgba(0,0,0,.35);
            font-weight: 700;
            pointer-events: none;
        }

        .grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,0,0,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,.06) 1px, transparent 1px);
            background-size: 25px 25px;
            pointer-events: none;
        }

        #note {
            position: absolute;
            left: <?php echo $x; ?>px;
            top: <?php echo $y; ?>px;
            width: <?php echo $width; ?>px;
            min-height: <?php echo $height; ?>px;
            background: <?php echo $color; ?>;
            transform: rotate(<?php echo $rotate; ?>deg);
            transform-origin: center center;
            padding: 14px 16px;
            border-radius: 4px;
            box-shadow: 0 8px 18px rgba(0,0,0,.28);
            color: #2b2b2b;
            cursor: grab;
            user-select: none;
            touch-action: none;
            z-index: 5;
            overflow: hidden;
        }

        #note.dragging {
            cursor: grabbing;
            opacity: .92;
            box-shadow: 0 12px 26px rgba(0,0,0,.42);
        }

        .note-title {
            font-weight: 700;
            font-size: 16px;
            line-height: 1.2;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(0,0,0,.18);
            padding-bottom: 6px;
        }

        .note-message {
            font-size: 15px;
            line-height: 1.35;
            white-space: normal;
            word-wrap: break-word;
        }

        .status {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 6px;
            background: #eaf4ff;
            display: none;
        }

        .status.ok {
            background: #e8ffe8;
        }

        .status.err {
            background: #ffe8e8;
        }
    </style>
</head>
<body>

<div class="topbar">
    <strong>Placement dynamique du post-it #<?php echo $eqLogic->getId(); ?></strong>

    <label>Design</label>
    <select id="planHeaderId">
        <option value="">Choisir un Design</option>
        <?php foreach ($planHeaders as $planHeader) {
            if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('r')) {
                continue;
            }
            $selected = ($targetPlan == intval($planHeader->getId())) ? 'selected' : '';
            echo '<option value="' . $planHeader->getId() . '" ' . $selected . '>' . htmlspecialchars($planHeader->getName(), ENT_QUOTES, 'UTF-8') . '</option>';
        } ?>
    </select>

    <label>X</label>
    <input id="x" type="number" value="<?php echo $x; ?>" style="width:80px">

    <label>Y</label>
    <input id="y" type="number" value="<?php echo $y; ?>" style="width:80px">

    <button class="btn-default" id="centerBtn">Centrer</button>
    <button class="btn-success" id="stickBtn">Coller sur ce Design</button>
    <button class="btn-primary" id="closeBtn" type="button">Fermer</button>
</div>

<div class="stage-wrap">
    <div id="stage">
        <div class="grid"></div>
        <div id="note">
            <div class="note-title"><?php echo $title; ?></div>
            <div class="note-message"><?php echo nl2br($message); ?></div>
        </div>
    </div>
</div>

<div id="status" class="status"></div>

<script>
(function () {
    "use strict";

    const eqLogicId = <?php echo $eqLogic->getId(); ?>;
    const stage = document.getElementById("stage");
    const note = document.getElementById("note");
    const xInput = document.getElementById("x");
    const yInput = document.getElementById("y");
    const statusBox = document.getElementById("status");

    let dragging = false;
    let startX = 0;
    let startY = 0;
    let startLeft = 0;
    let startTop = 0;

    function clamp(n, min, max) {
        n = parseInt(n, 10);
        if (isNaN(n)) n = min;
        return Math.max(min, Math.min(max, n));
    }

    function maxX() {
        return Math.max(0, stage.clientWidth - note.offsetWidth);
    }

    function maxY() {
        return Math.max(0, stage.clientHeight - note.offsetHeight);
    }

    function setPos(x, y) {
        x = clamp(x, 0, maxX());
        y = clamp(y, 0, maxY());

        note.style.left = x + "px";
        note.style.top = y + "px";
        xInput.value = x;
        yInput.value = y;
    }

    function showStatus(msg, type) {
        statusBox.className = "status " + (type || "");
        statusBox.textContent = msg;
        statusBox.style.display = "block";
    }

    function syncOpenerFields() {
        try {
            if (!window.opener || window.opener.closed) {
                return false;
            }

            const x = xInput.value;
            const y = yInput.value;
            const planHeaderId = document.getElementById("planHeaderId").value;

            const openerDoc = window.opener.document;

            const xField = openerDoc.getElementById("postitdesign_target_x");
            const yField = openerDoc.getElementById("postitdesign_target_y");
            const planField = openerDoc.getElementById("postitdesign_target_planHeader_id");

            if (xField) {
                xField.value = x;
                xField.dispatchEvent(new Event("input", { bubbles: true }));
                xField.dispatchEvent(new Event("change", { bubbles: true }));
            }

            if (yField) {
                yField.value = y;
                yField.dispatchEvent(new Event("input", { bubbles: true }));
                yField.dispatchEvent(new Event("change", { bubbles: true }));
            }

            if (planField && planHeaderId) {
                planField.value = planHeaderId;
                planField.dispatchEvent(new Event("input", { bubbles: true }));
                planField.dispatchEvent(new Event("change", { bubbles: true }));
            }

            if (window.opener.jQuery) {
                const $ = window.opener.jQuery;
                $("#postitdesign_target_x", openerDoc).val(x).trigger("change");
                $("#postitdesign_target_y", openerDoc).val(y).trigger("change");
                if (planHeaderId) {
                    $("#postitdesign_target_planHeader_id", openerDoc).val(planHeaderId).trigger("change");
                }
            }

            return true;
        } catch (err) {
            return false;
        }
    }

    note.addEventListener("pointerdown", function (e) {
        dragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = parseInt(note.style.left, 10) || 0;
        startTop = parseInt(note.style.top, 10) || 0;
        note.classList.add("dragging");
        try { note.setPointerCapture(e.pointerId); } catch (err) {}
        e.preventDefault();
    });

    note.addEventListener("pointermove", function (e) {
        if (!dragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        setPos(Math.round(startLeft + dx), Math.round(startTop + dy));
        e.preventDefault();
    });

    function stopDrag(e) {
        dragging = false;
        note.classList.remove("dragging");
        try { note.releasePointerCapture(e.pointerId); } catch (err) {}
    }

    note.addEventListener("pointerup", stopDrag);
    note.addEventListener("pointercancel", stopDrag);

    xInput.addEventListener("input", function () {
        setPos(xInput.value, yInput.value);
    });

    yInput.addEventListener("input", function () {
        setPos(xInput.value, yInput.value);
    });

    document.getElementById("centerBtn").addEventListener("click", function () {
        setPos(Math.round(maxX() / 2), Math.round(maxY() / 2));
    });


    document.getElementById("closeBtn").addEventListener("click", function () {
        syncOpenerFields();
        window.close();
    });

    document.getElementById("stickBtn").addEventListener("click", function () {
        const planHeaderId = document.getElementById("planHeaderId").value;

        if (!planHeaderId) {
            showStatus("Choisis un Design cible.", "err");
            return;
        }

        const body = new URLSearchParams();
        body.append("action", "stickToDesign");
        body.append("eqLogic_id", eqLogicId);
        body.append("planHeader_id", planHeaderId);
        body.append("x", xInput.value);
        body.append("y", yInput.value);

        fetch("/plugins/postitdesign/core/ajax/postitdesign.ajax.php", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: body.toString()
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.state !== "ok") {
                throw new Error(data.result || "Erreur inconnue");
            }
            syncOpenerFields();
            showStatus("OK : post-it collé sur le Design à X=" + xInput.value + ", Y=" + yInput.value + ".", "ok");
            alert("OK : le post-it est collé sur le Design. Position X=" + xInput.value + ", Y=" + yInput.value + ".");
        })
        .catch(function (err) {
            showStatus("Erreur : " + err.message, "err");
        });
    });

    setPos(xInput.value, yInput.value);
})();
</script>

</body>
</html>
