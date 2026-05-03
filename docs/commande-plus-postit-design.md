# Commande  dans un Design Jeedom

Version : v1.0.25

## Objectif

Le plugin permet d'installer une vraie commande Jeedom  dans un Design depuis l'interface du plugin.

Cette commande sert à créer un nouveau post-it directement dans le Design sélectionné, sans utiliser de shell et sans poser de faux bouton HTML.

## Utilisation

1. Ouvrir la page du plugin .
2. Aller dans le bloc .
3. Choisir le Design cible.
4. Cliquer sur .
5. Ouvrir le Design puis faire Ctrl+F5.
6. Déplacer la commande  si nécessaire.
7. Cliquer sur  pour créer un nouveau post-it.

## Architecture retenue

Les vrais post-it sont isolés du  natif de Jeedom.

Ils ne portent plus la classe , car cette classe provoquait une resauvegarde globale des positions quand un autre widget du Design était déplacé.

Les vrais post-it gardent la classe .

La commande  est séparée des vrais post-it. Elle ne doit jamais utiliser la classe .

## Validation

Tests DEV validés :

- déplacement de  sans déplacement parasite du post-it  ;
- déplacement de la commande  sans déplacement parasite des post-it existants ;
- clic sur  créant un nouveau vrai post-it ;
- fonctions post-it conservées : déplacement par poignée, options, lignes barrées, édition, rotation.

## Règles de sécurité

- Ne pas toucher MQTT.
- Ne pas redémarrer Apache sans nécessité.
- Ne pas toucher PROD sans demande explicite.
- Conserver la base saine  comme référence technique.
