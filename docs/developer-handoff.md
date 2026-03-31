# ACF Homepage Importer — Developer Handoff

This is the master handoff document for the team building the ACF Homepage Importer.
It links the planning, architecture, checklist, developer guide, and role documents together.

---

## 1. Start Here

Read these documents in order:

1. [Developer Guide](developer-guide.md)
2. [Architecture Diagram](architecture-diagram.md)
3. [Implementation Checklist](implementation-checklist.md)
4. [Project Step-by-Step Plan](project-step-by-step-plan.md)
5. [Agent Roles Overview](agents/README.md)

---

## 2. Role Documents

### Core coordination
- [Overview](agents/00-overview.md)
- [Project Orchestrator](agents/01-project-orchestrator.md)

### Build roles
- [WordPress Plugin Developer](agents/02-wordpress-plugin-developer.md)
- [ACF Specialist](agents/03-acf-specialist.md)
- [Crawler and Parser Engineer](agents/04-crawler-parser-engineer.md)
- [AI Integration Agent](agents/05-ai-integration-agent.md)
- [Data, Storage, and Verification Agent](agents/06-data-verification-agent.md)
- [QA and DevOps Agent](agents/07-qa-devops-agent.md)

---

## 3. Recommended Build Sequence

### Phase A — Prepare
- Confirm scope
- Confirm access
- Confirm API keys
- Confirm Docker environment

### Phase B — Foundation
- Build plugin shell
- Add admin UI
- Add CLI test command
- Register ACF schema

### Phase C — Crawl and Parse
- Detect homepage source
- Fetch HTML
- Parse DOM
- Detect sections
- Classify sections

### Phase D — Normalize and Map
- Use AI only for uncertain cases
- Reshape data into canonical internal format
- Map canonical data to ACF payload

### Phase E — Save and Render
- Seed ACF data
- Sideload images
- Render dynamic homepage

### Phase F — Verify and Harden
- Run verification
- Add dry-run preview
- Add logging and artifacts
- Add retry, rollback, and batch support if needed

---

## 4. Ownership Matrix

| Area | Owner |
|---|---|
| Scope and sequencing | Project Orchestrator |
| Plugin bootstrap and admin UI | WordPress Plugin Developer |
| ACF schema and field keys | ACF Specialist |
| Homepage detection, crawling, parsing | Crawler and Parser Engineer |
| AI fallback and normalization | AI Integration Agent |
| Seeding, logs, artifacts, verification | Data, Storage, and Verification Agent |
| Environment, testing, release readiness | QA and DevOps Agent |

---

## 5. Key Decisions

- Use WordPress as the runtime.
- Use ACF Pro Flexible Content for homepage sections.
- Keep raw crawl data separate from ACF data.
- Reshape data before saving.
- Use OpenRouter as the preferred AI gateway.
- Use Firecrawl only as a fallback.
- Support dry-run mode before permanent writes.
- Preserve section order.
- Keep field keys stable.

---

## 6. Important Links

- [Developer Guide](developer-guide.md)
- [Architecture Diagram](architecture-diagram.md)
- [Implementation Checklist](implementation-checklist.md)
- [Project Step-by-Step Plan](project-step-by-step-plan.md)
- [Agents Folder](agents/README.md)

---

## 7. Handoff Rules

### When an agent finishes work
- document what changed
- list any blockers
- record what the next agent needs
- save artifacts under `storage/`

### When a phase is complete
- run the implementation checklist
- update the project plan
- verify the output on a real site or local Docker environment

### When a risky change is introduced
- use dry-run first
- confirm the backup or rollback path
- record the decision in the notes

---

## 8. Final Reminder

The safest order is:

1. crawl
2. parse
3. reshape
4. map
5. save
6. render
7. verify

Do not skip the reshaping step.
Do not write raw crawl output directly into ACF.
