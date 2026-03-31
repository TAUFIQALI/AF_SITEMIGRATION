# Agent Team Overview

This project should be built by a small group of specialized agents working in sequence.

## Goal
Build a WordPress plugin that can:
- crawl a source homepage
- detect and classify homepage sections
- map them into ACF Flexible Content layouts
- seed content into WordPress
- render the homepage dynamically
- verify the import

## Core rules
- Keep the MVP homepage-only.
- Store raw artifacts as JSON files.
- Use WordPress native storage for content and import state.
- Use ACF Pro Flexible Content for sections.
- Preserve section order.
- Prefer deterministic parsing first, AI second.

## Suggested workflow
1. Orchestrator defines scope and handoff.
2. WordPress plugin agent builds the plugin shell.
3. ACF agent defines schema and field keys.
4. Crawler/parser agent fetches and analyzes HTML.
5. AI agent classifies ambiguous sections.
6. Data/verification agent stores results and checks quality.
7. QA/DevOps agent validates the system end to end.
