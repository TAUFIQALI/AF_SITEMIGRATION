# ACF Homepage Importer — Run, Setup, and Testing Guide

This guide explains how to set up the project, what environment variables are needed, how to run it locally, and how to test it on WordPress and sample websites.

---

## 1. What You Need

### Required
- **WordPress** local or staging site
- **ACF Pro** installed and active
- **PHP 8.1 or 8.2**
- **MySQL 8**
- **Docker + Docker Compose**
- **WP-CLI**
- **Admin access** to WordPress
- **Plugin install permission**
- **One or more source homepage URLs**

### Optional but recommended
- **OpenRouter API key** for AI fallback
- **Firecrawl API key** for difficult or JS-heavy pages
- **phpMyAdmin** or database access
- **SSH access** to your staging server
- **2 to 3 real test websites**

---

## 2. Recommended Setup

### Local development stack
Use Docker for the WordPress environment.

Suggested services:
- WordPress
- MySQL
- phpMyAdmin
- WP-CLI container

### Typical local URLs
- WordPress: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

### Recommended plugin path
```text
wordpress/wp-content/plugins/acf-homepage-importer/
```

---

## 3. Environment Variables

Store secrets in `.env` or server environment variables.

```text
WP_HOME=https://localhost:8080
WP_SITEURL=https://localhost:8080
DB_HOST=db:3306
DB_NAME=wordpress
DB_USER=wpuser
DB_PASSWORD=wppass

AI_PROVIDER=openrouter
OPENROUTER_API_KEY=your_openrouter_key
OPENROUTER_MODEL=your_chosen_model

FIRECRAWL_API_KEY=your_firecrawl_key
USE_FIRECRAWL_FALLBACK=true

IMPORTER_DRY_RUN=true
IMPORTER_CRAWL_DELAY_MS=1000
IMPORTER_TIMEOUT_SECONDS=20
IMPORTER_RETRY_COUNT=2
IMPORTER_CONFIDENCE_THRESHOLD=0.8
```

### Environment rules
- do not commit secrets to git
- do not hardcode API keys in PHP files
- keep local and production values separate
- use WordPress options only for safe non-secret settings

---

## 4. How to Install on WordPress

### Option A — Local Docker setup
1. Start Docker Compose.
2. Open WordPress in the browser.
3. Complete WordPress installation.
4. Install and activate **ACF Pro**.
5. Copy the plugin into `wp-content/plugins/acf-homepage-importer/`.
6. Activate the plugin from WordPress admin.
7. Confirm the admin menu appears.

### Option B — Staging WordPress site
1. Upload the plugin to `wp-content/plugins/`.
2. Install and activate **ACF Pro**.
3. Activate the importer plugin.
4. Verify you have admin access.
5. Confirm the plugin menu and CLI access if available.

---

## 5. How to Run the Project

### Step 1: Start the environment
Bring up the WordPress stack.

### Step 2: Open WordPress admin
Log in as an administrator.

### Step 3: Activate required plugins
- ACF Pro
- ACF Homepage Importer

### Step 4: Open the importer screen
Go to the plugin’s admin page and enter:
- source homepage URL
- target page
- dry-run option
- image download option

### Step 5: Test the crawl flow
Run a preview crawl first.

### Step 6: Parse and preview data
Check the detected sections and confidence values.

### Step 7: Run import
Only after preview looks correct.

### Step 8: Verify output
Compare imported sections against the source and check the report.

---

## 6. How to Test with WP-CLI

### Basic test command
```bash
wp acf-homepage-importer test
```

### Crawl command
```bash
wp acf-homepage-importer crawl --url=https://example.com
```

### Parse command
```bash
wp acf-homepage-importer parse --url=https://example.com
```

### Import command
```bash
wp acf-homepage-importer import --url=https://example.com --page_id=123
```

### Verify command
```bash
wp acf-homepage-importer verify --page_id=123
```

---

## 7. What to Test First

### Test 1: Plugin loads correctly
- plugin activates
- admin menu appears
- CLI test command works

### Test 2: Crawl on a simple site
- use a static homepage
- confirm HTML is fetched
- confirm JSON artifact is saved

### Test 3: Parse sections
- confirm hero/about/cta/services/custom detection
- confirm section order is preserved

### Test 4: Dry run
- confirm no permanent writes happen
- confirm preview output is visible

### Test 5: Full import
- confirm ACF data is written
- confirm homepage renders dynamically
- confirm verification report is created

### Test 6: Difficult site test
- test a JS-heavy or blocked site
- confirm warning or Firecrawl fallback behavior

---

## 8. Sample Test Websites

Use a mix of these site types:

### Static HTML site
Good for testing the base crawler and parser.

### WordPress site with front-page template
Good for template detection and ACF migration behavior.

### JS-heavy site
Good for confirming warnings and fallback behavior.

### Blocked or protected site
Good for testing retry, timeout, and fallback handling.

---

## 9. Expected Local Storage

The project should save artifacts under:
```text
storage/imports/
storage/scans/
storage/reports/
```

Example import folder:
```text
storage/imports/import-001/
  crawl-result.json
  parsed-sections.json
  seed-payload.json
  verification-report.json
```

---

## 10. Troubleshooting

### Plugin does not appear
- confirm the plugin folder is in `wp-content/plugins/`
- check PHP syntax
- confirm WordPress is reading the correct path

### ACF fields do not save
- confirm ACF Pro is active
- confirm field keys are correct
- confirm `update_field()` is used with the full payload

### Crawl fails
- check source URL
- check timeout settings
- try another site
- enable Firecrawl fallback if needed

### Parsing fails
- confirm HTML is valid enough for DOM parsing
- test with a simpler site
- check section detection rules

### Import does not render
- confirm the homepage template is active
- confirm the flexible content field name matches
- confirm the section partial exists

---

## 11. Best Testing Rules

- always start with dry run
- test on simple sites before complex sites
- keep one source URL per test run
- save artifacts every time
- verify after every import
- do not skip rollback testing if template rewriting is enabled

---

## 12. Recommended Test Order

1. Local WordPress setup
2. ACF Pro activation
3. Plugin activation
4. CLI test command
5. Dry-run crawl
6. Parse output review
7. Full import on a simple site
8. Verification report
9. Difficult site with Firecrawl fallback
10. Cleanup and rerun

---

## 13. Final Checklist

- [ ] WordPress installed
- [ ] ACF Pro installed
- [ ] Plugin activated
- [ ] Environment variables configured
- [ ] OpenRouter API key added
- [ ] Firecrawl key added if needed
- [ ] Dry run works
- [ ] Crawl works
- [ ] Parse works
- [ ] Import works
- [ ] Verification works
- [ ] Logs and artifacts are saved
- [ ] Testing done on real websites
