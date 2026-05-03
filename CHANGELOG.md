# Changelog

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

