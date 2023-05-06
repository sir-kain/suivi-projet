# suivi-projet

## TODO 1er MVP

- [x] Formatter les dates.

- [x] Afficher les listes par ordre de derniere creation.

- [x] Mettre des libelles claires pour la presentation de l'app

- [x] Faire un seul champs pour les date `input:date`

- [x] Page detail bande: Utiliser des accordions sur les ventes et depenses

- [x] Calculer et afficher la duree en jour d'une bande, Tenir en compte la date de cloture.

- [x] Gerer cloture bande

- [x] Ajouter les colonnes description et client a la vente

- [x] Afficher les details de la vente et depense sur une modale

La description servira a expliquer les raisons du prix de vente dans certains cas (frais supplementaires, etc.).

- [x] Cacher les colone date de creation et bande sur les modales

- [ ] Gerer les depenses hors bande

- [ ] Gestion stock

Valider les donnees entre le nombre de mortalite, les ventes et le stock. Pour ne pas avoir de faux calculs. Afficher
des message flash en consequense

- [ ] Gestion compte bancaire (Gestion des avoir)

- [x] Gestion authentification

- [ ] Envoie mail sur toute action

- [ ] Deploiement 

## Commandes

```bash
symfony console dbal:run-sql 'SELECT * FROM bande'
```

```bash
symfony console make:migration
```

```bash
symfony console doctrine:migrations:migrate
```

http://localhost:18080/?pgsql=database&username=app&db=app&ns=public