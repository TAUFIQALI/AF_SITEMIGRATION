# ACF Homepage Importer — Architecture Diagram

## System Overview

```text
Admin UI / WP-CLI / Batch Runner
        ↓
Orchestrator / Core Plugin
        ↓
Source Detection
- raw homepage URL
- existing WordPress homepage
- theme template assessment
        ↓
Crawler Layer
- wp_remote_get()
- DOMDocument parsing
- Firecrawl fallback when needed
- asset collection
        ↓
Parser Layer
- section boundary detection
- section classification
- confidence scoring
- AI fallback for uncertain sections
        ↓
Normalization Layer
- canonical internal content model
- cleanup
- enrichment
- alt-text generation
        ↓
ACF Mapping Layer
- flexible content payload
- field key alignment
- layout mapping
        ↓
Data Layer
- update_field()
- media sideloading
- job storage
- logs
- JSON artifacts
        ↓
Template Layer
- homepage dynamic template
- section partials
- fallback rendering
        ↓
Verification Layer
- compare parsed vs saved data
- coverage checks
- pass/warn/fail report
```

---

## Recommended Layer Responsibilities

### 1. Core / Orchestrator
Owns the workflow and controls each phase.

### 2. Admin
Provides URL input, target page selection, dry-run, import, and verify actions.

### 3. Crawler
Fetches HTML and assets, handles retries, timeouts, and fallback crawling.

### 4. Parser
Turns HTML into section blocks and structured section data.

### 5. AI
Handles ambiguous classification and content normalization.

### 6. Schema
Registers ACF field groups and layout keys.

### 7. Data
Saves content, media, logs, and artifacts.

### 8. Template
Renders the imported homepage from ACF.

### 9. Verification
Checks whether the imported data matches the source data closely enough.

---

## Key Design Rules

- Raw crawl output must never be written directly into ACF.
- Normalize first, then map to ACF.
- Use one final `update_field()` call for the flexible content payload.
- Use Firecrawl only as a fallback, not the primary source of truth.
- Keep AI behind one service interface.
- Keep field keys stable and explicit.
- Preserve the source section order.

---

## Data Flow Summary

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

### Parsed section
```json
{
  "type": "hero",
  "confidence": 0.95,
  "items": {
    "heading": "Smile Better",
    "subheading": "Modern orthodontics",
    "button_text": "Book Now",
    "button_link": "/contact"
  }
}
```

### Canonical model
```json
{
  "type": "hero",
  "confidence": 0.95,
  "content": {
    "heading": "Smile Better",
    "subheading": "Modern orthodontics"
  },
  "assets": {
    "images": []
  },
  "notes": []
}
```

### ACF payload
```json
{
  "homepage_sections": [
    {
      "acf_fc_layout": "hero",
      "heading": "Smile Better",
      "subheading": "Modern orthodontics"
    }
  ]
}
```
