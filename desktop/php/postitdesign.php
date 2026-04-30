<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

sendVarToJS('eqType', 'postitdesign');
$eqLogics = eqLogic::byType('postitdesign');
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
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br>
                <form class="form-horizontal">
                    <fieldset>
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

                        <div class="alert alert-info">
                            {{Après sauvegarde, ajoute cet équipement sur un Design Jeedom comme n’importe quel widget.}}
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'postitdesign', 'css', 'postitdesign'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
<?php include_file('desktop', 'postitdesign', 'js', 'postitdesign'); ?>
