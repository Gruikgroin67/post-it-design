# Changelog

## v1.0.26 - 2026-05-03

### État DEV validé avant future PROD

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

- PROD non touchée.
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
- PROD non touchée, MQTT non touché, Apache non redémarré.

