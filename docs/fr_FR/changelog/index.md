# Changelog - Post-it Design

## v1.0.7 - Documentation finale et aperçu visuel

### Corrigé

- Documentation principale mise à jour.
- Changelog public mis à jour.
- README mis à jour.
- Fichiers Market mis à jour.
- Fichier d’information projet mis à jour.
- Synchronisation de `docs/fr_FR/changelog.md` et `docs/fr_FR/changelog/index.md`.
- Documentation de l’aperçu dynamique du champ `Visuel`.
- Conservation des corrections tactiles.
- Conservation de la rotation par appui simple sur le bouton `⟳`.

### Technique

- Tag Git créé depuis l’état DEV courant.
- La PROD n’a pas été modifiée.

## v1.0.6 - Documentation et aperçu visuel

### Corrigé

- Mise à jour de la documentation.
- Mise à jour du README.
- Mise à jour des fichiers Market.
- Mention de l’aperçu dynamique du champ `Visuel`.

## v1.0.5 - Correction tactile tablette

### Corrigé

- Déplacement tactile plus fluide des post-it sur un Design.
- Correction du comportement des boutons tactiles sur tablette.
- Correction de la rotation qui pouvait rester active.
- Rotation par appui simple sur le bouton `⟳`.
- Un appui sur `⟳` applique une rotation de 5 degrés.
- Le bouton `✕` permet de décoller le post-it sans supprimer l’équipement.
- Le post-it reste au-dessus des autres éléments du Design sans casser sa position Jeedom.

### Technique

- Certains contextes Jeedom peuvent retourner une erreur `403` sur les fichiers JavaScript externes d’un plugin.
- La gestion tactile principale est donc intégrée directement dans le rendu HTML du widget.
- Conservation des actions AJAX internes :
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

Dernière mise à jour : 20260501_123004
