# Market Checklist - Post-it Design

## Version

v1.0.5

## Vérifications

- [x] Le plugin s’installe dans Jeedom.
- [x] Le post-it peut être affiché sur un Design.
- [x] Le post-it peut être déplacé.
- [x] La position X/Y est sauvegardée.
- [x] La rotation fonctionne par appui simple.
- [x] Le mode rotation bloqué a été supprimé.
- [x] Les boutons du post-it restent accessibles.
- [x] Le décollage du Design ne supprime pas l’équipement.
- [x] La documentation française est présente.
- [x] Le changelog est présent.
- [x] Le README est présent.
- [x] GitHub Pages est compatible avec la documentation.
- [x] La PROD n’a pas été modifiée pendant cette correction.

## Note technique

La version v1.0.5 utilise une gestion tactile inline dans le HTML du widget, car le chargement de fichiers JavaScript externes du plugin peut être refusé en 403 depuis certains contextes de Design Jeedom.
