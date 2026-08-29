# FreeFind Laravel documentation

This catalogue is the user-facing guide to the FreeFind Laravel package. The package owns the integration code and application examples; FreeFind's downloaded manual remains an immutable reference for legacy protocol details.

## Start here

- [Configuration and spider handling](configuration.md) — site IDs, HTTPS endpoints, HTTP limits, opt-in spider middleware, and request safety.
- [Migration guide](migration.md) — map legacy FreeFind snippets and hosted forms to package APIs.
- [Troubleshooting](troubleshooting.md) — indexing, response optimization, middleware, account, and XML failures.

## Integrations

- [Hosted search](hosted-search.md) — render an accessible, unstyled form that submits directly to FreeFind.
- [Crawler markup](markup.md) — emit Page Search comments, metadata, fragment markers, and route annotations.
- [XML search](xml-search.md) — run bounded, user-initiated searches for subscribed regular Page Search accounts and render safe result models.

## Project reference

- [Planning pack](planning/README.md) — accepted product decisions, architecture, public API, capability map, and delivery state.
- [Upstream FreeFind documentation snapshot](freefind/README.md) — the dated, read-only research source used by the package design.

DataSearch is described in the planning and migration material for account-planning purposes, but it has no package implementation in `1.0.0`.
