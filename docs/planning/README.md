# FreeFind Laravel planning pack

Status: accepted `1.0.0` direction; DataSearch implementation deferred to a later version
Research basis: the complete documentation snapshot in [`docs/freefind`](../freefind/README.md), downloaded on 29 August 2026

This directory turns the legacy FreeFind contracts into a development plan for a modern Laravel package. The documents distinguish facts stated by FreeFind from recommendations made for this library.

## Documents

1. [`product-scope.md`](product-scope.md) defines the product promise, users, requirements, scope, and success criteria.
2. [`freefind-capability-map.md`](freefind-capability-map.md) maps the downloaded documentation to package features and records FreeFind's constraints and contradictions.
3. [`architecture.md`](architecture.md) proposes package boundaries, configuration, runtime flows, security posture, and extension points.
4. [`public-api.md`](public-api.md) sketches the PHP, Blade, middleware, component, and XML-client APIs that consumers would use.
5. [`delivery-roadmap.md`](delivery-roadmap.md) breaks delivery into testable milestones and defines the quality strategy.
6. [`decisions-and-open-questions.md`](decisions-and-open-questions.md) records accepted decisions, later DataSearch context, and protocol questions for live verification.

## Recommended product shape

The package should have three deliberately separate modules:

1. **Crawler markup** translates expressive Blade directives and PHP value objects into FreeFind's HTML comments and meta tags.
2. **Hosted search UI** provides accessible, publishable Blade components for search forms that submit to FreeFind.
3. **XML search client** gives subscribed accounts typed, server-side access to page-search results for fully bespoke Laravel result pages.

Spider detection and route middleware support the crawler-markup module, but a spoofable user-agent must never become an authentication mechanism.

The first published version will be `1.0.0`; internal milestones will not be published as partial package releases. That release includes Page Search markup, Blade directives and components, hosted search forms, hardened opt-in spider detection, safe and raw XML result representations, and the typed XML client. It does not register application controllers or routes.

DataSearch is wholly deferred beyond `1.0.0`. Supporting both modes requires two ordinary FreeFind accounts with separate credentials and site IDs: the Page Search account uses the home page, while the DataSearch account must use another page so the URL/email combination differs. DataSearch also serves captured HTML from FreeFind's origin and is not supported by the documented XML feed.

## Vocabulary used in this pack

- **Page search** returns links to matching pages.
- **DataSearch** indexes marked-up fragments and returns the fragments themselves.
- **Crawler markup** means the comments and meta tags consumed while FreeFind spiders a site.
- **Hosted search** means the visitor submits directly to `search.freefind.com` and FreeFind renders the results.
- **API search** means Laravel requests `https://search.freefind.com/find.xml` and renders the parsed result.
- **Account** means one configured FreeFind search engine/site ID. A site ID is an identifier, not a secret.
