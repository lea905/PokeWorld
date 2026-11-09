# PokeWorld

Encyclopédie web de l'univers Pokémon : Pokédex complet, dresseurs, arènes et organisations, avec un back-office pour gérer le contenu.

Les données des 1025 Pokémon (statistiques, types, évolutions) sont importées depuis la [PokéAPI](https://pokeapi.co).

## Fonctionnalités

**Site public**

- Pokédex paginé, filtrable par génération, type, stade d'évolution et statut (légendaire, fabuleux).
- Fiche détaillée de chaque Pokémon : statistiques de base, types et famille d'évolution.
- Annuaire des dresseurs, avec leurs équipes, leur arène et leur organisation.
- Liste des arènes avec leur champion et leur badge.
- Organisations (Team Rocket…) et leurs membres.
- Recherche globale sur les Pokémon, les dresseurs et les arènes.

**Back-office** (`/admin`)

- Gestion des dresseurs et de leurs équipes, des arènes, des organisations et des Pokémon.
- Accès réservé aux comptes administrateurs.

## Stack technique

| Composant | Technologie |
|---|---|
| Langage | PHP 8.4 ou plus |
| Framework | Laravel 13 |
| Vues | Blade, Bootstrap 5 |
| Base de données | MariaDB / MySQL (SQLite en mémoire pour les tests) |
| Qualité | PHPUnit, Pint, Larastan |
| Environnement | Docker Compose (Apache, MariaDB, Mailpit) |

## Démarrage rapide avec Docker

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec web composer install
docker compose exec web php artisan key:generate
docker compose exec web php artisan migrate --seed
docker compose exec web php artisan app:create-admin votre.email@exemple.fr
```

Le site est alors disponible sur <http://localhost:8000> et le back-office sur <http://localhost:8000/admin>.

L'import complet des Pokémon depuis la PokéAPI prend plusieurs minutes. Pour un environnement de développement, il peut être limité dans `.env` (par exemple aux 151 premiers) :

```env
POKEMON_SEED_LIMIT=151
```

## Installation sans Docker

Prérequis : PHP 8.4 ou plus avec les extensions `pdo_mysql`, `intl`, `mbstring` et `fileinfo`, Composer, et un serveur MariaDB ou MySQL.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Renseigner ensuite la connexion à la base dans `.env`. Les valeurs par défaut correspondent à la base fournie par Docker :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=app
DB_PASSWORD="!ChangeMe!"
```

Puis créer les tables, importer les données et lancer le serveur :

```bash
php artisan migrate --seed
php artisan app:create-admin votre.email@exemple.fr
php artisan serve
```

## Données

| Commande | Effet |
|---|---|
| `php artisan migrate` | Crée ou met à jour les tables. |
| `php artisan db:seed` | Importe les types et les Pokémon depuis la PokéAPI, puis les organisations, dresseurs, arènes et équipes. |
| `php artisan app:load-all` | Vide toutes les tables puis relance l'import complet. Irréversible. |

## Administration

Le back-office est accessible sur `/admin` après connexion sur `/connexion`.

- Seuls les comptes administrateurs y ont accès. Un utilisateur connecté sans ce droit reçoit une erreur 403.
- Un compte administrateur se crée avec `php artisan app:create-admin <email>`. Le mot de passe est demandé de façon masquée (12 caractères minimum).
- Les tentatives de connexion sont limitées à 5 par minute.
- Les champs de texte riche (description, ambition, but) acceptent du HTML simple. Il est nettoyé à l'affichage : scripts, attributs d'événements et liens `javascript:` sont supprimés.
- Les images envoyées sont enregistrées dans `public/images/`, et les badges d'arène dans `public/images/Badges/`.

## Structure du projet

```
app/
├── Http/Controllers/        Contrôleurs du site public
│   ├── Admin/               Contrôleurs du back-office
│   └── Auth/                Connexion et déconnexion
├── Http/Requests/           Validation des formulaires (Form Requests)
├── Models/                  Modèles Eloquent
└── Support/SafeHtml.php     Nettoyage du HTML saisi dans le back-office
database/
├── migrations/              Schéma de la base
└── seeders/                 Import des données (PokéAPI et données du jeu)
resources/views/
├── components/              Composants Blade réutilisables
├── admin/                   Vues du back-office
└── …                        Vues du site public
routes/
├── web.php                  Routes du site et du back-office
└── console.php              Commandes app:load-all et app:create-admin
tests/Feature/               Tests fonctionnels
```

## Qualité et tests

| Commande | Effet |
|---|---|
| `composer test` | Lance les tests (PHPUnit, base SQLite en mémoire). |
| `composer lint` | Vérifie la mise en forme du code avec Pint (`vendor/bin/pint` la corrige). |
| `composer analyse` | Lance l'analyse statique avec Larastan (niveau 5, configuration dans `phpstan.neon`). |
| `composer check` | Enchaîne les trois commandes précédentes et `composer audit`. |

Le dépôt n'a pas d'intégration continue : `composer check` doit passer avant chaque pull request.

## Contribution

- Les branches partent de `develop` : `feature/<TICKET>-description` pour une fonctionnalité, `fix/<TICKET>-description` pour une correction.
- Les messages de commit suivent le format `type(scope): sujet`, suivi d'une ligne vide et d'une description.
- Chaque branche est intégrée à `develop` par une pull request. `main` contient la dernière version stable.

## Historique

La première version de PokeWorld reposait sur Symfony (Doctrine, Twig, EasyAdmin). Le projet a été migré vers Laravel 13 et Blade en conservant le même schéma de base de données : une base créée par l'ancienne version reste compatible.
