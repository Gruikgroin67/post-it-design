# Post-it Design

Plugin Jeedom permettant d’afficher des post-it directement sur les Designs Jeedom.

## Version actuelle

`v1.0.8`

## Fonctions

- Post-it affichable sur un Design Jeedom.
- Déplacement visuel.
- Sauvegarde de la position X/Y.
- Rotation par appui simple.
- Modification depuis le Design.
- Décollage du Design sans suppression de l’équipement.
- Options masquées par défaut.
- Styles visuels : Classic, Paper, Tape.
- Aperçu dynamique du style visuel.
- Passage du post-it au-dessus des widgets Jeedom sans calque global.

## Note technique

Le post-it doit rester isolé dans son propre widget.

La version `v1.0.8` conserve le correctif local `postitdesign-safe-top-layer` : seul le post-it et son parent Jeedom direct sont relevés dans l’ordre d’affichage.

## Documentation

Documentation GitHub Pages :

https://gruikgroin67.github.io/post-it-design/fr_FR/

Changelog :

https://gruikgroin67.github.io/post-it-design/fr_FR/changelog/

## Dépôt

https://github.com/Gruikgroin67/post-it-design

Dernière mise à jour : 20260501_130525

## Commande  dans un Design

Depuis v1.0.25, le plugin peut installer une vraie commande Jeedom  dans un Design depuis l'interface du plugin.

Principe retenu :

- les vrais post-it sont isolés du  natif Jeedom ;
- les vrais post-it ne portent pas la classe  ;
- la commande  est une commande Jeedom séparée ;
- déplacer un autre widget du Design ne doit pas déplacer les post-it existants.

Documentation détaillée : .
