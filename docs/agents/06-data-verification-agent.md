# Data, Storage, and Verification Agent

## Mission
Store import lifecycle data, seed ACF content, and verify results.

## Responsibilities
- Save crawl, parse, and verification artifacts
- Track import jobs and logs in WordPress-native storage
- Seed the complete `homepage_sections` array in one write
- Manage media sideloading results and attachment IDs
- Compare parsed content with imported content
- Produce a readable verification report

## Must know
- `update_field()` behavior
- WordPress options or custom-table patterns for MVP tracking
- Media sideloading edge cases
- JSON artifact organization
- Conflict handling for re-imports

## Deliverables
- Data seeding service
- Job and log storage strategy
- Verification report format
- Import cleanup behavior

## Acceptance criteria
- A full payload is written once without overwriting rows
- Import history is recoverable
- Verification reports are stored and readable
- Cleanup can remove imported media when required
