# ACF Homepage Importer — Full Beginner-Friendly Implementation Plan

## 1. Project Goal

Build a WordPress plugin that lets an admin:

1. enter a source homepage URL,
2. crawl that homepage,
3. detect and parse homepage sections,
4. map the extracted content into ACF layouts,
5. save the content into WordPress through ACF,
6. render the homepage dynamically from ACF data.

This project is homepage-only for now.

---

## 2. Scope for This 3-Day MVP

### In scope

* homepage URL input
* homepage crawl only
* homepage section detection only
* support a small set of section types
* create/register ACF field structure
* save extracted content into WordPress
* render homepage dynamically from ACF
* basic verification/logging

### Out of scope

* full-site crawl
* blog pages or inner pages
* batch importing many sites
* advanced AI classification
* theme-wide PHP rewriting engine
* rollback system
* all possible homepage section variations

### Supported MVP section types

* `hero`
* `services`
* `about`
* `cta`
* `custom`

---

## 3. Recommended Product Strategy

Use:

**ACF Pro + Custom Homepage Template**

Do not use the default WordPress builder as the core migration engine.

### Why

* imported content needs structured fields
* section order matters
* repeatable layouts are needed
* automatic seeding is easier with ACF
* rendering is predictable with ACF flexible content

---

## 4. Access and Requirements Checklist

Before coding starts, confirm all of these.

### Required access

* WordPress admin login
* permission to install/activate plugins
* permission to install/activate ACF Pro
* target staging or local WordPress site
* access to `wp-content/plugins/`
* one real source homepage URL

### Strongly recommended

* SSH access
* WP-CLI installed
* phpMyAdmin or DB access
* 2–3 sample homepage URLs for testing

### Optional

* AI API key for low-confidence section classification later
* proxy/scraping service only if sites block requests

### Required plugin dependency

* **ACF Pro**

Reason: the implementation uses **Flexible Content** for homepage sections.

---

## 4.1 Docker Development Setup (RECOMMENDED)

Since you are facing PHP version issues on Ubuntu, you should use Docker for development.

### Why Docker

* isolates PHP version
* avoids system conflicts
* consistent environment across machines
* easy reset and rebuild
* built-in MySQL + WordPress + WP-CLI

---

### Folder Structure (Docker)

```text
acf-homepage-importer-dev/
├── docker-compose.yml
├── wordpress/
│   └── wp-content/
│       └── plugins/
│           └── acf-homepage-importer/
│               ├── acf-homepage-importer.php
│               └── ...
```

Your plugin lives here:

```text
wordpress/wp-content/plugins/acf-homepage-importer/
```

---

### docker-compose.yml

```yaml
services:
  db:
    image: mysql:8.0
    container_name: acf_importer_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wpuser
      MYSQL_PASSWORD: wppass
      MYSQL_ROOT_PASSWORD: rootpass
    command: --default-authentication-plugin=mysql_native_password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3307:3306"

  wordpress:
    image: wordpress:php8.1-apache
    container_name: acf_importer_wp
    restart: unless-stopped
    depends_on:
      - db
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DB_USER: wpuser
      WORDPRESS_DB_PASSWORD: wppass
    ports:
      - "8080:80"
    volumes:
      - ./wordpress:/var/www/html

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: acf_importer_pma
    restart: unless-stopped
    depends_on:
      - db
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
      MYSQL_ROOT_PASSWORD: rootpass
    ports:
      - "8081:80"

  wpcli:
    image: wordpress:cli
    container_name: acf_importer_wpcli
    depends_on:
      - wordpress
      - db
    user: "33:33"
    working_dir: /var/www/html
    volumes:
      - ./wordpress:/var/www/html
    entrypoint: ["tail", "-f", "/dev/null"]

volumes:
  db_data:
```

---

### Start Docker

```bash
docker compose up -d
```

---

### Access URLs

* WordPress: [http://localhost:8080](http://localhost:8080)
* phpMyAdmin: [http://localhost:8081](http://localhost:8081)

---

### WordPress Setup

After opening localhost:8080:

* create admin user
* login to dashboard

---

### Install ACF Pro

Option 1 (recommended):

* upload via WP Admin → Plugins → Add New → Upload

Option 2:

* manually extract into:

```text
wordpress/wp-content/plugins/advanced-custom-fields-pro/
```

---

### WP-CLI Usage

Enter container:

```bash
docker exec -it acf_importer_wpcli bash
```

Run commands:

```bash
wp plugin list --allow-root
wp plugin activate acf-homepage-importer --allow-root
```

---

### Change PHP Version

Modify this line:

```yaml
image: wordpress:php8.1-apache
```

Example:

```yaml
image: wordpress:php8.2-apache
```

Then restart:

```bash
docker compose down
docker compose up -d
```

---

### Common Commands

Start:

```bash
docker compose up -d
```

Stop:

```bash
docker compose down
```

Logs:

```bash
docker compose logs -f
```

Enter WP container:

```bash
docker exec -it acf_importer_wp bash
```

---

### Recommendation

Always develop this plugin inside Docker.
Never rely on system PHP for this project.

---

Before coding starts, confirm all of these.

### Required access

* WordPress admin login
* permission to install/activate plugins
* permission to install/activate ACF Pro
* target staging or local WordPress site
* access to `wp-content/plugins/`
* one real source homepage URL

### Strongly recommended

* SSH access
* WP-CLI installed
* phpMyAdmin or DB access
* 2–3 sample homepage URLs for testing

### Optional

* AI API key for low-confidence section classification later
* proxy/scraping service only if sites block requests

### Required plugin dependency

* **ACF Pro**

Reason: the implementation uses **Flexible Content** for homepage sections.

---

## 5. Final MVP Workflow

```text
Admin enters homepage URL
→ plugin fetches homepage HTML
→ plugin extracts sections
→ plugin maps sections into known ACF layouts
→ plugin registers required ACF schema
→ plugin seeds homepage data into target page
→ homepage renders dynamically using ACF
→ plugin shows verification report
```

---

## 6. Suggested Folder Structure

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
    └── implementation-notes.md
```

---

## 7. What Each Layer Does

### Core

Bootstraps the plugin and wires everything together.

### Admin

Provides the WordPress admin UI:

* source URL input
* target page selection
* import button
* verify button
* import history

### CLI

Lets you run the same steps through terminal for faster testing.

### Crawler

Fetches homepage HTML and collects assets like images and links.

### Parser

Splits homepage HTML into logical sections and maps content into normalized fields.

### Schema

Defines ACF layouts and registers the field group programmatically.

### Data

Seeds the extracted content into the selected WordPress page through ACF.

### Template

Renders the homepage using ACF flexible content rows.

### Verification

Checks imported data quality and stores reports.

---

## 8. Core Classes and Responsibilities

### `Plugin`

Main orchestrator.

* registers admin hooks
* registers CLI commands
* loads services

### `AdminMenu`

Adds plugin menu items.

### `ImportPage`

Shows the import form and triggers the import flow.

### `HttpClient`

Fetches HTML using the WordPress HTTP API.

### `HomepageDetector`

Normalizes and validates the homepage URL.

### `HtmlCrawler`

Downloads homepage HTML.

### `AssetCollector`

Extracts image URLs, links, phone numbers, and useful metadata.

### `DomParser`

Loads HTML into `DOMDocument` and provides helper queries.

### `SectionParser`

Breaks the homepage into section blocks.

### `SectionClassifier`

Classifies blocks as `hero`, `services`, `about`, `cta`, or `custom`.

### `SectionItemMapper`

Maps raw extracted items into ACF-ready field names.

### `ContentNormalizer`

Cleans and normalizes text, phone numbers, URLs, and button labels.

### `SectionSchemaRegistry`

Defines the homepage section layouts and their ACF fields.

### `AcfSchemaBuilder`

Builds the full field group array.

### `AcfRegistrar`

Registers the field group in ACF.

### `PageRepository`

Finds the target page or homepage.

### `DataSeeder`

Writes structured homepage content into ACF fields.

### `MediaSideloadService`

Downloads and imports images into the WordPress media library.

### `HomepageTemplateRenderer`

Renders the homepage dynamically from ACF.

### `ContentVerifier`

Checks that seeded content matches the extracted content closely enough.

### `Logger`

Stores logs and reports.

---

## 9. ACF Field Design for MVP

Create one top-level field:

```text
homepage_sections
```

Type:

* Flexible Content

### Layout: `hero`

Fields:

* `heading`
* `subheading`
* `button_text`
* `button_link`
* `background_image`

### Layout: `services`

Fields:

* `heading`
* `items` (Repeater)

  * `title`
  * `description`
  * `image`
  * `link`

### Layout: `about`

Fields:

* `heading`
* `description`
* `image`
* `button_text`
* `button_link`

### Layout: `cta`

Fields:

* `heading`
* `description`
* `button_text`
* `button_link`
* `phone`

### Layout: `custom`

Fields:

* `heading`
* `raw_html`
* `notes`

---

## 10. JSON Contracts You Should Use

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

### Parsed sections

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

### Seed payload

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

## 11. Storage Strategy

For each import, save artifacts under `storage/`.

Example:

```text
storage/imports/import-001/
  crawl-result.json
  parsed-sections.json
  seed-payload.json
  verification-report.json
```

This is critical for debugging during the 3-day timeline.

---

## 12. Rendering Pattern

The homepage should be rendered from ACF flexible content rows.

Example rendering logic:

```php
if (have_rows('homepage_sections', $page_id)) :
    while (have_rows('homepage_sections', $page_id)) : the_row();
        $layout = get_row_layout();

        if ($layout === 'hero') {
            include plugin_dir_path(__FILE__) . '../templates/sections/hero.php';
        } elseif ($layout === 'services') {
            include plugin_dir_path(__FILE__) . '../templates/sections/services.php';
        } elseif ($layout === 'about') {
            include plugin_dir_path(__FILE__) . '../templates/sections/about.php';
        } elseif ($layout === 'cta') {
            include plugin_dir_path(__FILE__) . '../templates/sections/cta.php';
        } else {
            include plugin_dir_path(__FILE__) . '../templates/sections/custom.php';
        }
    endwhile;
endif;
```

---

## 13. CLI Commands for Faster Development

Add these WP-CLI commands first:

```bash
wp acf-homepage-importer test
wp acf-homepage-importer crawl --url=https://example.com
wp acf-homepage-importer parse --url=https://example.com
wp acf-homepage-importer import --url=https://example.com --page_id=123
wp acf-homepage-importer verify --page_id=123
```

### What they should do

* `test`: confirm plugin loads correctly
* `crawl`: fetch homepage HTML and save crawl JSON
* `parse`: extract homepage sections and save parsed JSON
* `import`: register schema if needed, seed content, and attach images
* `verify`: compare imported content with parsed content

---

## 14. Section Detection Rules for MVP

### Hero

Signals:

* near top of page
* contains a main `h1`
* contains CTA button or primary link
* contains large image or banner area

### Services

Signals:

* repeated cards or repeated columns
* multiple similar titles/descriptions
* words like services, treatments, braces, invisalign

### About

Signals:

* image + text block
* heading includes words like about, practice, meet

### CTA

Signals:

* short call-to-action band
* heading + button
* phone number or appointment message

### Custom

Use when none of the above is reliable.

---

## 15. Beginner Rules You Must Follow

1. Use `update_field()` for ACF data writes.
2. Do not use `update_post_meta()` for flexible content row creation.
3. For image fields, save WordPress attachment IDs, not raw image URLs.
4. Preserve section order exactly as found.
5. Save JSON artifacts for every import.
6. Add `dry-run` support before risky operations.
7. Skip unsupported sections safely into `custom`.
8. Do not attempt full template rewrite in version 1.

---

## 16. Step-by-Step Build Order

### Step 1 — Plugin shell

Create:

* main plugin file
* core bootstrap
* activation/deactivation files
* admin menu page

Goal:
plugin activates without errors.

### Step 2 — CLI test command

Add:

* `wp acf-homepage-importer test`

Goal:
confirm plugin and WP-CLI integration work.

### Step 3 — Manual ACF proof of concept

Manually create a temporary flexible content field and one `hero` layout.

Goal:
understand:

* `get_field()`
* `update_field()`
* `have_rows()`
* `get_sub_field()`

### Step 4 — Build homepage fetcher

Implement:

* `HttpClient`
* `HomepageDetector`
* `HtmlCrawler`

Goal:
save `crawl-result.json` for a homepage URL.

### Step 5 — Build parser

Implement:

* `DomParser`
* `SectionParser`
* `SectionClassifier`

Goal:
save `parsed-sections.json` with only the MVP section types.

### Step 6 — Build mapper

Implement:

* `SectionItemMapper`
* `ContentNormalizer`

Goal:
generate clean ACF-ready structured section data.

### Step 7 — Build schema registration

Implement:

* `SectionSchemaRegistry`
* `AcfSchemaBuilder`
* `AcfRegistrar`

Goal:
register the homepage flexible content field programmatically.

### Step 8 — Build data seeder

Implement:

* `PageRepository`
* `DataSeeder`

Goal:
write extracted data into the target page.

### Step 9 — Build rendering layer

Implement:

* `HomepageTemplateRenderer`
* section partials

Goal:
render imported homepage dynamically using ACF.

### Step 10 — Add image sideloading

Implement:

* `MediaSideloadService`

Goal:
import remote images into the media library and store attachment IDs.

### Step 11 — Add verification

Implement:

* `ContentVerifier`
* `DiffBuilder`
* `VerificationReport`

Goal:
show basic pass/fail and warnings.

### Step 12 — Add admin import action

Wire the admin form to:

* crawl
* parse
* seed
* verify

Goal:
run end-to-end import from WordPress admin.

---

## 17. Exact 3-Day Plan

## Day 1 — Foundation + Crawl + Parse

### Deliverables

* plugin shell works
* admin page exists
* CLI test command works
* homepage HTML can be fetched
* homepage sections can be parsed into JSON

### Files to focus on

* `acf-homepage-importer.php`
* `includes/Core/Plugin.php`
* `includes/Admin/AdminMenu.php`
* `includes/Admin/ImportPage.php`
* `includes/CLI/TestCommand.php`
* `includes/Crawler/HttpClient.php`
* `includes/Crawler/HomepageDetector.php`
* `includes/Crawler/HtmlCrawler.php`
* `includes/Parser/DomParser.php`
* `includes/Parser/SectionParser.php`
* `includes/Parser/SectionClassifier.php`

### Day 1 exit criteria

Given a real homepage URL, the plugin saves:

* crawl JSON
* parsed section JSON

## Day 2 — Schema + Seed + Render

### Deliverables

* ACF schema registers automatically
* parsed sections map into ACF payload
* content seeds into target page
* homepage renders from ACF dynamically

### Files to focus on

* `includes/Parser/SectionItemMapper.php`
* `includes/Parser/ContentNormalizer.php`
* `includes/Schema/SectionSchemaRegistry.php`
* `includes/Schema/AcfSchemaBuilder.php`
* `includes/Schema/AcfRegistrar.php`
* `includes/Data/PageRepository.php`
* `includes/Data/DataSeeder.php`
* `includes/Template/HomepageTemplateRenderer.php`
* `templates/homepage-dynamic.php`
* `templates/sections/*.php`

### Day 2 exit criteria

A target page/homepage shows imported `hero`, `services`, `about`, `cta`, or `custom` sections from ACF.

## Day 3 — Images + Verification + Hardening

### Deliverables

* remote images import into media library
* verification report works
* logs/history visible
* dry-run mode added
* tested on 2–3 real homepages

### Files to focus on

* `includes/Data/MediaSideloadService.php`
* `includes/Verification/ContentVerifier.php`
* `includes/Verification/DiffBuilder.php`
* `includes/Verification/VerificationReport.php`
* `includes/Data/ImportJobRepository.php`
* `includes/Data/ImportLogRepository.php`
* `includes/Support/Logger.php`
* `includes/CLI/ImportCommand.php`
* `includes/CLI/VerifyCommand.php`

### Day 3 exit criteria

The homepage importer works end-to-end on real source homepages with basic logs and verification.

---

## 18. Minimum Admin UI

The first admin page should contain:

### Fields

* source homepage URL
* target page dropdown
* checkbox: download images
* checkbox: dry run

### Buttons

* Crawl
* Parse
* Import
* Verify

### Output areas

* crawl status
* detected section list
* seed payload preview
* verification summary

Keep the UI simple.

---

## 19. Main Risks

### Risk 1 — messy HTML

Different websites structure homepages very differently.

### Risk 2 — wrong section classification

A block may look like services on one site and something else on another.

### Risk 3 — image handling

Some image URLs may be lazy-loaded, relative, or blocked.

### Risk 4 — time pressure

Trying to support too many layouts will break the 3-day timeline.

### Risk 5 — missing ACF Pro

Flexible Content support depends on having the right ACF features available.

---

## 20. Final Recommendation

For this 3-day homepage-only version, the correct architecture is:

```text
Homepage URL
→ crawl HTML
→ parse homepage sections
→ map to ACF layouts
→ register homepage schema
→ seed target page
→ render dynamically from ACF
→ verify
```

Build only the homepage importer first.
Do not attempt a generic full-site migration engine yet.

---

## 21. Immediate Next Step

Start by implementing this exact first slice:

1. plugin shell
2. admin page with URL input
3. `wp acf-homepage-importer test`
4. homepage fetcher
5. section parser for `hero`, `services`, `about`, `cta`, `custom`

Once that works, move to ACF schema + seeding.
