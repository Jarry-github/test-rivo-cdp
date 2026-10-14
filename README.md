# OLONA TALENTS — API de suivi client et d'événements

Application web développée avec Laravel dans le cadre d'un test technique. Elle permet d'enregistrer des événements associés à des clients, de consulter les données et de rechercher des segments de clients à partir des propriétés de leurs événements.

## Fonctionnalités

- Enregistrement d'événements associés à un client identifié par son adresse e-mail.
- Création automatique d'un client lors de la réception de son premier événement.
- Déduplication des événements à partir d'un `event_id` UUID lorsqu'il est fourni.
- Consultation des clients et de leurs statistiques.
- Consultation des événements avec pagination.
- Segmentation des clients à partir de conditions portant sur les propriétés JSON des événements.
- Interface web de consultation et formulaire d'enregistrement d'événements.
- Tests automatisés des fonctionnalités principales.

## Technologies

- PHP et Laravel
- MySQL ou MariaDB
- Eloquent ORM et migrations Laravel
- Laravel Sanctum pour l'authentification par jeton
- PHPUnit pour les tests
- HTML, CSS et JavaScript pour l'interface

## Prérequis

Installer les éléments suivants :

- PHP compatible avec la version Laravel du projet.
- Composer.
- MySQL ou MariaDB.
- Node.js et npm si vous souhaitez compiler les ressources front-end.

Consulter `composer.json` pour les versions exactes des dépendances.

## Installation locale

### 1. Récupérer le projet

```bash
git clone https://github.com/Jarry-github/test-rivo-cdp.git
cd test-rivo-cdp
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

Sous Windows PowerShell :

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Modifier ensuite le fichier `.env` avec les paramètres de la base de données locale :

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_rivo_cdp
DB_USERNAME=root
DB_PASSWORD=
```

Adapter le nom de la base, l'utilisateur et le mot de passe à votre installation. Ne jamais publier le fichier `.env`.

### 4. Créer la base de données

Créer une base MySQL ou MariaDB correspondant à `DB_DATABASE`, par exemple :

```sql
CREATE DATABASE test_rivo_cdp
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Appliquer les migrations :

```bash
php artisan migrate
```

Les migrations du dépôt construisent le schéma nécessaire. Elles incluent les tables de l'application ainsi que les tables Laravel utilisées pour les utilisateurs, les sessions, le cache, les tâches et les jetons d'accès.

### 5. Lancer l'application

```bash
php artisan serve
```

Ouvrir ensuite :

- Dashboard : http://127.0.0.1:8000
- Formulaire d'événement : http://127.0.0.1:8000/events/create

Si les ressources front-end doivent être compilées :

```bash
npm install
npm run build
```

## Documentation de l'API

Les routes métier ci-dessous sont définies dans `routes/api.php`. Le préfixe `/api` est ajouté automatiquement par Laravel.

### 1. Enregistrer un événement

**POST `/api/events`**

Exemple de requête JSON :

```json
{
  "event_id": "550e8400-e29b-41d4-a716-446655440000",
  "email": "client@example.com",
  "name": "Client Démo",
  "event_name": "purchase",
  "properties": {
    "amount": 150000,
    "currency": "MGA"
  },
  "occurred_at": "2026-10-14T10:00:00"
}
```

Champs principaux :

- `email` : obligatoire, adresse e-mail du client.
- `event_name` : obligatoire, nom de l'événement.
- `event_id` : UUID facultatif permettant d'identifier un événement de manière unique.
- `name` : nom facultatif du client.
- `properties` : objet JSON facultatif contenant les propriétés de l'événement.
- `occurred_at` : date facultative de l'événement ; la date courante est utilisée si elle est omise.

Réponse indicative pour une création réussie : HTTP `201 Created`.

Une nouvelle réception avec le même `event_id` renvoie HTTP `200 OK` et `duplicate: true`, sans créer un deuxième événement. Sans `event_id`, la déduplication par identifiant n'est pas possible.

### 2. Lister les clients

**GET `/api/customers`**

Retourne les clients avec le nombre d'événements associés. La pagination est configurée à 20 éléments par page.

Exemple :

```http
GET /api/customers?page=1
```

La réponse est une collection paginée Laravel.

### 3. Consulter un client

**GET `/api/customers/{id}`**

Exemple :

```http
GET /api/customers/1
```

Cette route permet de consulter le profil du client, ses événements récents et les statistiques d'achat calculées par le contrôleur.

Si le client n'existe pas, vérifier le statut HTTP retourné par l'application.

### 4. Lister les événements

**GET `/api/events`**

Exemple :

```http
GET /api/events?page=1
```

Retourne les événements, associés à leurs informations client, triés du plus récent au plus ancien. La pagination est configurée à 20 éléments par page.

### 5. Rechercher un segment de clients

**POST `/api/segments/query`**

Cette route recherche les clients ayant des événements correspondant à toutes les conditions envoyées.

Exemple de requête :

```json
{
  "conditions": [
    {
      "event": "purchase",
      "property": "amount",
      "operator": ">=",
      "value": 100000
    },
    {
      "event": "purchase",
      "property": "currency",
      "operator": "=",
      "value": "MGA"
    }
  ],
  "limit": 50
}
```

Opérateurs acceptés : `=`, `!=`, `>`, `>=`, `<`, `<=`.

Chaque condition précise le nom de l'événement, la propriété JSON, l'opérateur et la valeur recherchée. Le champ `limit` est facultatif, avec une valeur par défaut de 50 et un maximum de 100.

Les noms de propriétés sont limités à des caractères alphanumériques et au caractère `_`. Les valeurs sont transmises séparément dans les requêtes SQL.

### Authentification

La route `GET /api/user` utilise le middleware `auth:sanctum` et nécessite un jeton Sanctum valide.

**Important :** dans la version actuelle, les cinq routes métier décrites plus haut ne sont pas protégées par ce middleware. Ne considérez donc pas l'API comme entièrement sécurisée pour une exposition publique en production. L'authentification et les autorisations doivent être adaptées au contexte de déploiement.

## Schéma de la base de données

Le schéma MySQL de l'application est disponible dans le fichier suivant :

`database/schema.sql`

Ce fichier contient la structure des tables et des index de la base de données. Les migrations Laravel correspondantes se trouvent dans `database/migrations/`.

## Base de données

### Table `customers`

| Colonne | Rôle |
|---|---|
| `id` | Clé primaire |
| `email` | Adresse e-mail unique et obligatoire |
| `name` | Nom du client, facultatif |
| `created_at`, `updated_at` | Dates de création et de mise à jour |

### Table `events`

| Colonne | Rôle |
|---|---|
| `id` | Clé primaire |
| `event_id` | UUID facultatif et unique pour la déduplication |
| `customer_id` | Référence au client |
| `event_name` | Nom de l'événement |
| `properties` | Propriétés JSON de l'événement |
| `occurred_at` | Date de survenue de l'événement |
| `created_at`, `updated_at` | Dates de création et de mise à jour |

Relations :

- Un client peut posséder plusieurs événements.
- Chaque événement appartient à un client.
- L'adresse e-mail identifie le client lors de l'ingestion des événements.

Des index sont définis pour optimiser les recherches par client, date et nom d'événement. Les migrations constituent la source de référence du schéma : `database/migrations/`.

## Exécuter les tests

```bash
php artisan test
```

La configuration PHPUnit utilise une base de données de test distincte, nommée `test_rivo_cdp_testing` dans la configuration actuelle.

Créer cette base avant d'exécuter les tests si elle n'existe pas. Vérifier `phpunit.xml` avant toute exécution et ne jamais remplacer la configuration de test par les paramètres de la base de développement.

Les tests couvrent notamment l'enregistrement d'événements, les statistiques d'un client, la segmentation et la déduplication.

## Décisions de conception et compromis

- **Identification par e-mail :** permet de rattacher simplement les événements à un client. Les adresses sont normalisées en minuscules lors de l'ingestion.
- **Propriétés JSON :** offre une structure flexible pour différents types d'événements, mais les requêtes sur les propriétés nécessitent une attention particulière aux types et aux index.
- **Déduplication par UUID :** évite les doublons lorsque l'émetteur fournit le même `event_id`. Le champ reste facultatif pour accepter les événements qui n'en disposent pas.
- **Segmentation par conditions :** utilise des sous-requêtes `EXISTS` pour vérifier que le client possède les événements satisfaisant chaque condition.
- **Pagination :** limite le volume de données renvoyé par les routes de consultation.
- **Tests automatisés :** vérifient les principaux comportements et facilitent les évolutions.
- **Traitement synchrone :** l'enregistrement est effectué directement dans la requête HTTP. Une file de tâches pourrait être ajoutée si le volume d'événements ou les contraintes de latence l'exigent.
- **Authentification :** Sanctum est présent, mais la protection des routes métier reste un point à finaliser avant une mise en production publique.

## Données de démonstration

L'application peut être testée en envoyant des requêtes à `POST /api/events`. Utiliser des adresses e-mail et des identifiants d'événements fictifs pour les démonstrations.

Ne pas importer de données personnelles réelles ni partager de jetons ou de mots de passe dans le dépôt.

## Dépôt source

https://github.com/Jarry-github/test-rivo-cdp