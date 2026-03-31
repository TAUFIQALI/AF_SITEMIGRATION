# ACF Homepage Importer — Step-by-Step Project Plan

This plan uses the agent roles defined in the `docs/agents/` folder.
It is organized in the order the project should be built so the team can move from foundation to import, then to rendering and verification.

## Requirements Alignment Review

After reviewing the developer war room guide, the plan needs these updates:

- Add homepage discovery for existing WordPress sites, including `front-page.php` / theme assessment.
- Add page builder detection and a clear JS-rendered-site warning path.
- Add template rewrite and rollback as an explicit later phase for source WordPress sites.
- Expand the section catalog beyond the MVP five layouts when the project moves from pilot to bulk migration.
- Treat field keys, confidence thresholds, preview-before-save, retry logic, and cleanup as required hardening items.
- Add batch or multi-site execution only after the single-site workflow is stable.

---

## Project Principles

- Homepage-only for MVP
- Use ACF Pro Flexible Content for all imported sections
- Use WordPress native storage for content and job state
- Use AI only where rule-based parsing is uncertain
- Keep the AI prompt/output format centralized
- Save crawl and parse artifacts as JSON files
- Preserve section order exactly as detected
- Support dry-run mode before saving data
- Treat JS-rendered sites as a known limitation unless a headless-browser path is added later
- Support both source-URL crawl mode and existing-WordPress theme discovery mode
- Keep ACF field keys and layout keys consistent for programmatic writes

---

## Phase 1 — Define Scope and Ownership

### Step 1: Confirm MVP boundaries
**Owner:** Project Orchestrator

**Tasks:**
- Confirm the project is homepage-only
- Confirm core MVP section types: `hero`, `services`, `about`, `cta`, `custom`
- Confirm the extended migration catalog for later rollout: `providers`, `testimonials`, `before_after`, `locations`, `insurance`, `technology`, `faq`, `map`, `custom`
- Confirm that ACF Pro is required
- Confirm dry-run is mandatory
- Confirm conflict behavior for repeated imports
- Confirm whether the current source is a raw homepage URL or an existing WordPress theme template

**Output:**
- Final scope note
- Risk list
- Assumptions list

**Done when:**
- Everyone agrees on what is in scope and what is not

---

### Step 2: Define the agent handoff sequence
**Owner:** Project Orchestrator

**Tasks:**
- Define which agent works first, second, and so on
- Define the inputs and outputs for each agent
- Define review checkpoints after each phase

**Output:**
- Agent execution order
- Handoff checklist

**Done when:**
- The team can follow the plan without guessing responsibilities

---

## Phase 2 — Build the WordPress Plugin Foundation

### Step 3: Create the plugin shell
**Owner:** WordPress Plugin Developer

**Tasks:**
- Create the main plugin bootstrap file
- Add activation and deactivation hooks
- Register the loader/bootstrap class
- Add basic logging hooks

**Output:**
- Installable plugin skeleton

**Done when:**
- The plugin activates without errors

---

### Step 4: Build the admin menu and UI shell
**Owner:** WordPress Plugin Developer

**Tasks:**
- Add the admin menu
- Create the import page
- Add fields for source URL and target page
- Add checkboxes for `dry run` and `download images`
- Add buttons for Crawl, Parse, Import, and Verify

**Output:**
- Working admin page UI

**Done when:**
- An admin can open the importer screen in WordPress

---

### Step 5: Add WP-CLI test command
**Owner:** WordPress Plugin Developer

**Tasks:**
- Register `wp acf-homepage-importer test`
- Make the command confirm plugin availability
- Add basic CLI output for debugging

**Output:**
- CLI smoke-test command

**Done when:**
- A terminal user can confirm the plugin is loaded

---

## Phase 3 — Define ACF Structure

### Step 6: Design the homepage ACF schema
**Owner:** ACF Specialist

**Tasks:**
- Define the `homepage_sections` Flexible Content field
- Define each layout and subfield
- Decide which fields are required and optional
- Prepare field names and field keys

**Output:**
- ACF schema specification

**Done when:**
- Every supported section has a known ACF layout

---

### Step 7: Implement ACF registration
**Owner:** ACF Specialist

**Tasks:**
- Build the schema registration class
- Register field groups programmatically
- Ensure field keys are stored and referenced correctly
- Verify the field group loads in ACF

**Output:**
- Programmatic ACF registration

**Done when:**
- The homepage field group appears and can be saved reliably

---

## Phase 4 — Crawl the Source Homepage

### Phase 4A — Discover the Source Homepage Template

### Step 8: Detect the homepage source and template type
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Detect whether the source is a static homepage URL or an existing WordPress theme homepage
- If the source is WordPress, identify `front-page.php`, `home.php`, or `index.php`
- Detect common page builders and flag them early
- Produce a homepage assessment report before parsing begins

**Output:**
- Homepage assessment JSON

**Done when:**
- The system can explain where the homepage content is coming from and whether it can be processed safely

### Step 9: Build the HTTP fetcher
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Implement `wp_remote_get()` fetching
- Add timeout, retry, and delay support
- Handle redirects and final URL detection
- Detect failure states clearly

**Output:**
- Crawl response object

**Done when:**
- A real homepage HTML response can be fetched reliably

---

### Step 10: Parse HTML into a DOM tree
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Load HTML into `DOMDocument`
- Normalize encoding and malformed markup
- Create helper methods for element queries
- Identify top-level content blocks

**Output:**
- DOM parsing layer

**Done when:**
- The raw HTML becomes searchable DOM structure

---

### Step 11: Collect assets and metadata
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Extract image URLs
- Extract relevant links
- Capture title, headings, and useful page metadata
- Resolve relative URLs

**Output:**
- Asset map and metadata collection

**Done when:**
- The crawler can prepare all source assets for later processing

---

## Phase 5 — Detect and Classify Sections

### Step 12: Split the homepage into section blocks
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Detect logical section boundaries
- Ignore obvious nav/footer noise where possible
- Preserve the original order
- Extract section HTML chunks

**Output:**
- Section block list

**Done when:**
- The page is divided into reusable chunks

---

### Step 13: Classify each section
**Owner:** Crawler and Parser Engineer

**Tasks:**
- Classify blocks as `hero`, `services`, `about`, `cta`, or `custom`
- Produce confidence scores
- Fallback to `custom` when unsure
- Flag JS-heavy or ambiguous layouts

**Output:**
- Parsed sections JSON

**Done when:**
- Every detected block has a type and confidence score

---

### Step 14: Add AI fallback for uncertain sections
**Owner:** AI Integration Agent

**Tasks:**
- Define the prompt template
- Send only section chunks, not the full page
- Require JSON-only responses
- Use AI only when rule-based confidence is low

**Output:**
- AI classification service

**Done when:**
- Ambiguous sections get a controlled AI-assisted fallback

---

### Step 15: Add content normalization
**Owner:** AI Integration Agent

**Tasks:**
- Clean messy text
- Normalize button labels and headings
- Generate alt text where needed
- Return clean structured content for mapping

**Output:**
- Normalized section payloads

**Done when:**
- Raw scraped content is converted into cleaner import data

---

## Phase 6 — Map Parsed Data into ACF Payloads

### Step 16: Build the section mapper
**Owner:** WordPress Plugin Developer + ACF Specialist

**Tasks:**
- Map parsed sections into ACF-ready arrays
- Convert each section into the correct `acf_fc_layout`
- Validate required fields before saving
- Make sure the final payload is assembled in memory first

**Output:**
- ACF payload builder

**Done when:**
- The full homepage payload is ready in one structured array

---

### Step 17: Define import conflict behavior
**Owner:** Project Orchestrator + Data/Verification Agent

**Tasks:**
- Decide replace vs append vs skip behavior
- Default to replacing the full homepage sections on re-import
- Log what changed during each run

**Output:**
- Conflict policy

**Done when:**
- Re-imports have a predictable result

---

## Phase 7 — Store Jobs, Logs, and Artifacts

### Step 18: Set up import job storage
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Store job metadata in WordPress-native storage
- Track status, timestamps, URL, page ID, and errors
- Keep a history of import runs

**Output:**
- Import job records

**Done when:**
- The system remembers what happened during each run

---

### Step 19: Save JSON artifacts
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Save crawl-result JSON
- Save parsed-sections JSON
- Save seed-payload JSON
- Save verification-report JSON

**Output:**
- Artifact files on disk

**Done when:**
- Every import can be audited later

---

### Step 20: Build logging and error tracking
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Log crawl errors
- Log parse errors
- Log sideload failures
- Log verification warnings

**Output:**
- Central import log data

**Done when:**
- Failures are visible and traceable

---

## Phase 8 — Seed ACF Data into WordPress

### Step 21: Build the data seeder
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Write the complete `homepage_sections` value once
- Use `update_field()` for ACF data
- Do not loop and overwrite sections one by one
- Support dry-run mode

**Output:**
- ACF seeding service

**Done when:**
- The target page receives the full section array correctly

---

### Step 22: Add media sideloading
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Download remote images into the media library
- Handle relative URLs and blocked URLs where possible
- Store attachment IDs in ACF fields
- Generate missing alt text if available

**Output:**
- Media import service

**Done when:**
- Imported sections can display local WordPress media

---

## Phase 9 — Render the Homepage Dynamically

### Step 23: Build the dynamic homepage template
**Owner:** WordPress Plugin Developer

**Tasks:**
- Create the homepage dynamic template file
- Loop through `homepage_sections`
- Load the correct section partial for each layout
- Keep rendering logic simple and predictable

**Output:**
- Homepage template renderer

**Done when:**
- Imported ACF data renders on the front end

---

### Step 24: Build section partials
**Owner:** WordPress Plugin Developer + ACF Specialist

**Tasks:**
- Create partials for `hero`, `services`, `about`, `cta`, and `custom`
- Make the partials accept ACF data cleanly
- Keep styling isolated from data handling

**Output:**
- Section template partials

**Done when:**
- Each layout renders with the expected markup

---

## Phase 10 — Verify and Review

### Step 25: Build verification checks
**Owner:** Data, Storage, and Verification Agent

**Tasks:**
- Compare imported content against parsed content
- Check section counts and section types
- Highlight missing images or missing text
- Produce pass/warn/fail results

**Output:**
- Verification engine

**Done when:**
- The team can tell whether the import was successful

---

### Step 26: Add preview and dry-run inspection
**Owner:** Data, Storage, and Verification Agent + Project Orchestrator

**Tasks:**
- Display the seed payload before saving
- Show low-confidence items clearly
- Allow review before import commit

**Output:**
- Preview workflow

**Done when:**
- Users can inspect what will be imported before it is saved

---

## Phase 11 — Harden the System

### Step 27: Add retry, timeout, and rate limiting rules
**Owner:** QA and DevOps Agent

**Tasks:**
- Validate request timeout behavior
- Validate retry behavior
- Add crawl delay support
- Confirm the importer does not hammer source sites

**Output:**
- Safer crawl behavior

**Done when:**
- Crawling is stable and polite

---

### Step 28: Validate JS-rendered site limitations
**Owner:** QA and DevOps Agent

**Tasks:**
- Test against a static site
- Test against a JS-heavy site
- Confirm the system warns when content may be incomplete

**Output:**
- Known-limits report

**Done when:**
- The team knows which sites the MVP can and cannot handle

---

### Step 29: Test cleanup and rollback behavior
**Owner:** QA and DevOps Agent + Data/Verification Agent

**Tasks:**
- Test deleting import jobs
- Confirm media cleanup works when enabled
- Confirm repeated imports do not create messy duplicates

**Output:**
- Cleanup validation

**Done when:**
- The importer can be cleaned up safely

---

## Phase 12 — Template Rewrite and Rollback for Existing WordPress Sites

This phase is required when the source is an existing WordPress site that needs its homepage template rewritten instead of only importing content into a fresh page.

### Step 30: Build template backup and rewrite flow
**Owner:** WordPress Plugin Developer

**Tasks:**
- Back up the original homepage template before any change
- Generate a new `front-page.php` wrapper that reads ACF data
- Keep a fallback path that restores the original rendering if ACF data is missing
- Generate reusable section partials for the supported layouts

**Output:**
- Rewrite pipeline and backup files

**Done when:**
- The homepage can switch between ACF-driven rendering and safe fallback rendering

### Step 31: Build rollback and restore support
**Owner:** WordPress Plugin Developer + Data, Storage, and Verification Agent

**Tasks:**
- Restore the original template from backup
- Clear imported ACF content when rollback is requested
- Clean up generated assets and temporary files

**Output:**
- Rollback command and restore flow

**Done when:**
- A bad migration can be reverted safely

## Phase 13 — Batch Migration and Scale-Out

### Step 32: Add batch execution support
**Owner:** QA and DevOps Agent

**Tasks:**
- Add a batch runner for multiple sites
- Store per-site status and errors
- Support dry-run and force modes

**Output:**
- Batch migration workflow

**Done when:**
- Multiple homepage migrations can be queued or scripted reliably

### Step 33: Add migration coverage reporting
**Owner:** Data, Storage, and Verification Agent + QA and DevOps Agent

**Tasks:**
- Report extraction coverage per site
- Flag low-confidence or skipped content
- Mark sites that need human review

**Output:**
- Coverage report and review queue

**Done when:**
- The team can see which sites are ready and which need manual intervention

---

## Final Release Checklist

The project is ready for MVP use when:
- The plugin installs and activates cleanly
- The admin import screen works
- The crawl step saves JSON artifacts
- The homepage assessment step identifies the source and template type
- Section parsing works for supported layouts
- AI fallback works for uncertain sections
- ACF schema registers correctly
- The full payload is written with `update_field()` once
- The homepage renders dynamically
- Verification results are visible
- Dry-run mode works
- Logs and history are available
- JS-heavy sites are clearly flagged as limited
- Template rewrite and rollback are available for existing WordPress sources
- Batch migration support is available for multi-site rollout

---

## Recommended Build Order Summary

1. Orchestrator sets scope
2. Plugin developer builds shell and admin UI
3. ACF specialist defines and registers schema
4. Crawler/parser engineer fetches and splits the homepage
5. AI agent handles ambiguous classification
6. Data/verification agent seeds and checks data
7. QA/DevOps validates the whole pipeline

This is the safest order for the MVP.
