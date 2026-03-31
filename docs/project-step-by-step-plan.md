# ACF Homepage Importer — Step-by-Step Project Plan

This plan is the practical execution order for the project team.
It follows the developer guide and the role files in `docs/agents/`.

---

## Phase 1 — Scope and Access

### Step 1: Confirm project scope
**Owner:** Project Orchestrator

- Confirm homepage-only MVP
- Confirm supported section types
- Confirm ACF Pro requirement
- Confirm dry-run requirement
- Confirm repeated-import conflict policy

### Step 2: Confirm access and secrets
**Owner:** Project Orchestrator

- WordPress admin access
- plugin install permission
- ACF Pro access
- Docker environment
- WP-CLI access
- OpenRouter API key
- optional Firecrawl API key
- staging or local site access

---

## Phase 2 — Plugin Foundation

### Step 3: Build plugin shell
**Owner:** WordPress Plugin Developer

- main plugin file
- activation / deactivation hooks
- loader and bootstrap
- admin menu

### Step 4: Build admin UI
**Owner:** WordPress Plugin Developer

- source URL input
- target page selector
- dry-run toggle
- download images toggle
- crawl / parse / import / verify buttons

### Step 5: Add CLI test command
**Owner:** WordPress Plugin Developer

- `wp acf-homepage-importer test`
- confirm plugin loads correctly

---

## Phase 3 — ACF Schema

### Step 6: Define ACF layout structure
**Owner:** ACF Specialist

- create `homepage_sections`
- define layouts for hero, services, about, cta, custom
- map required and optional fields

### Step 7: Register schema programmatically
**Owner:** ACF Specialist

- build schema registry
- register field keys
- confirm ACF loads the field group

---

## Phase 4 — Crawl and Detect Source

### Step 8: Detect homepage source
**Owner:** Crawler and Parser Engineer

- detect raw homepage URL
- detect WordPress homepage template
- detect page builders
- generate homepage assessment report

### Step 9: Fetch homepage HTML
**Owner:** Crawler and Parser Engineer

- use `wp_remote_get()`
- add timeout and retries
- add crawl delay
- support Firecrawl fallback if required

### Step 10: Parse DOM and collect assets
**Owner:** Crawler and Parser Engineer

- parse with `DOMDocument`
- collect images and links
- resolve relative URLs

---

## Phase 5 — Parse and Classify

### Step 11: Split sections
**Owner:** Crawler and Parser Engineer

- detect section boundaries
- preserve original order
- extract section HTML chunks

### Step 12: Classify sections
**Owner:** Crawler and Parser Engineer

- classify as hero, services, about, cta, custom
- assign confidence scores
- fallback to custom if uncertain

### Step 13: AI fallback and normalization
**Owner:** AI Integration Agent

- use AI only for low-confidence items
- normalize text and button labels
- generate missing alt text
- reshape raw data into canonical structure

---

## Phase 6 — Map to ACF

### Step 14: Build canonical mapper
**Owner:** WordPress Plugin Developer + ACF Specialist

- map canonical data to ACF payload
- convert to `acf_fc_layout`
- validate required fields
- assemble full array in memory first

### Step 15: Define import conflict behavior
**Owner:** Project Orchestrator + Data/Verification Agent

- replace vs append vs skip
- default to replace on re-import
- log changes for each run

---

## Phase 7 — Store and Seed

### Step 16: Store import jobs and logs
**Owner:** Data, Storage, and Verification Agent

- save job metadata
- save logs
- save artifact JSON files

### Step 17: Seed ACF content
**Owner:** Data, Storage, and Verification Agent

- write full flexible content array once
- use `update_field()`
- support dry-run mode

### Step 18: Sideload images
**Owner:** Data, Storage, and Verification Agent

- download remote images
- store attachment IDs
- handle failures gracefully

---

## Phase 8 — Render and Verify

### Step 19: Build homepage template
**Owner:** WordPress Plugin Developer

- create dynamic homepage template
- render rows from ACF
- load section partials

### Step 20: Build verification checks
**Owner:** Data, Storage, and Verification Agent

- compare parsed data against saved data
- produce pass/warn/fail report
- save verification JSON

### Step 21: Add preview and dry-run inspection
**Owner:** Project Orchestrator + Data/Verification Agent

- show payload before save
- show confidence warnings
- allow human review

---

## Phase 9 — Hardening

### Step 22: Add retry and rate limiting
**Owner:** QA and DevOps Agent

- timeout checks
- retry logic
- crawl delay
- polite request handling

### Step 23: Validate JS-heavy site limitations
**Owner:** QA and DevOps Agent

- static site test
- JS-heavy site test
- confirm warning path

### Step 24: Add cleanup and rollback
**Owner:** QA and DevOps Agent + Data/Verification Agent

- remove failed imports
- cleanup media when enabled
- restore original template if needed

### Step 25: Add batch support
**Owner:** QA and DevOps Agent

- process multiple sites
- store per-site results
- support dry-run and force modes

---

## Release Checklist

- [ ] Plugin activates cleanly
- [ ] Admin page works
- [ ] CLI test works
- [ ] Crawl works
- [ ] Parse works
- [ ] AI fallback works
- [ ] ACF schema registers
- [ ] Data seeds correctly
- [ ] Homepage renders dynamically
- [ ] Verification report exists
- [ ] Dry-run works
- [ ] Logs and artifacts are stored
- [ ] Rollback and batch support are available if needed
