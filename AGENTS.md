# FreeFind Laravel agent guide

## Project

This is a PHP 8.4, Laravel 11/12 library that presents FreeFind's legacy HTML comments/forms and subscriber XML search API through modern Laravel APIs. The first published version will be the complete `1.0.0`; current source code is exploratory and may be replaced without compatibility shims.

The `1.0.0` product has three areas:

1. Blade directives/PHP objects for Page Search crawler markup.
2. Semantic, unstyled Blade components for hosted search and result presentation.
3. A typed, secure XML Page Search client for user-initiated searches.

DataSearch is documented but wholly deferred beyond `1.0.0`. Do not add DataSearch code, configuration, directives, components, or client behavior.

## Sources of truth

- `docs/planning/README.md`: planning index and current product summary.
- `docs/planning/decisions-and-open-questions.md`: accepted decisions; this wins if planning documents disagree.
- `docs/planning/delivery-roadmap.md`: implementation order, quality gates, and durable progress tracker.
- `docs/planning/architecture.md` and `public-api.md`: intended boundaries and consumer experience.
- `docs/planning/freefind-capability-map.md`: protocol traceability and known documentation contradictions.
- `docs/freefind/**`: downloaded upstream FreeFind documentation. **Read-only: never edit these files.**
- `src/`, `config/`, and `resources/`: package implementation. `tests/`: Pest/Testbench coverage.

## Starting or resuming work

1. Read this file, the planning index, accepted decisions, and the active roadmap milestone.
2. Inspect `git status` and relevant diffs. Preserve unrelated or user-authored changes.
3. Read the relevant implementation, tests, library docs, and upstream FreeFind source before changing behavior.
4. Work on the smallest complete vertical slice: implementation, tests, user documentation, and planning updates together.
5. Use the roadmap as durable cross-session state. Add/update a concise `Status` and `Next` note for an active milestone; do not turn it into a session journal or mark work complete before validation passes.

If code and accepted planning disagree, the accepted planning wins. If FreeFind sources conflict, do not guess: record the discrepancy in the capability map, isolate the behavior, and cover the chosen interpretation with a fixture/test. Ask the owner only when a decision materially changes scope or public API.

## Development rules

- Keep classes small, cohesive, strictly typed, and Laravel-native. Use Laravel's `Date` facade and expose `DateTimeInterface`, not Carbon, in public contracts.
- Treat site IDs as public strings, never credentials.
- Prefer safe plain-text XML result fields. Raw highlight fields must be explicitly named and treated as untrusted; never automatically wrap remote data in `HtmlString`.
- XML parsing must disable external resources/DTD processing and enforce transport/body bounds.
- XML calls must represent user-initiated searches. Do not add caching, schedules, queues, prefetching, bulk search, or normal-CI live requests.
- Spider detection is opt-in and never authorization. Do not call PHP `header()`, mutate process-global session configuration, or leak request state under Octane.
- Do not register package-owned application routes/controllers or invent Control Center/index-management APIs.
- Blade components and published views remain semantic and frontend-framework-free.
- Follow the module boundaries in `architecture.md`; avoid a single service object that renders markup, performs HTTP, and parses XML.

## Testing and quality (non-negotiable)

- Every production class must have focused unit tests.
- Complex behavior spanning collaborators must also have feature tests through the real Laravel container/Blade/middleware/HTTP integration.
- Every bug fix requires a regression test. Protocol output should use exact-string/fixture assertions; security boundaries require hostile-input cases.
- Keep live checks in the manually enabled `live-contract` Pest group. It must require explicit environment configuration and an operator-supplied query, and must never run in normal or scheduled CI.
- Code must follow PER as enforced by Pint. Larastan must pass; do not hide new errors in a baseline without explicit owner agreement.

Run targeted tests while developing. Before declaring a slice complete, run:

```bash
composer format
composer analyse
composer test
```

Do not hand off failing quality gates unless the failure is an external blocker that has been clearly documented.

## Documentation discipline

- Write/update user-facing library documentation under `docs/` as each feature is implemented, and link discoverable guides from the appropriate README/index.
- Never modify `docs/freefind/**`; it is the immutable upstream research snapshot.
- Update `docs/planning/**` whenever implementation changes an API, architecture decision, scope, milestone status, risk, or verified protocol fact.
- Keep code, tests, public docs, and planning synchronized in the same change. Do not document planned behavior as implemented.
- Preserve the distinction between FreeFind facts and package design decisions, linking to the local upstream document where relevant.

## Handoff checklist

Before stopping, leave the repository understandable from a clean context:

1. Review the diff for accidental or unrelated edits.
2. Update the active roadmap milestone's status/next action and all affected planning/user docs.
3. Record unresolved protocol assumptions, blockers, and the exact failing/passing validation commands.
4. Summarize completed behavior, tests added, documentation changed, and the next smallest vertical slice.
