# Checklist publication Market - Post-it Design

## Identité du plugin

- ID plugin : `postitdesign`
- Nom : `Post-it Design`
- Catégorie conseillée : communication ou organization
- Auteur : Emmanuel
- Type : plugin Jeedom indépendant
- Démon : non
- Dépendances : non

## À vérifier avant publication

- [ ] Le plugin s’installe depuis zéro.
- [ ] Le plugin apparaît dans Gestion des plugins.
- [ ] L’icône apparaît correctement.
- [ ] La page plugin s’ouvre sans erreur.
- [ ] Ajout d’un post-it fonctionnel.
- [ ] Sauvegarde titre/message/couleur/taille/rotation fonctionnelle.
- [ ] Collage sur Design fonctionnel.
- [ ] Déplacement direct sur Design fonctionnel.
- [ ] Sauvegarde automatique de la position après déplacement.
- [ ] Compléter depuis le Design fonctionnel.
- [ ] Décoller depuis le Design fonctionnel.
- [ ] Le post-it ne bloque pas le Design quand les options sont masquées.
- [ ] Le rendu reste lisible sur tablette.
- [ ] Le plugin ne contient pas de logo Jeedom officiel.
- [ ] Le plugin ne contient pas de données personnelles.
- [ ] Le dépôt Git est propre.
- [ ] Les backups `_patch_backups/` ne sont pas publiés si non nécessaires.
- [ ] Les fichiers temporaires ne sont pas publiés.
- [ ] `plugin_info/info.json` est propre.
- [ ] `docs/fr_FR/index.md` est présent.
- [ ] `docs/fr_FR/changelog.md` est présent.
- [ ] `README.md` est propre.
- [ ] Tag `v1.0.0` créé après validation.

## GitHub

À faire avant publication Market :

- [ ] Créer un dépôt GitHub public ou privé selon stratégie.
- [ ] Pousser la branche `main`.
- [ ] Créer un tag stable `v1.0.0`.
- [ ] Vérifier que les docs sont visibles sur GitHub.
- [ ] Mettre à jour `plugin_info/info.json` avec les URL GitHub/Market définitives si nécessaire.

## Fiche Market

À préparer :

- Description courte :
  `Ajoutez des post-it interactifs directement sur vos Designs Jeedom.`

- Description longue :
  `Post-it Design permet de créer des notes visuelles, de les coller sur un Design Jeedom, de les déplacer directement depuis le Design, de les compléter et de les décoller sans supprimer l’équipement.`

- Prix conseillé :
  `2 à 4 €` pour une première version simple et utile.

- Mots-clés :
  `post-it`, `note`, `design`, `tablette`, `rappel`, `memo`, `domotique`.

## Politique de support

À préciser sur la fiche :

- Support via Community Jeedom ou GitHub Issues.
- Indiquer que le plugin est indépendant et non officiel.
- Demander version Jeedom + capture écran + logs en cas de bug.

## Notes techniques

Le plugin utilise :

- `eqLogic` pour créer les post-it ;
- `plan` et `planHeader` pour coller les post-it sur les Designs ;
- AJAX interne pour coller, déplacer, compléter et décoller ;
- rendu inline pour éviter que les CSS Jeedom ne cassent le visuel.
