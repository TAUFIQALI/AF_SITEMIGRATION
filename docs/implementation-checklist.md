# ACF Homepage Importer — Implementation Checklist

Use this checklist to move the project from setup to release.

---

## Phase 1 — Setup

- [ ] Confirm WordPress admin access
- [ ] Confirm ACF Pro access
- [ ] Confirm Docker environment
- [ ] Confirm WP-CLI access
- [ ] Confirm source homepage URLs
- [ ] Confirm OpenRouter API key
- [ ] Confirm optional Firecrawl API key
- [ ] Confirm staging environment

---

## Phase 2 — Plugin Foundation

- [ ] Create plugin bootstrap file
- [ ] Register activation and deactivation hooks
- [ ] Add admin menu
- [ ] Create import page UI
- [ ] Register CLI test command
- [ ] Confirm plugin activates without errors

---

## Phase 3 — Crawling

- [ ] Add homepage source detection
- [ ] Detect existing WordPress templates
- [ ] Implement `wp_remote_get()` fetcher
- [ ] Add timeout and retry behavior
- [ ] Add crawl delay support
- [ ] Add Firecrawl fallback for difficult pages
- [ ] Save crawl JSON artifact

---

## Phase 4 — Parsing and Classification

- [ ] Parse HTML into `DOMDocument`
- [ ] Detect section boundaries
- [ ] Classify sections into supported types
- [ ] Add confidence scoring
- [ ] Add AI fallback for ambiguous sections
- [ ] Add content normalization and reshaping
- [ ] Save parsed-sections JSON artifact

---

## Phase 5 — ACF Schema and Mapping

- [ ] Define `homepage_sections`
- [ ] Register ACF field group programmatically
- [ ] Confirm field keys are stable
- [ ] Build canonical internal model
- [ ] Map canonical data to ACF payload
- [ ] Prepare one complete `update_field()` payload

---

## Phase 6 — Seeding and Media

- [ ] Implement data seeder
- [ ] Write the complete flexible content array once
- [ ] Add image sideloading
- [ ] Store attachment IDs in ACF fields
- [ ] Save seed payload JSON artifact
- [ ] Store job logs and import history

---

## Phase 7 — Rendering

- [ ] Create homepage dynamic template
- [ ] Create section partials
- [ ] Render `hero`
- [ ] Render `services`
- [ ] Render `about`
- [ ] Render `cta`
- [ ] Render `custom`

---

## Phase 8 — Verification

- [ ] Add content verifier
- [ ] Compare parsed data vs saved data
- [ ] Produce pass/warn/fail report
- [ ] Add dry-run preview
- [ ] Save verification JSON artifact

---

## Phase 9 — Hardening

- [ ] Add rollback support for template-based sites
- [ ] Add batch support for multiple sites
- [ ] Add rate limiting and better retries
- [ ] Add cleanup for failed imports
- [ ] Confirm JS-heavy site warning path

---

## Release Criteria

- [ ] Plugin installs and activates cleanly
- [ ] Admin import page works
- [ ] Crawl step works
- [ ] Parse step works
- [ ] AI fallback works
- [ ] ACF payload writes correctly
- [ ] Homepage renders dynamically
- [ ] Verification report is generated
- [ ] Dry-run mode works
- [ ] Logs and artifacts are stored
