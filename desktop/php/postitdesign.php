<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

sendVarToJS('eqType', 'postitdesign');
$eqLogics = eqLogic::byType('postitdesign');
$planHeaders = planHeader::all();
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-sticky-note"></i> {{Post-it Design}}</legend>

        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction" data-action="add">
                <i class="fas fa-plus-circle"></i>
                <br>
                <span>{{Ajouter un post-it}}</span>
            </div>

            <div class="cursor eqLogicAction" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
            </div>

            <?php foreach ($eqLogics as $eqLogic) { ?>
                <div class="eqLogicDisplayCard cursor" data-eqLogic_id="<?php echo $eqLogic->getId(); ?>">
                    <i class="fas fa-sticky-note" style="font-size:40px;color:#d6b400;"></i>
                    <br>
                    <span class="name"><?php echo $eqLogic->getHumanName(true, true); ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="col-xs-12 eqLogic" style="display:none;">
        <div class="input-group pull-right" style="display:inline-flex;">
            <span class="input-group-btn">
                <a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure">
                    <i class="fas fa-cogs"></i> {{Configuration avancée}}
                </a>
                <a class="btn btn-sm btn-warning eqLogicAction" data-action="copy">
                    <i class="fas fa-copy"></i> {{Dupliquer}}
                </a>
                <a class="btn btn-sm btn-danger eqLogicAction" data-action="remove">
                    <i class="fas fa-minus-circle"></i> {{Supprimer}}
                </a>
                <a class="btn btn-sm btn-success eqLogicAction roundedRight" data-action="save">
                    <i class="fas fa-check-circle"></i> {{Sauvegarder}}
                </a>
            </span>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation">
                <a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
                    <i class="fas fa-arrow-circle-left"></i>
                </a>
            </li>
            <li role="presentation" class="active">
                <a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
                    <i class="fas fa-tachometer-alt"></i> {{Equipement}}
                </a>
            </li>
            <li role="presentation">
                <a href="#postittab" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="fas fa-sticky-note"></i> {{Post-it}}
                </a>
            </li>
            <li role="presentation">
                <a href="#designtab" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="fas fa-thumbtack"></i> {{Collage Design}}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br>
                <form class="form-horizontal">
                    <fieldset>
                        <input type="hidden" class="eqLogicAttr" data-l1key="id">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du post-it}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (jeeObject::all() as $object) {
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-9">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '"> {{' . $value['name'] . '}}';
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked> {{Activer}}
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked> {{Visible}}
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="postittab">
                <br>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Titre affiché}}</label>
                            <div class="col-sm-5">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_title" placeholder="{{Courses, Message, Rappel...}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Message}}</label>
                            <div class="col-sm-7">
                                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_message" rows="6" placeholder="{{Texte du post-it}}"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Couleur}}</label>
                            <div class="col-sm-3">
                                <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_color" value="#fff475">
                            </div>
                            <div class="col-sm-6">
                                <a class="btn btn-default btn-xs postitColor" data-color="#fff475">Jaune</a>
                                <a class="btn btn-default btn-xs postitColor" data-color="#b8f7b1">Vert</a>
                                <a class="btn btn-default btn-xs postitColor" data-color="#ffd1dc">Rose</a>
                                <a class="btn btn-default btn-xs postitColor" data-color="#bde7ff">Bleu</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Largeur}}</label>
                            <div class="col-sm-2">
                                <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_width" value="220">
                            </div>

                            <label class="col-sm-1 control-label">{{Hauteur}}</label>
                            <div class="col-sm-2">
                                <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_height" value="160">
                            </div>

                            <label class="col-sm-1 control-label">{{Rotation}}</label>
                            <div class="col-sm-2">
                                <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="postit_rotate" value="-1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Rotation visuelle}}</label>
                            <div class="col-sm-5">
                                <input type="range" id="postitdesign_rotate_range" class="form-control" min="-15" max="15" step="1" value="-1">
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-default btn-sm postitdesignRotateQuick" data-rotate="-5">-5°</a>
                                <a class="btn btn-default btn-sm postitdesignRotateQuick" data-rotate="0">0°</a>
                                <a class="btn btn-default btn-sm postitdesignRotateQuick" data-rotate="5">+5°</a>
                            </div>
                        </div>

                        <div class="alert alert-info">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Visuel}}</label>
                            <div class="col-sm-4">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="visual_style">
                                    <option value="classic">{{Classic}}</option>
                                    <option value="paper">{{Paper}}</option>
                                    <option value="tape">{{Tape}}</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <span class="help-block">{{Style visuel du post-it sur le Design.}}</span>
                            </div>
                        </div>

                            {{Aperçu dynamique en dessous : tu peux redimensionner le post-it par les coins et le faire tourner avec la poignée au-dessus.}}
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Aperçu dynamique}}</label>
                            <div class="col-sm-8">
                                <div class="postitdesign-preview-wrap">
                                    <div id="postitdesign_live_preview" class="postitdesign-live-preview">
                                        <div class="postitdesign-rotate-handle" title="{{Tourner le post-it}}"></div>
                                        <div class="postitdesign-live-preview-title">Titre</div>
                                        <div class="postitdesign-live-preview-message">Ton message ici</div>
                                    </div>
                                </div>
                                <div class="help-block">{{Prends le post-it par un coin pour le redimensionner.}}</div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <div role="tabpanel" class="tab-pane" id="designtab">
                <br>
                <form class="form-horizontal">
                    <fieldset>
                        <legend><i class="fas fa-thumbtack"></i> {{Coller ce post-it sur un Design}}</legend>

                        <?php if (count($planHeaders) == 0) { ?>
                            <div class="alert alert-warning">
                                {{Aucun Design Jeedom n’existe encore. Crée un Design ici, puis colle le post-it dessus.}}
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Créer un Design}}</label>
                            <div class="col-sm-4">
                                <input type="text" id="postitdesign_new_design_name" class="form-control" placeholder="{{Exemple : Frigo, Cuisine, Tablette}}">
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-primary" id="bt_postitdesign_create_design">
                                    <i class="fas fa-plus-circle"></i> {{Créer}}
                                </a>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Design cible}}</label>
                            <div class="col-sm-5">
                                <select class="eqLogicAttr form-control" id="postitdesign_target_planHeader_id" data-l1key="configuration" data-l2key="target_planHeader_id">
                                    <option value="">{{Choisir un Design}}</option>
                                    <?php
                                    foreach ($planHeaders as $planHeader) {
                                        if (method_exists($planHeader, 'hasRight') && !$planHeader->hasRight('r')) {
                                            continue;
                                        }
                                        echo '<option value="' . $planHeader->getId() . '">' . $planHeader->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Position X}}</label>
                            <div class="col-sm-2">
                                <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="target_x" value="30">
                            </div>

                            <label class="col-sm-1 control-label">{{Position Y}}</label>
                            <div class="col-sm-2">
                                <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="target_y" value="30">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-7">
                                <a class="btn btn-primary btn-lg" id="bt_postitdesign_open_placer">
                                    <i class="fas fa-mouse-pointer"></i> {{Placement dynamique}}
                                </a>
                                <a class="btn btn-success btn-lg" id="bt_postitdesign_stick_design">
                                    <i class="fas fa-thumbtack"></i> {{Coller sur ce Design}}
                                </a>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            {{Utilisation simple : écris ton post-it, sauvegarde, choisis le Design, puis clique sur Coller.}}
                        </div>

                        <pre id="postitdesign_design_result" style="margin-top:15px;display:none;"></pre>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'postitdesign', 'css', 'postitdesign'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
<?php include_file('desktop', 'postitdesign', 'js', 'postitdesign'); ?>

<script>
/* POSTITDESIGN LIVE VISUAL PREVIEW FIX V15 START */
(function () {
  "use strict";

  if (window.__postitdesignPreviewV15) return;
  window.__postitdesignPreviewV15 = true;

  function one(sel, root) {
    return (root || document).querySelector(sel);
  }

  function all(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }

  function getField(keys, fallback) {
    for (var i = 0; i < keys.length; i++) {
      var k = keys[i];
      var el =
        one('[data-l2key="' + k + '"]') ||
        one('[data-l1key="' + k + '"]') ||
        one('[name="' + k + '"]') ||
        one('[name*="' + k + '"]') ||
        one('#' + k);

      if (el && el.value !== undefined && String(el.value).trim() !== "") {
        return el.value;
      }
    }
    return fallback || "";
  }

  function esc(v) {
    return String(v || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function styleName(v) {
    v = String(v || "").toLowerCase();
    if (v.indexOf("paper") !== -1 || v.indexOf("papier") !== -1) return "paper";
    if (v.indexOf("tape") !== -1 || v.indexOf("scotch") !== -1) return "tape";
    return "classic";
  }

  function visualSelect() {
    var byKey =
      one('select[data-l2key="visual_style"]') ||
      one('select[data-l1key="visual_style"]') ||
      one('select[name*="visual_style"]');

    if (byKey) return byKey;

    var selects = all("select");
    for (var i = 0; i < selects.length; i++) {
      var txt = (selects[i].textContent || "").toLowerCase();
      var meta = ((selects[i].id || "") + " " + (selects[i].name || "") + " " + (selects[i].className || "")).toLowerCase();
      if (txt.indexOf("classic") !== -1 && txt.indexOf("paper") !== -1 && txt.indexOf("tape") !== -1) return selects[i];
      if (meta.indexOf("visual") !== -1 || meta.indexOf("visuel") !== -1) return selects[i];
    }

    return null;
  }

  function previewContainer() {
    var old = one("#postitdesign_preview_v15");
    if (old) return old;

    var labels = all("label,td,th,span,div").filter(function (el) {
      var t = (el.textContent || "").toLowerCase();
      return t.indexOf("aperçu dynamique") !== -1 || t.indexOf("apercu dynamique") !== -1;
    });

    var anchor = labels.length ? labels[labels.length - 1] : null;
    var row = anchor ? (anchor.closest(".form-group") || anchor.closest(".row") || anchor.parentNode) : null;

    var box = document.createElement("div");
    box.id = "postitdesign_preview_v15";
    box.style.margin = "14px 0 24px 0";
    box.style.minHeight = "240px";
    box.style.display = "flex";
    box.style.justifyContent = "center";
    box.style.alignItems = "flex-start";
    box.style.pointerEvents = "none";

    if (row && row.parentNode) {
      var next = row.nextSibling;
      if (next && next.nodeType === 1) {
        next.parentNode.insertBefore(box, next.nextSibling);
      } else {
        row.parentNode.appendChild(box);
      }
    } else {
      var sel = visualSelect();
      var parent = sel ? (sel.closest(".eqLogicAttr") || sel.closest(".form-group") || sel.parentNode) : null;
      if (parent && parent.parentNode) {
        parent.parentNode.appendChild(box);
      } else {
        document.body.appendChild(box);
      }
    }

    return box;
  }

  function hideOldPreview() {
    var box = one("#postitdesign_preview_v15");
    all("[id*=preview], [class*=preview], [id*=Preview], [class*=Preview]").forEach(function (el) {
      if (box && (el === box || box.contains(el) || el.contains(box))) return;
      var t = (el.textContent || "").toLowerCase();
      var html = (el.innerHTML || "").toLowerCase();
      if (
        t.indexOf("aperçu dynamique") === -1 &&
        t.indexOf("apercu dynamique") === -1 &&
        html.indexOf("postit") === -1 &&
        html.indexOf("rotate") === -1
      ) {
        return;
      }
      if (el.id === "postitdesign_preview_v15") return;
      el.style.display = "none";
    });
  }

  function render() {
    var style = visualSelect() ? styleName(visualSelect().value) : styleName(getField(["visual_style"], "classic"));
    var title = getField(["postit_title", "name"], "Post-it");
    var msg = getField(["postit_message"], "");
    var color = getField(["postit_color"], "#fff475");
    var width = parseInt(getField(["postit_width"], "220"), 10);
    var height = parseInt(getField(["postit_height"], "160"), 10);
    var rotate = parseInt(getField(["postit_rotate"], "0"), 10);

    if (isNaN(width) || width < 120) width = 220;
    if (isNaN(height) || height < 90) height = 160;
    if (isNaN(rotate)) rotate = 0;
    if (rotate > 15) rotate = 15;
    if (rotate < -15) rotate = -15;

    var tape = "";
    var fold = "";
    var lines = "";
    var radius = "6px";
    var shadow = "0 12px 24px rgba(0,0,0,.22)";

    if (style === "classic") {
      fold = '<div style="position:absolute;right:0;top:0;width:0;height:0;border-top:30px solid rgba(255,255,255,.72);border-left:30px solid rgba(0,0,0,.13);"></div>';
    }

    if (style === "paper") {
      radius = "3px";
      shadow = "0 5px 14px rgba(0,0,0,.18)";
      lines = '<div style="position:absolute;left:18px;right:18px;top:58px;bottom:18px;background:repeating-linear-gradient(to bottom, transparent 0, transparent 22px, rgba(0,0,0,.14) 23px);"></div>';
    }

    if (style === "tape") {
      tape = '<div style="position:absolute;left:50%;top:-14px;width:64px;height:26px;margin-left:-32px;background:rgba(220,220,220,.8);box-shadow:0 1px 4px rgba(0,0,0,.18);transform:rotate(-3deg);border-radius:2px;"></div>';
    }

    hideOldPreview();

    previewContainer().innerHTML =
      '<div style="' +
      'position:relative;' +
      'width:' + width + 'px;' +
      'height:' + height + 'px;' +
      'background:' + esc(color) + ';' +
      'transform:rotate(' + rotate + 'deg);' +
      'box-shadow:' + shadow + ';' +
      'border-radius:' + radius + ';' +
      'padding:18px 22px;' +
      'box-sizing:border-box;' +
      'font-family:Arial,sans-serif;' +
      'color:#111;' +
      'overflow:hidden;' +
      '">' +
      tape + fold + lines +
      '<div style="position:relative;z-index:2;font-weight:bold;font-size:17px;margin-bottom:12px;border-bottom:1px solid rgba(0,0,0,.16);padding-bottom:8px;">' + esc(title) + '</div>' +
      '<div style="position:relative;z-index:2;white-space:pre-wrap;font-size:15px;line-height:1.35;">' + esc(msg) + '</div>' +
      '</div>';
  }

  function bindOnce(el) {
    if (!el || el.__postitPreviewV15Bound) return;
    el.__postitPreviewV15Bound = true;
    el.addEventListener("change", function () { setTimeout(render, 0); }, true);
    el.addEventListener("input", function () { setTimeout(render, 0); }, true);
    el.addEventListener("keyup", function () { setTimeout(render, 0); }, true);
  }

  function bind() {
    all("input, textarea, select").forEach(function (el) {
      var meta = (
        (el.getAttribute("data-l2key") || "") + " " +
        (el.getAttribute("data-l1key") || "") + " " +
        (el.name || "") + " " +
        (el.id || "") + " " +
        (el.textContent || "")
      ).toLowerCase();

      if (
        meta.indexOf("postit") !== -1 ||
        meta.indexOf("visual") !== -1 ||
        meta.indexOf("visuel") !== -1 ||
        meta.indexOf("classic") !== -1 ||
        meta.indexOf("paper") !== -1 ||
        meta.indexOf("tape") !== -1
      ) {
        bindOnce(el);
      }
    });

    bindOnce(visualSelect());
  }

  function boot() {
    bind();
    render();
    setTimeout(function () { bind(); render(); }, 400);
    setTimeout(function () { bind(); render(); }, 1200);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
/* POSTITDESIGN LIVE VISUAL PREVIEW FIX V15 END */
</script>

