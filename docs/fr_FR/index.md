# Post-it Design

Post-it Design est un plugin Jeedom permettant d’afficher des post-it directement sur les Designs Jeedom.

Il permet d’ajouter des notes visuelles sur une tablette murale, un écran de supervision ou un Design personnel, comme des post-it collés sur un tableau.

## Fonctions principales

- Création d’un post-it depuis Jeedom.
- Affichage du post-it sur un Design.
- Déplacement visuel du post-it.
- Sauvegarde de la position X/Y.
- Rotation du post-it.
- Modification du contenu depuis le Design.
- Décollage du post-it du Design sans supprimer l’équipement.
- Options masquées par défaut pour garder un Design propre.
- Styles visuels sélectionnables :
  - Classic
  - Paper
  - Tape

## Utilisation

Créez un équipement Post-it Design, renseignez son titre, son message, sa couleur et ses dimensions, puis collez-le sur un Design Jeedom.

Une fois le post-it affiché sur le Design, vous pouvez le déplacer directement à la souris ou au doigt sur tablette.

Un appui simple sur le post-it affiche les options.

## Boutons disponibles sur le Design

- `+` : créer un nouveau post-it sur le Design.
- `✎` : compléter ou modifier le post-it.
- `⟳` : tourner le post-it par pas de 5 degrés.
- `✕` : décoller le post-it du Design.

Le bouton `✕` ne supprime pas l’équipement Jeedom. Il retire uniquement le post-it du Design.

## Notes tablette

Depuis la version `v1.0.5`, la gestion tactile a été corrigée pour améliorer le déplacement au doigt et éviter le blocage de la rotation.

Sur tablette, il est conseillé de recharger complètement le Design après une mise à jour du plugin.

## Documentation

- Changelog : `docs/fr_FR/changelog.md`
- Dépôt GitHub : `Gruikgroin67/post-it-design`
