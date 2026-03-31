# ACF Homepage Importer — Developer Guide

This guide defines the best-practice stack, architecture, workflow, access requirements, and implementation standards for the project.

---

## 1. Purpose

Build a WordPress plugin that can:
- crawl a source homepage or assess an existing WordPress homepage template
- detect and classify homepage sections
- reshape raw extraction data into a canonical internal model
- map that model into ACF Flexible Content layouts
- seed the final data into WordPress
- render the homepage dynamically from ACF
- verify the migration and store reports

The MVP is homepage-only.

---

## 2. Best-Practice Stack

### Core stack
- **WordPress**: CMS runtime and plugin host
- **PHP 8.1 or 8.2**: plugin language
- **ACF Pro**: Flexible Content and programmatic field groups
- **MySQL 8**: WordPress database
- **Docker + Docker Compose**: reproducible local environment
- **WP-CLI**: automation, diagnostics, and import commands

### Crawling and parsing
- **WordPress HTTP API (`wp_remote_get`)**: primary fetcher
- **DOMDocument**: HTML parsing and DOM traversal
- **Optional Firecrawl**: fallback for blocked, messy, or JS-heavy pages
- **Optional headless browser later**: for JS-rendered sites beyond MVP

### AI layer
- **OpenRouter** as the preferred AI gateway
- one or more models behind a single internal AI service interface
- AI used only for classification, normalization, and alt-text generation

### Supporting tools
- **phpMyAdmin** or database access
- **SSH** for deployment and debugging
- **Git** for version control
- **Linting and formatting** for PHP and markdown

---

## 3. AI Recommendation

### Recommended choice
Use **OpenRouter** instead of hardcoding a single provider.

### Why
- one API key for multiple models
- easy model switching without changing application logic
- good for testing accuracy, cost, and speed
- allows fallback models for classification and normalization

### Best practice
Create an internal AI service wrapper so the rest of the plugin only talks to one interface.

Example responsibilities:
- `classifySection()`
- `normalizeContent()`
- `generateAltText()`
- `validateStructuredJson()`

### Model policy
- start with one stable model
- keep the model configurable via environment or admin settings
- do not spread model-specific logic throughout the codebase

---

## 4. Architecture Overview

### Main pipeline
```text
Source homepage or WordPress theme
→ detect source type
→ fetch HTML
→ parse DOM
→ detect sections
→ classify sections
→ normalize and reshape
→ map to ACF payload
→ save with update_field()
→ render dynamically
→ verify and log
```

### Key principle
Never write raw crawl output directly into ACF.

Always:
1. fetch
2. parse
3. reshape
4. map
5. save

### Data boundary
- raw crawl data stays in crawl JSON
- normalized data stays in the internal canonical model
- ACF only receives final payload arrays

---

## 5. Required Access Checklist

### WordPress access
- WordPress admin login
- permission to install and activate plugins
- permission to install ACF Pro
- access to the target WordPress site
- access to `wp-content/plugins/`

### Development access
- local Docker environment
- WP-CLI access
- database access or phpMyAdmin
- optional SSH access to staging or production

### Source access
- one or more real source homepage URLs
- permission to crawl where needed
- proxy or scraping service access if sites block requests

### API access
- OpenRouter API key
- optional Firecrawl API key
- optional proxy/scraping API key

### Helpful extras
- 2 to 3 sample sites
- staging environment
- media library access
- logs and error visibility

---

## 6. Environment Variables

Store secrets outside code.

```text
WP_HOME=https://localhost:8080
WP_SITEURL=https://localhost:8080
DB_HOST=db:3306
DB_NAME=wordpress
DB_USER=wpuser
DB_PASSWORD=wppass

AI_PROVIDER=openrouter
OPENROUTER_API_KEY=...
OPENROUTER_MODEL=...

FIRECRAWL_API_KEY=...
USE_FIRECRAWL_FALLBACK=true

IMPORTER_DRY_RUN=true
IMPORTER_CRAWL_DELAY_MS=1000
IMPORTER_TIMEOUT_SECONDS=20
IMPORTER_RETRY_COUNT=2
IMPORTER_CONFIDENCE_THRESHOLD=0.8
```

### Secrets policy
- never commit keys to git
- never hardcode secrets in PHP files
- prefer environment variables for local and CI environments
- allow WordPress options only for non-sensitive toggles

---

## 7. Recommended File Structure

```text
acf-homepage-importer/
├── acf-homepage-importer.php
├── readme.txt
├── uninstall.php
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── includes/
│   ├── Core/
│   │   ├── Plugin.php
│   │   ├── Activator.php
│   │   ├── Deactivator.php
│   │   ├── Loader.php
│   │   └── Helpers.php
│   ├── Admin/
│   │   ├── AdminMenu.php
│   │   ├── ImportPage.php
│   │   ├── SettingsPage.php
│   │   └── HistoryPage.php
│   ├── CLI/
│   │   ├── TestCommand.php
│   │   ├── CrawlCommand.php
│   │   ├── ParseCommand.php
│   │   ├── ImportCommand.php
│   │   └── VerifyCommand.php
│   ├── Crawler/
│   │   ├── HttpClient.php
│   │   ├── UrlResolver.php
│   │   ├── HomepageDetector.php
│   │   ├── HtmlCrawler.php
│   │   └── AssetCollector.php
│   ├── Parser/
│   │   ├── DomParser.php
│   │   ├── SectionParser.php
│   │   ├── SectionClassifier.php
│   │   ├── SectionItemMapper.php
│   │   └── ContentNormalizer.php
│   ├── AI/
│   │   ├── AiClient.php
│   │   ├── PromptBuilder.php
│   │   └── ResponseValidator.php
│   ├── Schema/
│   │   ├── SectionSchemaRegistry.php
│   │   ├── AcfSchemaBuilder.php
│   │   └── AcfRegistrar.php
│   ├── Data/
│   │   ├── PageRepository.php
│   │   ├── ImportJobRepository.php
│   │   ├── ImportLogRepository.php
│   │   ├── DataSeeder.php
│   │   └── MediaSideloadService.php
│   ├── Template/
│   │   ├── HomepageTemplateRenderer.php
│   │   └── PartialLoader.php
│   ├── Verification/
│   │   ├── ContentVerifier.php
│   │   ├── DiffBuilder.php
│   │   └── VerificationReport.php
│   └── Support/
│       ├── Logger.php
│       ├── Validator.php
│       └── Exceptions.php
├── templates/
│   ├── homepage-dynamic.php
│   └── sections/
│       ├── hero.php
│       ├── services.php
│       ├── about.php
│       ├── cta.php
│       └── custom.php
├── storage/
│   ├── imports/
│   ├── scans/
│   └── reports/
└── docs/
    ├── implementation-notes.md
    └── developer-guide.md
```

---

## 8. Layer Responsibilities

### Core
Bootstraps the plugin and wires the service container or loader.

### Admin
Provides the WordPress UI:
- source URL input
- target page selection
- dry-run toggle
- download images toggle
- crawl / parse / import / verify buttons
- preview and report panels

### CLI
Provides testable commands:
- `test`
- `crawl`
- `parse`
- `import`
- `verify`

### Crawler
Responsibilities:
- HTML download
- URL normalization
- redirect handling
- timeout and retry policy
- Firecrawl fallback when needed

### Parser
Responsibilities:
- DOM traversal
- section boundary detection
- classification
- extraction
- fallback to `custom`

### AI
Responsibilities:
- uncertain section classification
- text cleanup
- alt text generation
- JSON validation

### Schema
Responsibilities:
- ACF field keys and layouts
- flexible content registration
- schema consistency

### Data
Responsibilities:
- ACF seeding
- media sideloading
- import job state
- logs and artifacts

### Template
Responsibilities:
- flexible content rendering
- section partial loading
- safe fallback rendering

### Verification
Responsibilities:
- compare imported vs parsed content
- detect missing data
- generate reports

---

## 9. Data Contracts

### Crawl result
```json
{
  "url": "https://example.com",
  "final_url": "https://example.com/",
  "title": "Example Site",
  "html": "<html>...</html>",
  "images": [],
  "links": []
}
```

### Parsed section result
```json
[
  {
    "type": "hero",
    "confidence": 0.95,
    "html": "<section>...</section>",
    "items": {
      "heading": "Smile Better",
      "subheading": "Modern orthodontics",
      "button_text": "Book Now",
      "button_link": "/contact"
    }
  }
]
```

### Canonical reshaped model
```json
{
  "type": "hero",
  "confidence": 0.95,
  "content": {
    "heading": "Smile Better",
    "subheading": "Modern orthodontics",
    "button": {
      "text": "Book Now",
      "link": "/contact"
    }
  },
  "assets": {
    "images": []
  },
  "notes": []
}
```

### ACF seed payload
```json
{
  "homepage_sections": [
    {
      "acf_fc_layout": "hero",
      "heading": "Smile Better",
      "subheading": "Modern orthodontics",
      "button_text": "Book Now",
      "button_link": "/contact"
    }
  ]
}
```

---

## 10. Best Practices

1. Use `update_field()` for ACF writes.
2. Do not use `update_post_meta()` for flexible content rows.
3. Store image fields as attachment IDs.
4. Preserve section order exactly.
5. Save JSON artifacts for every import.
6. Add dry-run support early.
7. Fallback unsupported sections to `custom`.
8. Keep AI optional and isolated behind one interface.
9. Reshape raw data before ACF mapping.
10. Use Firecrawl only as a fallback.
11. Keep field keys stable.
12. Never silently overwrite data.
13. Validate AI JSON before use.
14. Add retries, timeouts, and rate limiting.
15. Log every import step.

---

## 11. Local Development Setup

### Docker services
- MySQL
- WordPress Apache
- phpMyAdmin
- WP-CLI

### Typical local URLs
- WordPress: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

### Recommended workflow
1. Start Docker
2. Install WordPress
3. Install ACF Pro
4. Activate the plugin
5. Run CLI smoke tests
6. Test crawl and parse
7. Test dry-run
8. Test import
9. Test verification

---

## 12. Implementation Sequence

### Phase 1
- plugin shell
- admin UI
- settings and CLI test command

### Phase 2
- homepage source detection
- HTTP fetcher
- DOM parser
- asset collector

### Phase 3
- section parser
- section classifier
- AI fallback
- normalization and reshaping

### Phase 4
- ACF schema registration
- ACF payload builder
- data seeder
- media sideloading

### Phase 5
- dynamic homepage template
- section partials
- verification

### Phase 6
- logging
- rollback
- batch support
- hardening

---

## 13. Risk Areas

- JS-rendered pages
- Cloudflare or blocked pages
- malformed HTML
- duplicated media uploads
- ACF field key mismatches
- import re-runs and conflicts
- weak or inconsistent AI output

Mitigation:
- use fallback logic
- keep dry-run available
- store artifacts
- validate every stage
- separate raw, reshaped, and saved data

---

## 14. What Developers Need Before Starting

- WordPress admin access
- ACF Pro access
- source homepage URLs
- Docker environment
- WP-CLI access
- OpenRouter API key
- optional Firecrawl API key
- staging environment
- media library access

---

## 15. MVP vs Later Version

### MVP
- homepage-only
- 5 core section types
- crawl + parse + reshape + ACF save
- dry-run and verification
- logs and history
- optional Firecrawl fallback

### Later version
- expanded section catalog
- better JS support
- batch migration
- rollback automation
- human review workflow
- more advanced AI model routing

---

## 16. Final Developer Note

Treat this as a migration pipeline, not a scraper.

The safe order is:
- crawl
- parse
- reshape
- map
- save
- render
- verify

If the canonical model is clean, the rest of the system becomes much easier to maintain.
