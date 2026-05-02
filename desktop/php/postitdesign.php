<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('postitdesign');
sendVarToJS('eqType', 'postitdesign');
$eqLogics = eqLogic::byType('postitdesign');
?>

<div id="postitdesign_admin_root" class="postitdesign-admin-root">
    <div class="row row-overflow">
        <div class="col-xs-12 eqLogicThumbnailDisplay">
            <legend><i class="fas fa-sticky-note"></i> {{Post-it Design}}</legend>

            <div class="eqLogicThumbnailContainer">
                <div class="cursor eqLogicAction logoPrimary" data-action="add">
                    <i class="fas fa-plus-circle"></i>
                    <br>
                    <span>{{Ajouter un post-it}}</span>
                </div>

                <?php foreach ($eqLogics as $eqLogic) { ?>
                    <div class="eqLogicDisplayCard cursor" data-eqLogic_id="<?php echo $eqLogic->getId(); ?>">
                        <i class="fas fa-sticky-note"></i>
                        <br>
                        <span class="name"><?php echo $eqLogic->getHumanName(true, true); ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-xs-12 eqLogic" style="display:none;">
            <div class="input-group pull-right">
                <span class="input-group-btn">
                    <a class="btn btn-sm btn-default eqLogicAction" data-action="configure">
                        <i class="fas fa-cogs"></i> {{Configuration avancée}}
                    </a>
                    <a class="btn btn-sm btn-success eqLogicAction" data-action="save">
                        <i class="fas fa-check-circle"></i> {{Sauvegarder}}
                    </a>
                    <a class="btn btn-sm btn-danger eqLogicAction" data-action="remove">
                        <i class="fas fa-minus-circle"></i> {{Supprimer}}
                    </a>
                </span>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#postitdesign_tab_eqlogic" aria-controls="postitdesign_tab_eqlogic" role="tab" data-toggle="tab">
                        <i class="fas fa-tachometer-alt"></i> {{Equipement}}
                    </a>
                </li>
                <li role="presentation">
                    <a href="#postitdesign_tab_postit" aria-controls="postitdesign_tab_postit" role="tab" data-toggle="tab">
                        <i class="fas fa-sticky-note"></i> {{Post-it}}
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="postitdesign_tab_eqlogic">
                    <br>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Nom}}</label>
                        <div class="col-sm-5">
                            <input type="text" class="eqLogicAttr form-control" data-l1key="name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Objet parent}}</label>
                        <div class="col-sm-5">
                            <select class="eqLogicAttr form-control" data-l1key="object_id">
                                <option value="">{{Aucun}}</option>
                                <?php foreach (jeeObject::all() as $object) { ?>
                                    <option value="<?php echo $object->getId(); ?>"><?php echo $object->getName(); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Catégorie}}</label>
                        <div class="col-sm-9">
                            <?php foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) { ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="<?php echo $key; ?>">
                                    {{<?php echo $value['name']; ?>}}
                                </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Options}}</label>
                        <div class="col-sm-9">
                            <label class="checkbox-inline">
                                <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>
                                {{Activer}}
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>
                                {{Visible}}
                            </label>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id="postitdesign_tab_postit">
                    <br>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Titre affiché}}</label>
                        <div class="col-sm-5">
                            <input type="text" class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_title" placeholder="{{Titre}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Message}}</label>
                        <div class="col-sm-5">
                            <textarea class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_message" rows="5" placeholder="{{Message}}"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Couleur}}</label>
                        <div class="col-sm-3">
                            <input type="color" class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_color" value="#fff475">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Largeur}}</label>
                        <div class="col-sm-2">
                            <input type="number" min="120" max="600" class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_width" value="220">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Hauteur}}</label>
                        <div class="col-sm-2">
                            <input type="number" min="80" max="500" class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_height" value="160">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Rotation}}</label>
                        <div class="col-sm-2">
                            <input type="number" min="-10" max="10" class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="postit_rotate" value="-1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Visuel}}</label>
                        <div class="col-sm-3">
                            <select class="eqLogicAttr form-control postitdesign-preview-field" data-l1key="configuration" data-l2key="visual_style">
                                <option value="classic">{{Classic}}</option>
                                <option value="paper">{{Paper}}</option>
                                <option value="tape">{{Tape}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Aperçu}}</label>
                        <div class="col-sm-6">
                            <div class="postitdesign-admin-preview">
                                <div class="postitdesign-widget postitdesign-style-classic" style="width:220px;min-height:160px;--postitdesign-bg:#fff475;--postitdesign-rotate:-1deg;">
                                    <div class="postitdesign-note">
                                        <div class="postitdesign-title">Titre</div>
                                        <div class="postitdesign-message">Message</div>
                                    </div>
                                </div>
                            </div>
                            <p class="help-block">
                                {{Ajout au Design à faire avec les mécanismes natifs Jeedom. Le plugin ne modifie pas les Designs directement.}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
