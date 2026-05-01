# Post-it Design

Post-it Design est un plugin Jeedom permettant d’afficher des post-it directement sur les Designs Jeedom.

## Version actuelle

`v1.0.7`

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

## Rotation

Chaque appui sur `⟳` tourne le post-it de 5 degrés.

## Aperçu visuel

L’aperçu dynamique du champ `Visuel` est conservé.

Le changement entre Classic, Paper et Tape doit se voir directement dans l’aperçu de la page du plugin.

## Notes tablette

La gestion tactile améliore le déplacement au doigt.

La gestion tactile principale est intégrée directement dans le rendu du widget afin d’éviter les erreurs `403` sur les fichiers JavaScript externes selon les installations Jeedom.

## Changelog

[Changelog Post-it Design](./changelog/)

Dernière mise à jour : 20260501_123004
