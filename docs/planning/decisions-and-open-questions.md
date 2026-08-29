# Decisions and open questions

## Accepted decisions

These combine the conclusions supported by the FreeFind research with the owner's product answers.

### D1 — The package has three primary modules

Decision: crawler markup, hosted search UI, and XML page-search client are separate layers with a shared account model.

Why: they serve free vs subscribed accounts, operate at different times, and have different security/availability concerns.

### D2 — Site IDs are strings and public identifiers

Decision: stop coercing site IDs to integers and never call them secrets.

Why: the XML reference defines `si` as a string; DataSearch embeds it as text; FreeFind explicitly warns that site IDs appear in URLs and are guessable.

### D3 — Keep the common account configuration simple

Decision: `1.0.0` configures the normal Page Search site ID directly. Named accounts are introduced only if the later DataSearch investigation justifies a second site ID.

Why: one Page Search account is the normal case. FreeFind requires a second account for DataSearch, but that optional feature should not make every installation understand connection management.

### D4 — XML supports regular site search only

Decision: omit web search and DataSearch from the high-level XML client.

Why: the XML reference's introduction limits the feed to regular site search, even though one lower table mentions `web`.

### D5 — No programmatic Control Center features

Decision: do not claim to upload templates, trigger indexing, change exclusions/sections/relevance, or fetch reports.

Why: the downloaded corpus documents browser-based Control Center operations, not a supported management API.

### D6 — Safe plain text is the default XML result representation

Decision: titles/descriptions used by package views are text; raw HTML-bearing fields require an explicit accessor/type.

Why: FreeFind inserts `<b>` highlights and indexed content is remote/untrusted from Laravel's perspective.

### D7 — No XML caching, queues, schedules, or bulk API by default

Decision: one explicit client terminal call represents one user-triggered search.

Why: FreeFind limits XML access to user-entered searches, prohibits robotic requests, and includes a result-reuse warning.

### D8 — Spider detection never changes authorization

Decision: detection can adjust response markup, caching, and non-sensitive presentation only.

Why: a user agent is trivially spoofed, and FreeFind's protected-content model can leak snippets.

### D9 — Global middleware is not an unconditional side effect

Decision: always register an alias, but require explicit opt-in for global execution.

Why: every package consumer currently receives config/session/cache mutations, including consumers who only need Blade output.

### D10 — Do not mutate PHP headers or process-global session config

Decision: use the response headers object and ship no package-controlled session suppression in `1.0.0`. Reconsider later only if a request-isolated, ordering-correct implementation can be proven.

Why: direct `header()` bypasses Laravel's response abstraction and `Config::set()` can leak under long-running workers.

### D11 — Deprecated FreeFind tags stay low-level or unsupported

Decision: do not add ergonomic directives for `<nofollow>`, `<nofollowscript>`, or `FreeFind No Parameters`.

Why: FreeFind directs new code to comment/meta replacements.

### D12 — Page annotation output is independent of spider detection

Decision: normal Blade annotations render on all eligible page responses.

Why: caches vary unpredictably by user agent and FreeFind must receive the same annotation-bearing HTML reliably.

### D13 — The first published version is `1.0.0`

Decision: milestones are internal checkpoints. Do not publish partial `0.x` releases; publish only when the complete agreed scope is ready as `1.0.0`.

Why: both the hosted/crawler integration and bespoke XML client are priorities, and the package is not intended for use before the complete initial product is delivered.

### D14 — Existing code has no compatibility weight

Decision: replace exploratory facade, site-ID, middleware, and configuration APIs directly wherever the final design differs. No aliases or deprecation cycle are needed.

Why: none of the current project has been published as a supported package API.

### D15 — Use Blade directives and components

Decision: use directives for exact crawler comments, head annotations, and paired regions; use semantic components for hosted forms and reusable presentation. Both sit on normal PHP objects.

Why: directives fit non-element legacy markers, while components fit accessible UI and customizable slots.

### D16 — Package views are semantic and unstyled

Decision: published views include no Tailwind, Bootstrap, or other design-system dependency.

Why: consumers should be able to adopt their own frontend conventions without undoing package styling.

### D17 — Do not register package controllers or routes

Decision: `1.0.0` ships the client, directives, components, views, and controller examples, but application HTTP endpoints remain consumer-owned.

Why: validation, URLs, authorization, rate limiting, analytics, response type, and error UX belong to the application. Reconsider only if later usage reveals a truly reusable workflow.

### D18 — Raw highlighted XML fields are part of `1.0.0`

Decision: preserve explicitly named raw title/description fields alongside safe plain-text fields. Package views use safe text by default; raw access is opt-in and documented as untrusted.

Why: bespoke consumers may want FreeFind's highlight markup, while a safe default must not turn remote strings into trusted Laravel HTML.

### D19 — Live contract tests are manually group-gated

Decision: create a separate Pest/PHPUnit `live-contract` group. It never runs in normal or scheduled CI and requires an explicit enable flag, subscribed-account environment configuration, and operator-supplied query for that run.

Why: the remote contract needs occasional verification, but FreeFind limits XML access to user-entered, non-robotic searches and remote availability must not affect deterministic CI.

### D20 — Use Laravel's date abstraction

Decision: use Laravel's `Date` facade to parse/create dates and expose `DateTimeInterface` rather than Carbon or another concrete type in public contracts.

Why: this is a Laravel-only package and should respect the date factory configured by the host application.

### D21 — Make the Laravel runtime requirement explicit

Decision: prefer a direct supported `laravel/framework` constraint over enumerating every Illuminate component used.

Why: this package intentionally requires Laravel. The current direct requirement on `illuminate/contracts` alone does not supply Foundation, Support, HTTP, View, or Routing, while `spatie/laravel-package-tools` also only requires contracts. Testbench happens to provide the full framework in development, masking that runtime gap.

### D22 — Defer all DataSearch functionality

Decision: `1.0.0` contains no DataSearch configuration, directives, components, or client behavior. It documents the account distinction so users do not mistake DataSearch for a mode that can be enabled on their existing Page Search account.

Why: DataSearch has a separate account lifecycle, markup model, result-hosting security surface, and no documented XML integration. Deferring it keeps the first release cohesive and avoids designing multi-account abstractions before a concrete DataSearch integration is being built.

### D23 — Package exceptions use an `Exception` suffix

Decision: name every package-owned exception with the `Exception` suffix and enforce the convention with an architecture test. Existing exploratory names are replaced directly without compatibility aliases.

Why: the suffix makes thrown types immediately recognizable at call sites and keeps the exception namespace consistent before the first published release.

## Later DataSearch context

What is already known from FreeFind's documentation:

- both are ordinary FreeFind accounts, each with its own credentials and site ID;
- an existing Page Search account/site ID cannot simultaneously operate as DataSearch;
- retaining Page Search therefore requires a second regular account configured for DataSearch;
- the second signup cannot repeat the same URL/email combination, so FreeFind recommends using another page on the same site for DataSearch;
- DataSearch returns captured listing HTML rather than links to pages;
- all captured links/images must be absolute because FreeFind hosts the result page;
- the documented XML feed cannot retrieve DataSearch results for local Laravel rendering.

Before designing it for a future release:

1. Use the available dedicated DataSearch account only with controlled, non-sensitive fixtures.
2. Verify live behavior against the downloaded documentation.
3. Define the concrete use case that FreeFind-hosted captured fragments solve beyond Page Search/XML.
4. Design named accounts, `data` mode, category/listing directives, and absolute-URL linting as one coherent later module.

## Questions to verify with FreeFind or a live account

These are protocol research tasks rather than product preferences:

1. Which query-control meta value is current: `noFollowQueries`, `noQueries`, or both?
2. Does the XML endpoint always escape highlight HTML as text, and can fields contain tags other than `<b>`?
3. What HTTP status and content type accompany XML `sts` errors?
4. Can `srch`, `items`, `du`, `u`, and `dt` be absent in real responses despite the DTD?
5. Are duplicate `s` query keys required, and is their order significant?
6. What redirects, response-size limits, rate limits, or throttling signals does the service use?
7. Does the endpoint negotiate compression and UTF-8 consistently?
8. Are the XML result reuse/caching terms still exactly those embedded in the sample response?
9. Are `zh2`/`zh3` and `ro`/`ro2` all active, and what labels distinguish them?
10. Is the result-image comment still accepted exactly as documented?
11. Does the spider still identify as `freefind/2.1`, and are there additional production user agents?
12. Does FreeFind offer any supported management endpoint not present in the downloaded corpus?

The subscribed Page Search account needed for these checks is available. Tests must use controlled, non-sensitive data and follow the manually gated policy above.
