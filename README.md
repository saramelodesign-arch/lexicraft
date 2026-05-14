# LexiCraft Glossary

LexiCraft is the product platform; **LexiCraft Glossary** is the public-facing industrial terminology dataset focused on footwear, leather goods, leather, and fashion accessories.

The project is being built as a concept-first terminology platform designed for:

- industrial terminology
- multilingual navigation
- semantic relations
- technical learning
- SEO-first architecture
- fast semantic search
- educational and professional use

---

## Core vision

LexiCraft is not a traditional word-based dictionary. The platform is structured around **concepts** and multilingual terminology relations.

Example:

| Concept | Portuguese | English | French | German |
| --- | --- | --- | --- | --- |
| Topline | gáspea | topline | ligne de tige | Schaftlinie |

This architecture allows multilingual navigation, semantic linking, professional terminology consistency, scalable search indexing, future AI integration, and international SEO.

---

## Main features (product)

### Multilingual interface

Target languages for content and UI:

- Portuguese
- English
- French
- German

### Technical glossary (planned)

Each term page is intended to include short and full definitions, translations, industrial examples, semantic relations, media (images/video), and related terminology.

### Semantic search (infrastructure)

[Laravel Scout](https://laravel.com/docs/scout) is installed. The default driver is the **`collection`** driver (local, no external service). For production-style search, configure **Meilisearch** (or another Scout driver) via `.env`.

### Learning-oriented structure (planned)

Flashcards, quizzes, audio, diagrams, learning modules—roadmap items, not implemented in the foundation app yet.

---

## Technology stack (repository)

Accurate versions come from `composer.json` / `composer.lock`:

| Layer | Technology |
| --- | --- |
| Runtime | PHP **8.3+** |
| Framework | Laravel **13.x** (`laravel/framework` ^13.7) |
| Auth | Laravel Fortify |
| UI | Livewire **4**, Volt, [Flux UI](https://fluxui.dev/) (requires Composer authentication) |
| CSS / build | Tailwind CSS **4**, Vite **8** |

Optional / configured via env:

| Concern | Package / service |
| --- | --- |
| Search indexing | Laravel Scout (Meilisearch, Algolia, database driver, etc.) |
| Media | Spatie Laravel Media Library |
| Database | **PostgreSQL** (default in `.env.example`; LexiCraft glossary schema targets PG) |
| Database (optional) | **SQLite** only for experiments; PHPUnit uses in-memory SQLite via `phpunit.xml` |

---

## Architecture (target)

LexiCraft aims at a domain-driven, concept-first layout. Planned domain structure:

```text
app/
└── Domains/
    ├── Concepts/
    ├── Search/
    ├── Localization/
    ├── Media/
    ├── Learning/
    └── Shared/
```

The current codebase is the **Laravel + Livewire foundation** (authentication, settings, dashboard). Domain modules above are not present yet.

---

## Database design (planned)

Core entities (roadmap): concepts, concept translations, examples, semantic relations, domains, media, languages.

---

## Requirements

- PHP **8.3+**
- [Composer](https://getcomposer.org/)
- Node.js **LTS** (CI uses 22; any current LTS is fine)
- Database: **PostgreSQL** (aligned with `.env.example`). Ensure PHP has the **pdo_pgsql** extension enabled if `php artisan migrate` fails with “could not find driver”.

Optional:

- **Meilisearch** (or another Scout backend) when `SCOUT_DRIVER` is not `collection`
- **Flux** Composer credentials (`FLUX_USERNAME` / `FLUX_LICENSE_KEY`) — required for `composer install` in CI and for contributors installing private Flux packages

---

## Installation

### Clone and enter the project

```bash
git clone https://github.com/saramelodesign-arch/LexiCraft.git
cd LexiCraft
```

(Use your fork or the canonical remote URL if different.)

### PHP dependencies

Private Flux packages need HTTP Basic auth for `composer.fluxui.dev`. Configure once per machine (see [Flux licensing docs](https://fluxui.dev/docs/installation)) or use environment variables in CI.

```bash
composer install
```

### Frontend dependencies

```bash
npm install
```

### Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Database (PostgreSQL)

Copy `.env.example` to `.env` and adjust credentials. Create the database once (example on PostgreSQL):

```sql
CREATE DATABASE lexicraft;
```

Then:

```bash
php artisan migrate
```

If you see **`could not find driver`** with `DB_CONNECTION=pgsql`, enable the **PostgreSQL PDO** extension for your PHP install (`pdo_pgsql` / `extension=pgsql` on Windows: uncomment in `php.ini` and ensure matching DLLs are present).

**SQLite (optional):** only if you switch `.env` to `DB_CONNECTION=sqlite` and point `DB_DATABASE` at `database/database.sqlite` (create the empty file first). The automated test suite still uses **SQLite in memory** via `phpunit.xml`, independent of your local `.env`.

### Meilisearch (optional)

When you switch Scout to Meilisearch:

```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=
```

Start Meilisearch locally per its docs; sync indexes with Scout commands when models are searchable.

---

## Development

Single command (PHP server, queue worker, Vite) via Composer:

```bash
composer dev
```

Or manually:

```bash
php artisan serve
npm run dev
```

Production assets:

```bash
npm run build
```

---

## Testing and code style

```bash
composer test      # Pint (check) + PHPUnit
composer lint      # Pint (fix)
composer lint:check
```

GitHub Actions: `.github/workflows/tests.yml` runs PHPUnit and builds assets; `.github/workflows/lint.yml` runs Pint. Flux secrets must be configured in the repository/environment for Composer to resolve private packages.

---

## Security

The foundation follows common Laravel practices: CSRF protection, hashed passwords, email verification (when enabled on the `User` model), Fortify rate limiting, and middleware-protected routes. For production, set `APP_DEBUG=false`, use strong `APP_KEY`, HTTPS, and restrict database and Scout credentials.

---

## Development status

**In place today:** application shell, Fortify authentication (registration, reset, email verification, two-factor), Livewire/Volt settings pages, Tailwind/Flux UI, Scout and Media Library as dependencies.

**Next phases (roadmap):** concept domain, multilingual routing, semantic relations, glossary indexing, learning features, media workflows tied to glossary content.

---

## Project goals

LexiCraft and LexiCraft Glossary aim to deliver a professional industrial terminology surface, a multilingual navigation platform, a technical learning resource, a semantic knowledge graph, and a specialized SEO layer for footwear and leather goods.

---

## License

Open-source dependencies remain under their respective licenses. This application’s license is **MIT**, as declared in `composer.json`. If you treat terminology datasets, branding, or editorial content as proprietary, document that separately from the codebase license.

---

## Author

**Sara Melo** — Frontend developer / industrial CAD-CAM specialist  

GitHub: [https://github.com/saramelodesign-arch](https://github.com/saramelodesign-arch)
