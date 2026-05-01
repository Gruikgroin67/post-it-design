# Changelog - Post-it Design

## v1.0.8 - Post-it au-dessus sans casser le Design

### Corrigé

- Le post-it passe au-dessus des widgets Jeedom voisins.
- Le calque est limité au widget post-it et à son parent Jeedom direct.
- Le Design complet n’est plus impacté par un calque global.
- Le déplacement reste disponible depuis le post-it.
- Les boutons du post-it restent accessibles.
- Les widgets Jeedom autour restent visibles et cliquables.
- Conservation de la rotation par appui simple.
- Conservation du décollage sans suppression de l’équipement.
- Documentation, README, fichiers Market et fichier d’information projet mis à jour.

### Technique

- Utilisation du correctif local `postitdesign-safe-top-layer`.
- Z-index limité au post-it et à son parent direct.
- Pas de bloc tactile global `postitdesign-touch-inline-v*`.
- Pas de z-index extrême.
- Pas de modification globale du Design.
- Tag créé depuis l’état DEV courant.
- PROD non modifiée par ce tag.

## v1.0.7 - Documentation finale et aperçu visuel

- Documentation principale mise à jour.
- Changelog public mis à jour.
- README mis à jour.
- Fichiers Market mis à jour.
- Documentation de l’aperçu dynamique du champ `Visuel`.
- Conservation des corrections tactiles.
- Conservation de la rotation par appui simple sur le bouton `⟳`.

## v1.0.6 - Documentation et aperçu visuel

- Mise à jour de la documentation.
- Mise à jour du README.
- Mise à jour des fichiers Market.
- Mention de l’aperçu dynamique du champ `Visuel`.

## v1.0.5 - Correction tactile tablette

- Déplacement tactile plus fluide des post-it sur un Design.
- Correction du comportement des boutons tactiles sur tablette.
- Correction de la rotation qui pouvait rester active.
- Rotation par appui simple sur le bouton `⟳`.
- Un appui sur `⟳` applique une rotation de 5 degrés.
- Le bouton `✕` permet de décoller le post-it sans supprimer l’équipement.
- La gestion tactile principale est intégrée directement dans le rendu HTML du widget.
- Conservation des actions AJAX internes `savePositionFromDesign` et `saveRotationFromDesign`.

## v1.0.4 - Version stable Market

- Documentation Market corrigée.
- GitHub Pages opérationnel.
- Documentation française publiée.
- Changelog publié.
- Préparation des fichiers Market.

## v1.0.3

- Amélioration de l’aperçu du style visuel.
- Conservation de la couleur choisie dans les différents styles.
- Suppression du double aperçu.

## v1.0.2

- Ajout des styles visuels Classic, Paper et Tape.
- Amélioration du rendu dans les Designs.

## v1.0.1

- Correction du placement sur Design.
- Sauvegarde position X/Y.
- Ajout des options de modification depuis le Design.

## v1.0.0

- Première version publique du plugin Post-it Design.

Dernière mise à jour : 20260501_130525
