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
