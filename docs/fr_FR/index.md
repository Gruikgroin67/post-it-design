# Post-it Design

Post-it Design est un plugin Jeedom permettant d’afficher des post-it directement sur les Designs Jeedom.

## Version actuelle

`v1.0.27`

## Fonctions principales

- Création d’un post-it depuis Jeedom.
- Affichage du post-it sur un Design.
- Déplacement direct à la souris ou au doigt.
- Sauvegarde automatique de la position X/Y.
- Rotation du post-it par appui simple.
- Modification du contenu depuis le Design.
- Décollage du post-it du Design sans supprimer l’équipement.
- Options masquées par défaut pour garder un Design propre.
- Styles visuels sélectionnables : Classic, Paper, Tape.
- Aperçu dynamique du style visuel dans la page du plugin.
- Passage du post-it au-dessus des widgets Jeedom sans calque global.

## Utilisation sur un Design

Créez un équipement Post-it Design, renseignez son titre, son message, sa couleur et ses dimensions, puis collez-le sur un Design Jeedom.

Une fois affiché sur le Design, le post-it peut être déplacé directement.

Un appui simple sur le post-it affiche les options.

## Boutons disponibles

- `+` : créer un nouveau post-it sur le Design.
- `✎` : compléter ou modifier le post-it.
- `⟳` : tourner le post-it par pas de 5 degrés.
- `✕` : décoller le post-it du Design.

Le bouton `✕` ne supprime pas l’équipement Jeedom. Il retire uniquement le post-it du Design.

## Calque Design

Le post-it passe au-dessus des autres widgets Jeedom sans créer de calque global.

Le correctif relève seulement le widget post-it et son parent Jeedom direct.

Le reste du Design n’est pas modifié.

## Déplacement

Le déplacement est local au post-it.

Aucun écouteur global permanent n’est installé sur tout le document.

## Changelog

[Changelog Post-it Design](./changelog/)

Dernière mise à jour : 20260501_130525
