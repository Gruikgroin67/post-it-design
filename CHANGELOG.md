# Changelog








## v1.0.35 - 2026-05-04

### Modification du titre depuis les options du post-it

- Ajout d’un bouton `Titre` directement dans les options du post-it.
- Le titre peut être modifié sans double-clic.
- Le double-clic reste éventuellement en secours, mais n’est plus la méthode normale.
- Correction adaptée aux Designs Jeedom, où le double-clic, clic droit, appui long ou tap spécial ne sont pas fiables.
- Le bouton utilise l’action AJAX existante `setTitleFromDesign`.
- Le titre est mis à jour immédiatement dans le post-it, puis sauvegardé côté plugin.
- MQTT non touché.
- Apache non redémarré.

## v1.0.34 - 2026-05-04

### Masquer/réafficher sans rechargement du Design

- Le bouton `+ Post-it` conserve son clic principal : il crée immédiatement un post-it.
- La pastille du bouton ouvre le menu `Masquer/Réafficher`.
- Le masquage/réaffichage est maintenant immédiat côté navigateur, sans `window.location.reload()` pour cette action.
- Les post-it restent dans le DOM et sont masqués/réaffichés via `display:none`, ce qui évite de recharger tout le Design.
- L’état reste sauvegardé côté plugin via AJAX, afin de conserver le choix après rechargement manuel.
- Le menu reste cantonné au Design.
- Le masquage reste non destructif : aucune ligne `plan` n’est supprimée.
- MQTT non touché.
- Apache non redémarré.

## v1.0.33 - 2026-05-04

### Bouton + Post-it : pastille de menu cantonnée au Design

- Le bouton `+ Post-it` garde sa fonction principale : un clic sur le bouton crée immédiatement un post-it.
- Ajout d’une petite pastille intégrée au bouton pour ouvrir un menu d’options.
- La pastille permet de masquer ou réafficher les post-it du Design.
- Le masquage est non destructif : aucune ligne `plan` n’est supprimée, les positions restent conservées.
- Le menu reste cantonné au Design : il n’utilise plus `position:fixed` par rapport à la fenêtre.
- La solution validée utilise un menu `absolute` dans le conteneur du bouton avec bornage local.
- Les pistes non retenues sont documentées : clic droit, double-clic, appui long, remplacement complet du rendu commande, menu global hors Design.
- MQTT non touché.
- Apache non redémarré.

## v1.0.32 - 2026-05-04

### Correction tablette renforcée

- Les boutons Classic, Paper et Tape des options du post-it utilisent maintenant une gestion tactile robuste.
- La méthode retenue utilise onclick, ontouchend et onpointerup en direct avec return false.
- Ajout d’un verrou anti double-déclenchement.
- Cette correction remplace l’approche insuffisante basée principalement sur addEventListener.
- Aucun changement MQTT ni redémarrage Apache.

## v1.0.31 - 2026-05-04

### Correction tablette

- Correction des boutons de choix visuel dans les options du post-it sur tablette.
- Les boutons Classic, Paper et Tape réagissent maintenant aussi aux événements touchend et pointerup.
- Ajout d’un verrou anti double-déclenchement pour éviter les actions doublées sur écran tactile.
- Cause : le clic souris fonctionnait sur PC, mais le clic synthétique tablette pouvait être intercepté par la couche tactile du Design Jeedom.
- Aucun changement MQTT ni redémarrage Apache.

## v1.0.30 - 2026-05-04

### Options depuis le Design

- Ajout du choix du visuel directement depuis les options du post-it.
- Les boutons ,  et  sont disponibles dans le panneau d’options du post-it.
- Le choix est sauvegardé et conservé après actualisation.
- Aucun changement MQTT ni redémarrage Apache.

## v1.0.29 - 2026-05-04

### Interface Design

- Les pastilles d’action situées dans les coins supérieur droit et inférieur droit des post-it sont rendues plus discrètes.
- Taille réduite, opacité diminuée et intégration visuelle plus légère.
- Aucun changement fonctionnel sur la création, le déplacement, les styles ou la rotation.

## v1.0.27 - 2026-05-04

### Préparation Market Jeedom

- Description Market enrichie dans `plugin_info/info.json`.
- Liens documentation et changelog normalisés pour GitHub Pages avec `#language#`.
- README et documentation publique mis à jour.
- Base stable conservée : commande Jeedom `+ Post-it`, styles Classic/Paper/Tape, rotation 0°/+15°/-15° et persistance après actualisation.

## v1.0.26 - 2026-05-03

### État validé avant publication stable

- Base DEV actuelle :  ().
- Commande Jeedom native  fonctionnelle dans un Design.
- La commande peut être installée depuis la page plugin via le bloc .
- La commande est affichée sous forme de mini post-it plutôt qu’un bouton gris.
- Le bloc  reste disponible après suppression d’un post-it sur la page plugin.
- Les vrais post-it restent isolés du  natif Jeedom : déplacer  ne déplace plus .

### Visuels et rotation

- Les styles , ,  sont présents côté interface.
- Le rendu Design applique maintenant les visuels.
- L’aperçu dynamique a été renforcé côté CSS/JS.
- Les raccourcis de rotation sont passés à , , .
- Le bouton rotation du post-it suit le cycle .
- La rotation  et  est conservée après actualisation du Design.

### Limite assumée à ce stade

- Un léger délai visuel peut encore apparaître au clic rotation avant resynchronisation.
- Le comportement est laissé tel quel à la demande utilisateur, car la rotation sauvegardée est correcte après actualisation.

### Sécurité

- Version validée avant publication stable.
- MQTT non touché.
- Apache non redémarré.
- Aucun déploiement PROD effectué.

## v1.0.25 - 2026-05-03

### Ajout
- Ajout d'une vraie commande Jeedom , installable depuis l'interface du plugin dans le Design choisi.
- La commande est visible et déplaçable comme une commande Jeedom native.
- Un clic sur  crée un nouveau vrai post-it dans le Design cible.

### Correction
- Isolation des vrais post-it hors du  natif de Jeedom.
- Les vrais post-it ne portent plus la classe , afin d'éviter qu'un déplacement d'un autre widget du Design, par exemple , ne déplace ou ne resauvegarde les post-it.
- Les vrais post-it conservent leur classe  et leur logique interne : déplacement par poignée, cantonnement, options, édition, rotation et lignes barrées.

### Validation DEV
- Test validé : déplacement de  puis sauvegarde et Ctrl+F5, le post-it  ne bouge plus.
- Test validé : installation de la commande  depuis la page plugin.
- Test validé : commande  visible, déplaçable et fonctionnelle dans le Design.
- MQTT non utilisé, aucun redémarrage Apache requis.
