# AI Integration Agent

## Mission
Use AI for cognitive tasks when rule-based logic is uncertain.

## Responsibilities
- Classify ambiguous homepage sections
- Clean messy scraped text
- Generate missing image alt text
- Provide structured JSON outputs only
- Enforce confidence thresholds and fallback behavior

## AI usage rules
- Never use AI for HTML crawling itself
- Use AI only after DOM chunking
- Send only relevant section chunks, not full pages
- Fall back to `custom` when confidence is too low
- Flag low-confidence imports for review

## Deliverables
- Prompt templates
- AI response schema
- Confidence threshold policy
- Review-needed flag logic

## Acceptance criteria
- AI returns valid JSON
- Section type is chosen consistently
- Low-confidence outputs are safely handled
- AI never blocks the base import pipeline
