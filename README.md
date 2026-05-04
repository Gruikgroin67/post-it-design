# Post-it Design

Plugin Jeedom permettant d’afficher des post-it directement sur les Designs Jeedom.

## Version actuelle

`v1.0.34`

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

Le plugin conserve une logique d’affichage isolée : seul le post-it et son parent Jeedom direct sont relevés dans l’ordre d’affichage.

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

Documentation détaillée : voir la documentation GitHub Pages du plugin.

## État v1.0.26

Version stable préparée pour publication sur le Market Jeedom.

Fonctions principales :

- vrais post-it isolés du  Jeedom ;
- commande Jeedom native  installable depuis l’interface plugin ;
- commande visible et déplaçable dans un Design ;
- création de nouveau post-it depuis le Design ;
- rendu de commande en mini post-it ;
- styles , ,  ;
- rotation  ;
- rotation conservée après actualisation.

Documentation détaillée : voir la documentation GitHub Pages du plugin.

Sécurité : pas de dépendances, pas de démon, MQTT non utilisé, aucun redémarrage Apache requis.

## Préparation Market Jeedom

`v1.0.27` nettoie les métadonnées publiques, la documentation et le changelog pour une publication propre sur le Market Jeedom.

## Dernière évolution

Le menu de la pastille du bouton `+ Post-it` permet maintenant de masquer ou réafficher les post-it du Design sans rechargement complet de la page. Le clic principal du bouton continue de créer un post-it immédiatement.
