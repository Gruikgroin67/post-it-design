# Changelog - Post-it Design

## v1.0.5 - Correction tactile tablette

Version de correction centrée sur l’utilisation dans les Designs Jeedom depuis une tablette tactile.

### Corrigé

- Déplacement tactile plus fluide des post-it sur un Design.
- Correction du problème des boutons tactiles qui répondaient mal sur tablette.
- Correction du bouton de rotation : la rotation ne reste plus bloquée en mode maintien.
- La rotation se fait maintenant par appui simple, par pas de 5 degrés.
- Le bouton Décoller reste utilisable sans supprimer l’équipement.
- Le post-it reste au-dessus des autres éléments du Design sans casser sa position Jeedom.

### Technique

- Abandon du chargement JavaScript externe pour la gestion tactile, car certains Jeedom peuvent retourner un accès `403` sur les fichiers JS du plugin depuis le Design.
- La gestion tactile critique est maintenant injectée directement dans le rendu HTML du widget.
- Conservation des appels AJAX internes :
  - `savePositionFromDesign`
  - `saveRotationFromDesign`

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
