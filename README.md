# PokeWorld 🌍⚡

Bienvenue sur **PokeWorld**, une application Symfony pour gérer un univers de Pokémon, Dresseurs, Arènes et Teams via une interface d'administration complète.

## 🚀 Fonctionnalités Principales

-   **Pokedex** : Consultation des Pokémon et de leurs statistiques.
-   **Dresseurs** : Gestion des profils de dresseurs (Nom, Région, Ville natale, Ambition, Description).
-   **Teams** : Un dresseur peut avoir plusieurs équipes. Chaque équipe peut contenir plusieurs Pokémon (max 6 recommandé).
-   **Admin Interface** : Une interface back-office (EasyAdmin) pour gérer tout le contenu sans toucher au code.

---

## 🛠️ Installation et Configuration

Assurez-vous d'avoir PHP, Composer et Symfony CLI installés.

1.  **Cloner le projet**
2.  **Installer les dépendances**
    ```bash
    composer install
    ```
3.  **Configurer la Base de Données**
    Modifiez le fichier `.env` pour configurer votre connexion MySQL :
    ```env
    DATABASE_URL="mysql://root:@127.0.0.1:3306/pokeworld?serverVersion=8.0.32&charset=utf8mb4"
    ```
4.  **Créer la Base de Données et les Tables**
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

---

## 🗄️ Gestion de la Base de Données

Voici les commandes essentielles pour gérer la base de données :

-   **Mettre à jour la structure (Migrations)** :
    ```bash
    php bin/console doctrine:migrations:migrate
    ```
-   **Charger les données de test (Fixtures)** :
    Attention : Cela vide les tables existantes !
    ```bash
    php bin/console doctrine:fixtures:load
    ```

---

## 👑 Administration (Back-Office)

L'administration est gérée via **EasyAdmin**.

-   **URL** : `/admin` (ex: `http://127.0.0.1:8000/admin`)
-   **Accès** : Gère les entités suivantes :
    -   **Dresseurs** : Créer, modifier, supprimer des dresseurs.
        -   *Nouveau* : Possibilité d'ajouter des Teams directement depuis la fiche Dresseur.
    -   **Teams** : Gérer les équipes et leur composition (Pokémon).
    -   **Arènes** : Gérer les arènes et les champions associés.

### 📝 Note sur les Teams
-   L'entité `Equipe` (ancienne version) a été supprimée au profit de `Team`.
-   Structure : `Dresseur` -> (OneToMany) -> `Team` -> (ManyToMany) -> `Pokemon`.
-   Vous pouvez ajouter des Pokémon à une Team en utilisant l'autocomplétion dans l'admin.

---

## 🐛 Dépannage Courant

-   **Erreur "Data too long for column 'description'"** :
    -   Le champ description est maintenant de type `TEXT` (65k caractères), ce problème est résolu.
-   **Problème de migration** :
    -   Si une migration échoue, verifiez les status : `php bin/console doctrine:migrations:status`.