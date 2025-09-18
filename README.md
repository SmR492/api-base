# API Base (English)

This project is a modern API boilerplate based on Symfony and API Platform. It provides a solid foundation for developing REST and GraphQL APIs with best practices, Docker support, and a modular architecture. It is intended as a starting point for new projects.

## Features

- Symfony Framework
- API Platform for rapid API development (REST & GraphQL)
- Docker setup for local development and production
- Support for Doctrine ORM
- CSRF protection and security configuration
- Asset management with Asset Mapper
- Migrations with Doctrine Migrations
- Extensive configuration options
- Unit and integration tests with PHPUnit
- Mercure Hub for real-time communication

## Requirements

- Docker & Docker Compose
- PHP >= 8.1 (for local development without Docker)
- Composer

## Quick Start

### With Docker

```bash
docker compose up --build
```

The API will be available at `http://localhost`.

### Without Docker

1. Install dependencies:
   ```bash
   composer install
   ```
2. Start the server:
   ```bash
   symfony server:start
   ```

## Running Tests

```bash
./bin/phpunit
```

## Project Structure

- `src/` – Source code (Controller, Entity, Repository, ...)
- `config/` – Configurations
- `public/` – Webroot (entry point)
- `assets/` – Frontend assets (JS, CSS)
- `migrations/` – Database migrations
- `tests/` – Tests
- `docker/`, `frankenphp/` – Docker and server configurations

## Useful Commands

- Create migrations: `bin/console make:migration`
- Run migrations: `bin/console doctrine:migrations:migrate`
- Clear cache: `bin/console cache:clear`

## Further Links

- [Symfony](https://symfony.com/)
- [API Platform](https://api-platform.com/)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
- [Mercure Hub](https://mercure.rocks/)

---

# API Base (Deutsch)

Dieses Projekt ist eine moderne API-Basis auf Basis von Symfony und API Platform. Es bietet eine solide Grundlage für die Entwicklung von REST- und GraphQL-APIs mit Best Practices, Docker-Support und einer modularen Architektur.

## Funktionen

- Symfony-Framework
- API Platform für schnelle API-Entwicklung (REST & GraphQL)
- Docker-Setup für lokale Entwicklung und Produktion
- Unterstützung für Doctrine ORM
- CSRF-Schutz und Sicherheitskonfiguration
- Asset-Management mit Asset Mapper
- Migrationen mit Doctrine Migrations
- Umfangreiche Konfigurationsmöglichkeiten
- Unit- und Integrationstests mit PHPUnit
- Mercure Hub für Echtzeit-Kommunikation

## Voraussetzungen

- Docker & Docker Compose
- PHP >= 8.1 (bei lokaler Entwicklung ohne Docker)
- Composer

## Schnellstart

### Mit Docker

```bash
docker compose up --build
```

Die API ist dann unter `http://localhost` erreichbar.

### Ohne Docker

1. Abhängigkeiten installieren:
   ```bash
   composer install
   ```
2. Server starten:
   ```bash
   symfony server:start
   ```

## Tests ausführen

```bash
./bin/phpunit
```

## Projektstruktur

- `src/` – Quellcode (Controller, Entity, Repository, ...)
- `config/` – Konfigurationen
- `public/` – Webroot (Entry-Point)
- `assets/` – Frontend-Assets (JS, CSS)
- `migrations/` – Datenbankmigrationen
- `tests/` – Tests
- `docker/`, `frankenphp/` – Docker- und Server-Konfigurationen

## Nützliche Befehle

- Migrationen erstellen: `bin/console make:migration`
- Migrationen ausführen: `bin/console doctrine:migrations:migrate`
- Cache leeren: `bin/console cache:clear`

## Weiterführende Links

- [Symfony](https://symfony.com/)
- [API Platform](https://api-platform.com/)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
- [Mercure Hub](https://mercure.rocks/)

---

# API Base (Français)

Ce projet est une base moderne pour API, basée sur Symfony et API Platform. Il fournit une base solide pour le développement d’API REST et GraphQL avec les meilleures pratiques, le support Docker et une architecture modulaire. Il est destiné à servir de point de départ pour de nouveaux projets.

## Fonctionnalités

- Framework Symfony
- API Platform pour un développement rapide d’API (REST & GraphQL)
- Configuration Docker pour le développement local et la production
- Prise en charge de Doctrine ORM
- Protection CSRF et configuration de la sécurité
- Gestion des assets avec Asset Mapper
- Migrations avec Doctrine Migrations
- Nombreuses options de configuration
- Tests unitaires et d’intégration avec PHPUnit
- Mercure Hub pour la communication en temps réel

## Prérequis

- Docker & Docker Compose
- PHP >= 8.1 (pour le développement local sans Docker)
- Composer

## Démarrage rapide

### Avec Docker

```bash
docker compose up --build
```

L’API sera disponible sur `http://localhost`.

### Sans Docker

1. Installer les dépendances :
   ```bash
   composer install
   ```
2. Démarrer le serveur :
   ```bash
   symfony server:start
   ```

## Exécuter les tests

```bash
./bin/phpunit
```

## Structure du projet

- `src/` – Code source (Contrôleur, Entité, Répertoire, ...)
- `config/` – Configurations
- `public/` – Webroot (point d’entrée)
- `assets/` – Assets frontend (JS, CSS)
- `migrations/` – Migrations de base de données
- `tests/` – Tests
- `docker/`, `frankenphp/` – Configurations Docker et serveur

## Commandes utiles

- Créer des migrations : `bin/console make:migration`
- Exécuter les migrations : `bin/console doctrine:migrations:migrate`
- Vider le cache : `bin/console cache:clear`

## Liens utiles

- [Symfony](https://symfony.com/)
- [API Platform](https://api-platform.com/)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
- [Mercure Hub](https://mercure.rocks/)

---

© 2025 – Stefan Riedl
