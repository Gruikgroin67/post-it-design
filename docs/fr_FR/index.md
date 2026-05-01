# Post-it Design

## Présentation

Post-it Design est un plugin Jeedom permettant d’ajouter des post-it visuels directement sur les Designs Jeedom.

L’objectif est de retrouver un usage très simple : comme un post-it collé sur un frigo, mais sur un Design Jeedom affiché sur une tablette, un écran mural ou une interface domotique.

Le plugin permet de créer des notes visuelles, de les placer sur un Design, de les déplacer directement depuis le Design, puis de compléter leur contenu sans retourner dans la configuration du plugin.

## Fonctions principales

- Création de post-it comme équipements Jeedom.
- Affichage sur les Designs Jeedom.
- Choix du titre, message, couleur, largeur, hauteur et rotation.
- Aperçu dynamique dans la page du plugin.
- Redimensionnement du post-it depuis l’aperçu.
- Rotation visuelle depuis l’aperçu.
- Collage automatique sur un Design choisi.
- Déplacement direct du post-it depuis le Design.
- Sauvegarde automatique de la position au relâchement de la souris.
- Options masquées par défaut pour garder un rendu propre.
- Affichage des options au clic sur le post-it.
- Ajout de texte directement depuis le Design avec le bouton Compléter.
- Décollage du post-it depuis le Design sans supprimer l’équipement.

## Principe d’utilisation

### 1. Créer un post-it

Aller dans :

`Plugins` → `Post-it Design`

Cliquer sur :

`Ajouter un post-it`

Renseigner au minimum :

- le nom de l’équipement ;
- le titre affiché ;
- le message ;
- la couleur ;
- la largeur ;
- la hauteur ;
- la rotation.

Cliquer ensuite sur :

`Sauvegarder`

### 2. Coller le post-it sur un Design

Ouvrir l’onglet :

`Collage Design`

Choisir le Design cible.

Cliquer sur :

`Coller sur ce Design`

Le post-it est alors ajouté automatiquement au Design sélectionné.

### 3. Déplacer le post-it directement sur le Design

Ouvrir le Design Jeedom.

Cliquer une fois sur le post-it pour afficher ses options.

Cliquer sur l’icône :

`↔`

Déplacer le post-it avec la souris.

Relâcher la souris.

La nouvelle position est sauvegardée automatiquement.

### 4. Compléter le texte depuis le Design

Cliquer sur le post-it pour afficher les options.

Cliquer sur :

`✎`

Saisir le texte à ajouter.

Valider.

Le texte est ajouté au post-it et enregistré dans Jeedom.

### 5. Décoller le post-it du Design

Cliquer sur le post-it pour afficher les options.

Cliquer sur :

`✕`

Confirmer.

Le post-it disparaît du Design, mais l’équipement n’est pas supprimé. Il reste disponible dans le plugin et peut être recollé plus tard.

## Différence entre Décoller et Supprimer

`Décoller` enlève seulement le post-it du Design.

`Supprimer` supprime réellement l’équipement Jeedom depuis la page du plugin.

## Conseils d’usage

Pour une tablette murale ou une interface type frigo, il est conseillé d’utiliser :

- une largeur entre 180 et 320 pixels ;
- une hauteur entre 120 et 260 pixels ;
- une rotation légère entre -8 et +8 degrés ;
- des couleurs claires pour garder une bonne lisibilité.

## Cas d’usage

- Liste de courses.
- Rappels familiaux.
- Notes temporaires.
- Message pour une personne.
- Tâches à faire.
- Information visible sur une tablette domotique.
- Aide-mémoire sur un Design dédié.

## Compatibilité

Ce plugin est prévu pour fonctionner avec les Designs Jeedom classiques.

Il ne nécessite pas de démon.

Il ne nécessite pas d’équipement matériel.

## Sécurité et droits

Le plugin utilise les droits Jeedom de l’utilisateur connecté.

Les actions de modification, déplacement, collage et décollage nécessitent un utilisateur autorisé.

## Plugin non officiel

Post-it Design est un plugin indépendant.

Il n’est pas un plugin officiel Jeedom SAS et n’utilise pas de logo officiel Jeedom.


## Créer un nouveau post-it depuis le Design

Depuis un post-it déjà présent sur un Design :

1. Cliquer sur le post-it pour afficher les options.
2. Cliquer sur le bouton `+`.
3. Saisir le titre.
4. Saisir le message.
5. Choisir la couleur : jaune, vert, rose, bleu ou un code hexadécimal.
6. Choisir la rotation.
7. Valider.

Le nouveau post-it est créé automatiquement sur le même Design, légèrement décalé par rapport au post-it utilisé.

Il peut ensuite être déplacé directement avec le bouton `↔`.

## Modifier la rotation depuis le Design

Depuis les options du post-it, cliquer sur `⟳`, saisir une rotation entre -15 et +15 degrés, puis valider.
