# Post-it Design

Post-it Design est un plugin Jeedom permettant d’ajouter des post-it directement dans les Designs Jeedom.

Il sert à afficher des notes visuelles, rappels, consignes ou informations rapides sur un Design, avec une présentation proche d’un post-it physique.

## Version actuelle

`v1.0.40`

## Présentation

Le plugin permet de créer des post-it personnalisables puis de les placer dans un Design Jeedom.

Chaque post-it peut avoir un titre, un contenu, une couleur, un style visuel, une rotation et une priorité. Les actions principales sont disponibles directement depuis le Design.

## Fonctions principales

- Création d’un post-it depuis Jeedom.
- Ajout d’un post-it sur un Design.
- Commande `+ Post-it` installable dans un Design.
- Création rapide d’un nouveau post-it depuis le Design.
- Déplacement visuel du post-it.
- Sauvegarde de la position.
- Modification du titre depuis le Design.
- Modification du contenu depuis le Design.
- Choix rapide de la couleur.
- Choix du style visuel : `Classic`, `Paper`, `Tape`.
- Priorité visuelle : `Normal`, `Important`, `Urgent`.
- Rotation du post-it.
- Masquage et réaffichage des post-it d’un Design.
- Décollage du Design sans suppression de l’équipement.
- Utilisation adaptée aux écrans tactiles.

## Création d’un post-it

Depuis la page du plugin, créez un équipement Post-it Design.

Renseignez au minimum :

- le titre ;
- le message ;
- la couleur ;
- les dimensions souhaitées.

Le post-it peut ensuite être affiché dans un Design Jeedom.

## Utilisation dans un Design

Une fois affiché sur le Design, le post-it peut être déplacé et modifié.

Les repères discrets placés dans les coins du post-it permettent d’accéder aux actions sans surcharger l’affichage.

Actions disponibles :

- déplacer le post-it ;
- ouvrir les options ;
- modifier le titre ;
- modifier le contenu ;
- changer la couleur ;
- changer le style visuel ;
- changer la priorité ;
- tourner le post-it ;
- décoller le post-it du Design.

Le décollage retire uniquement le post-it du Design. L’équipement Jeedom n’est pas supprimé.

## Commande `+ Post-it`

Le plugin peut installer une commande `+ Post-it` dans un Design.

Cette commande sert à créer rapidement un nouveau post-it depuis le Design.

La commande est séparée des vrais post-it. Elle peut être déplacée comme un élément Jeedom du Design et ne modifie pas les post-it existants.

## Couleurs rapides

Depuis les options du post-it, il est possible de choisir rapidement une couleur.

La couleur est appliquée immédiatement à l’affichage du post-it puis sauvegardée.

## Styles visuels

Trois styles sont disponibles :

- `Classic` : rendu simple et lisible ;
- `Paper` : rendu papier ;
- `Tape` : rendu avec effet visuel de note collée.

Le style peut être changé depuis les options du post-it.

## Priorités

Chaque post-it peut recevoir une priorité :

- `Normal` : affichage standard ;
- `Important` : contour orange ;
- `Urgent` : contour rouge.

La priorité permet de rendre certaines notes plus visibles dans le Design.

## Masquer ou réafficher les post-it

Depuis la commande `+ Post-it`, un menu permet de masquer ou réafficher les post-it du Design.

Le masquage est visuel et non destructif. Les post-it restent conservés avec leurs positions et leurs réglages.

## Utilisation tactile

Le plugin est prévu pour être utilisé sur ordinateur, tablette ou écran tactile.

Les boutons principaux des options utilisent une gestion adaptée aux événements tactiles afin de limiter les doubles déclenchements et les pertes de clic dans les Designs Jeedom.

## Bonnes pratiques

Pour une utilisation confortable :

- éviter les post-it trop petits si l’écran est tactile ;
- conserver un espace libre autour des notes importantes ;
- utiliser les priorités pour distinguer les informations critiques ;
- utiliser le masquage lorsque le Design doit rester plus lisible.

## Changelog

[Changelog Post-it Design](./changelog/)

Dernière mise à jour documentation : 2026-05-31.
