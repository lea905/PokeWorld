# PokeWorld 🌍⚡

Bienvenue sur **PokeWorld**, une application **Laravel** (vues **Blade**) pour gérer un univers de Pokémon, Dresseurs, Arènes, Organisations et Teams via une interface d'administration complète.

> Le projet était à l'origine écrit avec Symfony (Doctrine, Twig, EasyAdmin). Il a été migré vers Laravel 13 :
> Doctrine → Eloquent, Twig → Blade, fixtures → seeders, EasyAdmin → back-office Blade maison.
> Le schéma de base de données est **identique** : une base créée par l'ancienne version reste utilisable.

## 🚀 Fonctionnalités Principales

-   **Pokédex** : Consultation des Pokémon, filtres (génération, type, stade d'évolution, légendaires, fabuleux), statistiques et famille d'évolution.
-   **Dresseurs** : Profils de dresseurs (Nom, Région, Ville natale, Ambition, Description, Antagoniste, Grade).
-   **Teams** : Un dresseur peut avoir plusieurs équipes. Chaque équipe peut contenir plusieurs Pokémon (max 6 recommandé).
-   **Arènes** et **Organisations** (Team Rocket…).
-   **Recherche** globale sur les Pokémon, Dresseurs et Arènes (`/recherche?q=...`).
-   **Admin Interface** : un back-office (`/admin`) pour gérer tout le contenu sans toucher au code.

---

## 🛠️ Installation et Configuration

Prérequis : PHP ≥ 8.4 (extensions `pdo_mysql`, `intl`, `mbstring`, `fileinfo`), Composer et MySQL/MariaDB.

1.  **Cloner le projet**
2.  **Installer les dépendances et créer le fichier `.env`**
    ```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    ```
3.  **Configurer la Base de Données** dans `.env` :
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pokeworld
    DB_USERNAME=root
    DB_PASSWORD=root
    ```
4.  **Créer les tables**
    ```bash
    php artisan migrate
    ```
    Les tables PokeWorld déjà présentes (base issue de la version Symfony) sont conservées telles quelles.
5.  **Lancer le serveur**
    ```bash
    php artisan serve
    ```
    Puis ouvrir `http://127.0.0.1:8000`.

### Avec Docker

```bash
docker compose up -d --build
docker compose exec web composer install
docker compose exec web php artisan migrate --seed
```

---

## 🗄️ Gestion de la Base de Données

-   **Mettre à jour la structure (Migrations)** :
    ```bash
    php artisan migrate
    ```
-   **Charger les données (Seeders)** — types et Pokémon importés depuis la [PokéAPI](https://pokeapi.co), puis Team Rocket, dresseurs, arènes et équipes :
    ```bash
    php artisan db:seed
    ```
-   **Tout réinitialiser puis recharger** (équivalent de l'ancien `app:load:all`) — attention, cela vide toutes les tables :
    ```bash
    php artisan app:load-all
    ```

L'import complet des 1025 Pokémon prend du temps. Pour un import rapide en développement, limitez-le dans `.env` :
```env
POKEMON_SEED_LIMIT=151
```

---

## 👑 Administration (Back-Office)

-   **URL** : `/admin` (ex : `http://127.0.0.1:8000/admin`)
-   **Accès** : réservé aux comptes **administrateurs**. Un visiteur est redirigé vers `/connexion`, et un utilisateur connecté sans droit administrateur reçoit une erreur 403. Les tentatives de connexion sont limitées à 5 par minute.
-   **Créer un compte administrateur** (le mot de passe est demandé de façon masquée, 12 caractères minimum) :
    ```bash
    php artisan app:create-admin ton.email@exemple.fr
    ```
    Avec Docker : `docker compose exec web php artisan app:create-admin ton.email@exemple.fr`.
-   **Contenu géré** :
    -   **Dresseurs** : Créer, modifier, supprimer des dresseurs, avec ajout/suppression de Teams directement depuis la fiche Dresseur.
    -   **Teams** : Gérer les équipes et leur composition (Pokémon, avec recherche).
    -   **Arènes** : Gérer les arènes, leur champion et leur badge (image).
    -   **Organisations** : Gérer les organisations et leur logo.
    -   **Pokémon** : Consulter et corriger les Pokémon importés.

Les images envoyées sont enregistrées dans `public/images/` (badges dans `public/images/Badges/`).

Les textes riches (description, ambition, but) acceptent un HTML simple (`<p>`, `<b>`, `<i>`, `<ul>`, liens…). Ils sont nettoyés à l'affichage avec la directive Blade `@html(...)` (`App\Support\SafeHtml`) : scripts, attributs `on*` et liens `javascript:` sont supprimés.

---

## 🧭 Organisation du code

| Symfony (avant)                        | Laravel (maintenant)                                  |
|----------------------------------------|-------------------------------------------------------|
| `src/Entity/*`                         | `app/Models/*` (Eloquent)                             |
| `src/Repository/*` (findBySearchQuery…) | scopes Eloquent (`Pokemon::filter()`, `::search()`…) |
| `src/Controller/*`                     | `app/Http/Controllers/*`                              |
| `src/Controller/Admin/*` (EasyAdmin)   | `app/Http/Controllers/Admin/*` + `resources/views/admin/*` |
| `templates/*.html.twig`                | `resources/views/*.blade.php`                         |
| `src/DataFixtures/*`                   | `database/seeders/*`                                  |
| `migrations/*` (Doctrine)              | `database/migrations/*`                               |
| `assets/styles/app.css`                | `public/css/app.css`                                  |

## ✅ Qualité du code

| Commande | Ce qu'elle fait |
|---|---|
| `composer test` | Lance les tests (PHPUnit, base SQLite en mémoire) |
| `composer lint` | Vérifie la mise en forme du code (Pint) ; `vendor/bin/pint` la corrige |
| `composer analyse` | Analyse statique (Larastan, niveau 5, voir `phpstan.neon`) |
| `composer check` | Tout ce qui précède + `composer audit` (failles connues dans les dépendances) |

Lancez `composer check` avant chaque pull request : il n'y a pas d'intégration continue sur GitHub.
