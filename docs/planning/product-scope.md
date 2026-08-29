# Product scope

## Product statement

`freefind-laravel` should let a Laravel developer integrate FreeFind without copying fragile 1990s-era snippets throughout Blade templates or hand-parsing an XML feed. It should preserve the semantics FreeFind expects while presenting Laravel-native, typed, testable APIs.

The package is an adapter around a remote service. It does not run an index, crawl a site, configure a FreeFind account, or replace the FreeFind Control Center.

The first published package version will be `1.0.0`. The implementation milestones are internal development checkpoints, not public prereleases; existing exploratory code carries no backward-compatibility constraint.

## Target users

### Primary

- Maintainers adding search to an existing Laravel site with a FreeFind account.
- Maintainers migrating a static or legacy FreeFind-enabled site to Laravel.
- Developers who want FreeFind to crawl a dynamic Laravel site correctly.
- Subscribers who want a bespoke, first-party results page powered by the XML feed.

### Secondary

- Sites using FreeFind sections, site maps, “what's new,” result images, or relevance hints.
- Experienced developers using DataSearch to index repeated listings such as products or directory entries.

## Product principles

1. **Modern interface, faithful output.** Consumers work with Blade, enums, immutable value objects, Laravel's HTTP client, and normal middleware. Generated markup and query parameters remain faithful to FreeFind.
2. **Safe by default.** Escape comment attributes, reject invalid nesting, use HTTPS, parse XML without network/entity expansion, and escape returned HTML by default.
3. **No hidden authorization.** Spider detection may change presentation and crawl behavior, but never grants access or bypasses authentication.
4. **Progressive adoption.** A consumer can install only a search box, then add crawl annotations, and later add API-backed result pages without changing the earlier integration.
5. **Explicit legacy constraints.** Paid-plan requirements, Control Center steps, re-index requirements, HTML-comment preservation, and remote-service limitations belong in the public documentation.
6. **No invented FreeFind API.** The downloaded material documents search requests only. Account configuration, template upload, and “index now” remain manual until a supported API is verified.

## User outcomes

A successful package lets a developer:

- configure a FreeFind site ID through a simple default-account path;
- render a valid, accessible search form without memorizing `si`, `query`, `s`, `lang`, `nsb`, or `css` fields;
- annotate a page, fragment, link group, document date, image, site-map entry, and “what's new” entry from Blade;
- identify a FreeFind crawl request and optionally make its response cacheable or sessionless;
- run simple, advanced, sectioned, refined, paginated, or date-sorted XML searches through typed request objects;
- distinguish transport failures, malformed XML, FreeFind status errors, and successful zero-result searches;
- render results with accessible package views or use the DTOs in a completely custom UI;
- test all of the above without contacting FreeFind.

## Functional scope

### `1.0.0` scope

#### Configuration and account

- A simple default Page Search account with a string site ID.
- An internal resolver that does not prevent named accounts being introduced if the later DataSearch investigation justifies them.
- HTTPS endpoints, timeouts, user-agent patterns, and crawler-response behavior configurable with conservative defaults.
- Configuration validation with actionable failures for missing site IDs or invalid endpoints/options.

#### Crawler markup

- Page-level annotations: keywords and weight, document date, no-index-page, explicit discoverable links, no-map, map title, not-new, what's-new metadata, result image, script/query following policy.
- Paired fragment annotations: no-index content and no-follow links.
- Exact legacy output isolated behind a renderer instead of being assembled inside directive callbacks.
- Strict escaping and validation for values placed inside HTML comments or attributes.

#### Hosted search UI

- Search form component/view with site ID, query, optional sections, language, hidden-result-search-box flag, extended CSS flag, target, method, and accessible labels.
- Escape hatch for custom attributes and slots without allowing protected protocol fields to be silently overridden.
- An explicit URL builder for search, site-map, what's-new, and index links where the downloaded docs establish the contract.

#### Spider handling

- Opt-in or explicitly configurable user-agent detection.
- A request-scoped crawler context (`isSpider()` plus matched user agent).
- Response cache headers applied to the Laravel response object, not through PHP's global `header()` function.
- No process-global session-driver mutation; documentation shows how an application can keep a dedicated crawlable route group outside `StartSession` when needed.
- Route middleware that applies supported page annotations to HTML responses, while refusing streamed, binary, compressed, or non-HTML bodies.

#### XML client

- Simple `query` searches and advanced `q1`–`q4` searches.
- Search options for accent/case sensitivity, description length, offset, 1–25 results, sections, refinement, relevance/date sorting, and stemming.
- Typed search response, result item, pagination, spelling suggestion, and status error models using Laravel's configured date factory behind `DateTimeInterface` contracts.
- Secure, bounded XML parsing and Laravel HTTP fakes.
- Plain-text-safe fields by default; raw FreeFind highlight HTML available only through an explicitly named API.
- Semantic unstyled Blade components/views for result lists, result items, pagination, spelling suggestions, and empty states; the application owns its controller and route.

#### Laravel integration

- Auto-discovered service provider, contracts in the container, facade, Blade directives and components, middleware aliases, publishable config, and semantic unstyled views.
- No database migrations or persistent package state in `1.0.0`.

### Later scope

- A separately designed DataSearch module using a second ordinary FreeFind account with its own credentials and site ID: named accounts, mode/category/listing directives, and absolute-URL linting. No DataSearch implementation is part of `1.0.0`.
- Rich result-highlight sanitizer with a tiny allowlist.
- Artisan diagnostic command that validates configuration and performs only a user-requested search.
- An indexability/debug panel for local and staging environments.
- Compatibility adapters for sites migrating existing raw snippets.
- Additional diagnostics beyond the manually gated live-contract test already included for maintainers.

### Explicitly out of scope

- Crawling or building a local search index.
- Scraping or automating the FreeFind Control Center.
- Triggering re-index jobs without a documented supported API.
- Uploading FreeFind-hosted templates without a documented supported API.
- Providing web-wide search through the XML client. The XML introduction says only regular site search is available, despite a contradictory parameter entry.
- Hiding FreeFind advertising or bypassing subscription requirements.
- Treating a site ID as a credential.
- Indexing sensitive authenticated content or bypassing Laravel authentication for the spider.
- Scheduled, speculative, bulk, prefetch, or robotic XML queries; FreeFind permits the feed only in response to user-entered searches.
- Shipping deprecated `<nofollow>`, `<nofollowscript>`, or `<!-- FreeFind No Parameters -->` output in the ergonomic API.
- Registering application routes or controllers. The XML client, Blade components, and documented controller examples are sufficient unless later consumer evidence demonstrates a genuinely reusable HTTP workflow.
- Shipping any DataSearch configuration, directives, components, or client behavior in `1.0.0`; the first-release documentation only explains why two regular FreeFind accounts are required if it is added later.

## Non-functional requirements

- Support the versions declared by the package: PHP 8.4 and Laravel 11/12.
- Strict types throughout package-owned PHP.
- Work correctly under traditional PHP-FPM and long-running workers such as Octane; no per-request configuration may leak into the next request.
- Never make a network request while compiling or rendering a Blade view.
- Deterministic generated markup suitable for snapshot and exact-string tests.
- Helpful exceptions for programmer errors; stable result objects for legitimate FreeFind errors.
- Reasonable API defaults: HTTPS, ten results, relevance sorting, no DTD, no XSLT, finite timeouts, no automatic cache.
- Published views remain optional and semantic, with no frontend-framework requirement.

## Success criteria for 1.0

- A new Laravel application can add a hosted site-search box in under ten minutes.
- Every non-deprecated Page Search-specific FreeFind tag in the downloaded tag reference has either a supported package API or a documented reason for exclusion; DataSearch-only tags are explicitly deferred.
- Every documented XML request parameter that applies to regular site search has a validated typed representation.
- Every documented XML response tag is represented or deliberately ignored with a recorded rationale.
- All markup and XML behavior can be tested with local fixtures and Laravel HTTP fakes.
- The README states the paid XML requirement, user-initiated-query restriction, re-index requirement, site-ID visibility, and authenticated-content warning before its first advanced example.

## Important product distinction

FreeFind's hosted templates and the package's Blade views are not the same thing:

- a FreeFind custom template is uploaded in the Control Center, limited to 64 KiB of HTML, and served from FreeFind's domain;
- a Laravel Blade view is rendered by the consumer's application;
- a Blade-built template can be offered as a copy/paste/export helper later, but this package cannot deploy it based on the documented interfaces;
- an API-backed Laravel results view avoids the FreeFind template mechanism entirely, but requires a subscription.
