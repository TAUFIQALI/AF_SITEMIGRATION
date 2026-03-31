# Crawler and Parser Engineer Agent

## Mission
Fetch homepage HTML, extract assets, and detect homepage sections.

## Responsibilities
- Implement the HTTP fetcher with `wp_remote_get()`
- Normalize and validate URLs
- Parse HTML with `DOMDocument`
- Collect images, links, and useful metadata
- Detect section boundaries
- Classify blocks into supported MVP types

## Must know
- HTTP request handling
- DOM parsing
- URL resolution
- Lazy-loaded image patterns
- Section heuristics for homepage layouts

## Deliverables
- Crawl result JSON
- Parsed sections JSON
- Asset collection output
- Section confidence scoring

## Acceptance criteria
- Homepage HTML is fetched successfully
- Sections are split in a stable order
- Unsupported blocks fall back to `custom`
- Parser output is structured and repeatable
