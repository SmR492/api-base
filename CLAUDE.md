# CLAUDE.md — smr492/api-base

Leitfaden für LLM-Agenten, die auf Basis dieses Templates neue API-Projekte starten oder bestehende erweitern. Ergänzt [README.md](README.md) um Architektur-Hinweise und Konventionen.

## Template-Zweck

`api-base` ist das **Starting-Point-Template** für neue API-Projekte in der smr492-Landschaft (z.B. `auftrags-cockpit`, `webmailer`). Es bringt Symfony 7.3 + API Platform 4.1 + FrankenPHP + Mercure mit und dient als Referenz für REST/GraphQL-Patterns.

**Es ist kein Bundle** — forken, nicht requiren.

## Architektur auf einen Blick

| Layer | Komponente | Pfad |
|---|---|---|
| HTTP | FrankenPHP Worker Mode | `frankenphp/Dockerfile`, `frankenphp/Caddyfile` |
| Routing | API Platform + Twig | `config/routes/` |
| Resources | `#[ApiResource]`-Attribute direkt auf Entities **oder** in `src/ApiResource/` | `src/Entity/`, `src/ApiResource/` |
| Persistence | Doctrine ORM 3 + Migrations 3 | `src/Entity/`, `src/Repository/`, `migrations/` |
| Real-time | Mercure Hub (SSE) via `symfony/mercure-bundle` | `config/packages/mercure.yaml` |
| Security | `symfony/security-bundle` – JWT via `auth-bundle` (optional, siehe unten) | `config/packages/security.yaml` |

## Entities & ApiResource

Zwei Varianten werden unterstützt:

1. **Attribute direkt auf Entity** (empfohlen für einfache CRUD-Ressourcen) — siehe `src/Entity/Post.php`.
2. **Separate ApiResource-Klasse in `src/ApiResource/`** (empfohlen, wenn die API-Form von der Persistence-Form abweicht, z.B. für DTO-Projections oder Aggregate).

Konventionen:

- **Serialisations-Gruppen:** `<resource>:read` / `<resource>:write` (Beispiel: `post:read`, `post:write`). Keine `default`-Group verwenden.
- **Validator-Constraints** direkt auf Entity-Properties. API Platform mapped sie auf `422`-Responses.
- **Pagination:** Standard 20 Items, mit `paginationItemsPerPage` pro Resource überschreiben.
- **Filter & Search:** via `#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]` — nur dort, wo explizit gebraucht.

### Neue Resource anlegen

```bash
bin/console make:entity Post
# Properties hinzufügen (title, content, publishedAt, ...)
# Dann #[ApiResource]-Attribut setzen und Serialization-Groups bestücken
bin/console make:migration
bin/console doctrine:migrations:migrate
```

## Mercure (Real-time)

- Hub läuft per default auf Port 3000 (siehe `compose.yaml`).
- Publishing via `Symfony\Component\Mercure\HubInterface::publish(new Update(...))`.
- ApiResource-Updates automatisch publishen: `mercure: true` in den ApiResource-Operations.

```yaml
# config/packages/mercure.yaml
mercure:
    hubs:
        default:
            url: '%env(MERCURE_URL)%'
            public_url: '%env(MERCURE_PUBLIC_URL)%'
            jwt:
                secret: '%env(MERCURE_JWT_SECRET)%'
                publish: '*'
```

## Security / JWT

`api-base` bringt `symfony/security-bundle` mit, aber **keinen** konfigurierten User oder Login-Flow. Zwei Optionen:

1. **Session-basiert (Browser-Clients):** Standard Symfony Form-Login.
2. **JWT-basiert (PWA/Mobile):** Das [smr492/auth-bundle](https://github.com/SmR492/auth-bundle) via `composer require smr492/auth-bundle` und dem [smr492/recipes](https://github.com/SmR492/recipes)-Endpoint integrieren. Das Bundle bringt Login, Refresh-Tokens, Lockouts, 2FA mit. UC-17 (API-Token-Management) ist für v3 geplant.

Die Security-Whitelist (Public-Pfade wie `/api/docs`, `/api/posts` etc.) gehört in `config/packages/security.yaml` unter `access_control`.

## FrankenPHP Worker-Mode

- Production läuft im Worker-Mode (siehe `frankenphp/` + `compose.prod.yaml`). Kernel wird einmal gebootet und pro Request via `runtime/frankenphp-symfony` reused.
- **Statefulness:** Keine Request-spezifischen Werte an Services binden (kein `$this->currentRequest` usw.). Für Request-Kontext die `RequestStack` injizieren.
- **Dev:** `compose.override.yaml` setzt `APP_ENV=dev` und schaltet den Worker-Mode aus.

## Tests

- **Unit-Tests:** `tests/Unit/` (PHPUnit 12).
- **API-Tests:** `tests/Api/` (ApiPlatform\Symfony\Bundle\Test\ApiTestCase) — siehe `PostResourceTest.php` als Referenz.
- **Fixtures:** Empfohlen `doctrine/data-fixtures` + `zenstruck/foundry`, im Template noch nicht gepflegt.

Run:

```bash
./bin/phpunit                    # alle Tests
./bin/phpunit tests/Api          # nur API-Tests
```

**Wichtig:** `.env.test` ist bereits im Repo — es setzt `APP_ENV=test` + Mercure-/Mailer-Stubs. Nicht löschen (WWM-CI-Issue #19 zeigt, was passiert wenn das fehlt).

## Console-Commands (häufig)

- `bin/console make:entity` — neue Entity generieren
- `bin/console make:migration` — Doctrine-Migration aus Entity-Diff
- `bin/console doctrine:migrations:migrate` — Migrations ausführen
- `bin/console cache:clear` — Cache leeren
- `bin/console debug:router` — Route-Liste (zeigt API-Platform-Routen)
- `bin/console api:swagger:export` — OpenAPI-Spec exportieren

## Projekt klonen → neues API-Projekt

1. `gh repo create mein-projekt --template smr492/api-base --private`
2. In `composer.json`: `name` und `description` anpassen
3. `APP_SECRET` neu generieren
4. `src/Entity/Post.php` + `src/Repository/PostRepository.php` + `tests/Api/PostResourceTest.php` als Blueprint behalten oder entfernen
5. Eigene Entities hinzufügen, Migration erzeugen, Tests schreiben

## Optional: auth-bundle via Flex-Recipe

Das private Recipe-Repository wird in `composer.json` als zusätzlicher Flex-Endpoint gesetzt — siehe [smr492/recipes README](https://github.com/SmR492/recipes/blob/main/README.md). Danach legt `composer require smr492/auth-bundle` Bundle-Registrierung und Default-Config automatisch an.

## Anti-Patterns

- **Keine** Business-Logik in Controllern — `src/Controller/` ist für HTTP-Edges, nicht für Domain-Logic. Services in `src/Service/` auslagern.
- **Keine** `default`-Serialization-Group — sie durchsickert zu anderen Resources und erzeugt Memory-Leaks bei Zirkulärbeziehungen.
- **Nicht** die `.env`-Datei im Repo anpassen — `.env.dev` / `.env.test` / `.env.local` nutzen. Secrets gehören in `.env.local` (gitignored) oder in Symfony Secret-Vault.
- **Kein** `schema:update --force` in Production — immer Migrations. (Siehe `projects/traces/cli/doctrine-schema-update-dev.md` für Dev-Ausnahme.)

## Referenzen

- [README.md](README.md) — Quick-Start und Setup
- Symfony 7.3 Docs: https://symfony.com/doc/7.3
- API Platform 4.1: https://api-platform.com/docs/
- Mercure: https://mercure.rocks/
- `projects/wiki/tech/frankenphp.md`, `projects/wiki/tech/api-platform.md`
