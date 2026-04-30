<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<form class="form-horizontal">
    <fieldset>
        <legend><i class="fas fa-sticky-note"></i> {{Post-it Design}}</legend>
        <div class="alert alert-info">
            {{Ce plugin permet de créer des post-it affichables sur les Designs Jeedom.}}
        </div>
    </fieldset>
</form>
