# Changelog - Post-it Design









## v1.0.36 - 2026-05-04

### Bouton Titre compatible tactile

- Correction du bouton `Titre` dans les options du post-it pour les écrans tactiles.
- Ajout d’une gestion robuste des événements `touchstart`, `touchend`, `pointerdown`, `pointerup`, `mousedown`, `mouseup` et `click`.
- Les événements sont capturés avec arrêt de propagation afin d’éviter que le Design Jeedom intercepte l’action.
- Le titre reste modifiable sans double-clic.
- La sauvegarde continue d’utiliser l’action AJAX `setTitleFromDesign`.
- MQTT non touché.
- Apache non redémarré.

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

## v1.0.28 - 2026-05-04

### Correction documentation publique

- Mise à jour du changelog visible sur GitHub Pages.
- Correction des fichiers  et .
- Aucun changement fonctionnel sur le plugin.
- Version destinée à rendre cohérente la documentation publique avant soumission Market Jeedom.

## v1.0.27 - 2026-05-04

### Préparation Market Jeedom

- Description Market enrichie dans .
- Liens documentation et changelog normalisés pour GitHub Pages.
- README et documentation publique mis à jour.
- Base stable conservée : commande Jeedom , styles Classic/Paper/Tape, rotation 0°/+15°/-15° et persistance après actualisation.

## v1.0.26 - 2026-05-03

### Version stable post-it et commande Design

- Commande Jeedom native  fonctionnelle dans un Design.
- Commande installable depuis la page plugin via le bloc .
- Commande affichée sous forme de mini post-it.
- Bloc  conservé après suppression d’un post-it.
- Vrais post-it isolés du  natif Jeedom.
- Déplacer un autre widget du Design ne déplace plus les post-it existants.
- Styles visuels ,  et  appliqués dans le Design.
- Aperçu dynamique renforcé.
- Raccourcis de rotation , , .
- Bouton rotation Design avec cycle .
- Rotation  et  conservée après actualisation.

### Limite connue

- Un léger délai visuel peut encore apparaître au clic rotation avant resynchronisation.
- La rotation sauvegardée reste correcte après actualisation.

## v1.0.25 - 2026-05-03

### Ajout

- Ajout d’une vraie commande Jeedom , installable depuis l’interface du plugin dans le Design choisi.
- La commande est visible et déplaçable comme une commande Jeedom native.
- Un clic sur  crée un nouveau vrai post-it dans le Design cible.

### Correction

- Isolation des vrais post-it hors du  natif Jeedom.
- Les vrais post-it ne portent plus la classe .
- Les vrais post-it conservent leur classe  et leur logique interne.

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

## v1.0.7 - Documentation finale et aperçu visuel

- Documentation principale mise à jour.
- Changelog public mis à jour.
- README mis à jour.
- Fichiers Market mis à jour.
- Documentation de l’aperçu dynamique du champ .

## v1.0.6 - Documentation et aperçu visuel

- Mise à jour de la documentation.
- Mise à jour du README.
- Mise à jour des fichiers Market.
- Mention de l’aperçu dynamique du champ .

## v1.0.5 - Correction tactile tablette

- Déplacement tactile plus fluide des post-it sur un Design.
- Correction du comportement des boutons tactiles sur tablette.
- Correction de la rotation qui pouvait rester active.
- Rotation par appui simple sur le bouton .
- Le bouton  permet de décoller le post-it sans supprimer l’équipement.

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

Dernière mise à jour : 2026-05-04
