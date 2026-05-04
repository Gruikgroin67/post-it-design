# Commande  dans un Design Jeedom

Version documentée : v1.0.26

## Fonction validée

Le plugin  permet d’installer une vraie commande Jeedom  dans un Design depuis l’interface du plugin.

La commande sert à créer un nouveau vrai post-it directement dans le Design sélectionné.

## Utilisation

1. Ouvrir la page du plugin .
2. Aller dans le bloc .
3. Choisir le Design cible.
4. Cliquer sur .
5. Ouvrir le Design.
6. Faire Ctrl+F5.
7. Déplacer la commande  si nécessaire.
8. Cliquer sur  pour créer un post-it.

## Architecture retenue

Les vrais post-it sont isolés du  natif de Jeedom.

Ils ne portent pas la classe , car cette classe provoquait une resauvegarde globale des positions quand un autre widget du Design était déplacé.

Les vrais post-it conservent la classe .

La commande  est séparée des vrais post-it et ne doit pas utiliser .

## État visuel actuel

- Commande  rendue comme mini post-it.
- Styles , ,  disponibles.
- Rendu Design appliqué.
- Aperçu dynamique renforcé.
- Rotation par raccourcis , , .
- Bouton rotation Design : cycle .
- Rotation conservée après actualisation.

## Limite actuelle

Le clic rotation peut encore présenter un léger délai visuel avant resynchronisation, mais la valeur sauvegardée est correcte après actualisation du Design.

## Règles à conserver

- Ne pas toucher MQTT.
- Ne pas redémarrer Apache sans nécessité.
- Ne pas toucher PROD sans demande explicite.
- Ne pas réintroduire  sur les vrais post-it.
- Ne pas masquer le contrôleur  avec le précédent patch qui avait blanchi la page plugin.

## v1.0.33 - Pastille de menu

Le clic principal sur `+ Post-it` crée toujours un nouveau post-it. Une petite pastille intégrée au bouton affiche un menu d’options. Ce menu permet de masquer ou réafficher les post-it du Design sans supprimer les équipements ni les lignes `plan`.

Règles techniques validées :

- ne pas remplacer le rendu natif complet de la commande Jeedom ;
- ne pas utiliser clic droit, double-clic ou appui long comme déclencheur principal ;
- ne pas utiliser `position:fixed` pour le menu, car il sort du Design ;
- garder le menu borné localement au conteneur du bouton ;
- conserver la création immédiate sur clic principal ;
- utiliser une pastille visible comme déclencheur fiable.

## v1.0.34 - Masquage sans rechargement

La pastille du bouton `+ Post-it` masque ou réaffiche les post-it sans recharger le Design. L’action reste sauvegardée côté plugin par AJAX.

Règles techniques validées :

- le clic principal du bouton `+ Post-it` reste réservé à la création immédiate ;
- la pastille reste le déclencheur du menu ;
- le menu reste cantonné au Design ;
- les post-it ne sont pas supprimés du plan ;
- le rendu ne doit pas utiliser `window.location.reload()` pour masquer/réafficher ;
- les post-it restent dans le DOM et basculent en `display:none`.
