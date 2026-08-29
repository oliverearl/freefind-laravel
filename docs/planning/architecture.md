# Proposed architecture

## Context

FreeFind has two execution paths and the package must not blur them:

```text
Crawl time
FreeFind spider -> Laravel middleware -> controller/view -> Blade annotations -> HTML response -> FreeFind index

Search time, hosted
Browser -> Laravel page/search form -> FreeFind HTML endpoint -> FreeFind-hosted results

Search time, bespoke
Browser -> Laravel controller -> typed XML client -> FreeFind XML endpoint -> DTOs -> Laravel Blade/JSON response
```

Crawler annotations affect a later index after FreeFind re-spiders the site. XML requests query the index that already exists. Neither path configures the FreeFind account.

## Module boundaries

### `Configuration`

Responsibilities:

- resolve the configured Page Search account;
- validate its site ID, endpoint schemes, timeouts, and spider patterns;
- leave a clean extension seam for named accounts if DataSearch is accepted later;
- expose immutable configuration to the rest of the package.

This module owns no request state and performs no I/O.

### `Markup`

Responsibilities:

- represent supported FreeFind annotations as value objects;
- serialize them into exact comments/meta tags;
- validate values and paired-region nesting;
- register Blade directives;
- remain usable from PHP without Blade.

All literal legacy spellings belong in this module. Blade registration should call renderers; directive callbacks should not concatenate user input.

### `SearchForm`

Responsibilities:

- model and encode FreeFind's hosted HTML request fields;
- build HTTPS hosted URLs;
- render the package's accessible form component;
- keep HTML form encoding rules distinct from XML encoding rules.

This is intentionally independent from the XML client so free-account users do not pull in API concepts.

### `Spider`

Responsibilities:

- detect configured FreeFind user-agent signatures;
- expose immutable, request-scoped `SpiderContext`;
- apply crawler-specific response policy safely;
- support route metadata that becomes page-level crawler markup.

Detection is a hint, not identity. This module must never bypass authorization, CSRF, tenancy, feature gates, or unpublished-content rules.

### `Xml`

Responsibilities:

- model valid search requests;
- encode repeated query fields correctly;
- call the HTTPS XML endpoint through an injected transport;
- parse bounded XML without external resources;
- map successful and failed responses into typed objects/exceptions.

It does not cache, schedule, prefetch, bulk search, render unsafe HTML, or support DataSearch/web search.

### `Laravel`

Responsibilities:

- service provider, manager, facade, middleware aliases, Blade registration, components, config/views publishing;
- adapt Laravel HTTP, cache-header, logging, and container facilities to the framework-agnostic core;
- provide test fakes.

## Suggested source layout

```text
src/
  Configuration/
    Account.php
    FreefindConfig.php
  Contracts/
    MarkupRenderer.php
    SearchTransport.php
    XmlResponseParser.php
  Exceptions/
  Http/
    Middleware/
      DetectFreefindSpider.php
      AddFreefindAnnotations.php
  Markup/
    Annotation.php
    AnnotationSet.php
    HtmlCommentEscaper.php
    Renderer.php
    Annotations/
  Search/
    Hosted/
      SearchForm.php
      SearchUrl.php
    Xml/
      FreefindXmlClient.php
      Query/
      Request/
      Response/
      XmlParser.php
  Spider/
    SpiderContext.php
    SpiderDetector.php
    SpiderResponsePolicy.php
  View/
    Components/
  Facades/
  Freefind.php
  FreefindServiceProvider.php
```

Prefer small immutable objects. Avoid a single `Freefind` god object that both renders markup and performs HTTP calls.

## Configuration model

Proposed publishable configuration:

```php
return [
    'site_id' => env('FREEFIND_SITE_ID'),

    'endpoints' => [
        'html' => 'https://search.freefind.com/find.html',
        'xml' => 'https://search.freefind.com/find.xml',
        'index' => 'https://search.freefind.com/siteindex.html',
    ],

    'http' => [
        'connect_timeout' => 2,
        'timeout' => 5,
        'max_response_bytes' => 2_000_000,
    ],

    'spider' => [
        'middleware' => false,
        'user_agents' => ['freefind/2.1'],
        'cache_control' => 'public, max-age=3600',
        'disable_session' => false,
    ],
];
```

Design notes:

- site IDs remain strings and must never be documented as secrets;
- the `1.0.0` configuration keeps the usual one-account setup simple;
- if DataSearch is accepted later, it introduces an optional named-account layer without changing the default `FREEFIND_SITE_ID` path, because Page Search and DataSearch cannot share a site ID;
- endpoints are configurable to support tests, but production configuration rejects non-HTTPS XML unless the application is explicitly in a local/testing environment;
- no `api_key` or password setting exists because the documented XML service has neither;
- do not expose `dtd` or `xslt` switches in normal configuration;
- no cache default exists because the documented usage/reuse terms are restrictive.

## Container graph

- `Freefind` is a lightweight facade root exposing `markup()`, `hostedSearch()`, and `xml()` collaborators for the configured Page Search account.
- Internally, collaborators receive a validated account object rather than repeatedly reading configuration. An account resolver can grow named-account support later without changing the common facade calls.
- `MarkupRenderer`, `SearchTransport`, `XmlResponseParser`, `SpiderDetector`, and a clock are replaceable contracts.
- The default transport wraps Laravel's HTTP client, allowing `Http::fake()` and application middleware to work.
- Request-specific `SpiderContext` is scoped/request-bound and absent or explicitly “not a spider” outside HTTP requests.
- Depend on Illuminate contracts where practical rather than `Illuminate\Foundation\Application` concrete types.

## Markup rendering

### Value objects before strings

Each annotation validates its own domain and delegates serialization to one renderer. Examples:

- `WeightedKeywords(words: list<string>, count: int)`;
- `DocumentDate(date: DateTimeInterface)`;
- `ExplicitLinks(urls: list<Uri>)`;
- `ResultImage(src, attributes, href, linkAttributes)`;
- `WhatsNewEntry(date, icon, comment)`.

A later DataSearch module would add `DataSearchCategory(TrustedMarkup)` and paired listing annotations without mixing them into the Page Search model.

The annotation set can reject contradictory page policies such as both follow and no-follow. Region annotations are stack-aware during rendering/testing so a mismatched end marker is caught where possible.

### Escaping legacy comments

HTML comments cannot safely contain arbitrary `--`, and FreeFind's mini-syntax uses quotes inside comments. The renderer must:

- normalize or reject control characters;
- reject `--`, `<!--`, and `-->` after decoding;
- escape the quote style used by the specific FreeFind syntax;
- require absolute `http`/`https` URLs where FreeFind does;
- allow only known image/link attribute names and validate numeric dimensions;
- cap keyword count and comment lengths defensively;
- never accept a generic “attributes string.”

`TrustedMarkup` should be required only for FreeFind Category HTML. Its name and documentation must make the XSS and remote-hosting implications plain.

### Where annotations render

- Meta tags belong in `<head>` and should have head-specific directives/components.
- Page comments may render in head or body as FreeFind permits.
- Region comments wrap their exact Blade body.
- Global tags must appear early on the first page the spider reads; the API can render them but cannot guarantee placement.
- Comments must be present for ordinary responses too. Rendering them only after a spoofable user-agent match risks CDN caches serving an unannotated version to FreeFind.

Document that HTML minifiers, optimizers, CDNs, and Blade post-processors must preserve `FreeFind` comments.

## Spider middleware design

### Detection

Normalize the user agent and match configured signatures through `SpiderDetector`. Preserve the matched signature in `SpiderContext` for diagnostics. Substring matching is compatible with the documented observed user agent, but consumers can replace the detector if they need stricter rules.

### Response policy

Run policy around `$next($request)` and mutate the returned Symfony response:

- set `Cache-Control` on `$response->headers`, never with global `header()`;
- operate only on eligible HTML responses;
- never touch streamed/download responses;
- do not overwrite stricter application cache/private directives unless an explicit force option exists;
- add crawler diagnostic headers only in local/testing environments;
- restore any temporary configuration in `finally` and avoid resolving a persistent session manager after mutation.

Session suppression is the hardest part. Changing `session.driver` is process-global state under Octane and may also occur after the session manager has been resolved. The `1.0.0` behavior is:

1. keep it disabled by default;
2. register the detector early enough that a route can opt out of `StartSession` through normal Laravel middleware structure;
3. provide documentation for a dedicated crawlable route group;
4. do not add package-controlled session suppression; reconsider later only if Laravel 11/12 and Octane tests can prove a request-isolated implementation.

### Route annotations

`AddFreefindAnnotations` can accept a conservative list such as `no-index`, `no-map`, or `not-new`. It should inject through an HTML-response transformer immediately before `</head>` (or use a shared view-state contract) and decline unsupported responses. A Blade layout hook such as `@freefindHead` is more reliable than arbitrary body rewriting and should be the recommended integration.

## Hosted form design

- Render a `GET` form with the configured HTTPS HTML endpoint.
- Always own and emit the connection's `si` and the query input name.
- Emit multiple section controls using the HTML rules: empty value means entire site and `web` remains reserved.
- Emit `lang`, `nsb`, and `css` only when requested.
- Validate target values rather than copying arbitrary HTML.
- Permit label, input, submit, and section slots for design systems.
- Provide a URL builder separately for links such as site map and what's new.
- Do not use remote JavaScript or require Alpine/Livewire.

## XML client design

### Request model

Use distinct query types so invalid combinations are hard to construct:

- `SimpleQuery` owns `query` and `MatchMode`;
- `AdvancedQuery` owns optional `q1`–`q4` and requires at least one non-empty value;
- `RefinedQuery` owns new and old simple queries and serializes `search=these`;
- `SearchOptions` owns sensitivity, description length, result window, sections, sort, and stemming.

The request encoder produces an ordered list of key/value pairs. This matters because multiple `s` parameters must be encoded as repeated keys; common PHP array encoders instead produce `s[0]=...`.

### Transport

The default transport should:

- use HTTPS GET and `application/x-www-form-urlencoded` query encoding;
- identify the package with a normal HTTP user agent;
- apply short connection and total timeouts;
- bound the response body before XML parsing;
- retry connection failures and selected transient 5xx responses at most once with jitter, never blindly retry FreeFind status errors;
- redact query text from exception context/logs by default because searches may contain personal data;
- never send cookies or application authorization headers to FreeFind.

No request should happen until an explicit terminal method such as `get()`/`execute()` is called.

### XML parsing

The parser should not use FreeFind's optional DTD. It should:

- request `dtd=n` and omit `xslt`;
- parse with network access disabled (`LIBXML_NONET`) and never enable entity substitution;
- use internal libxml errors without leaking the raw body to production logs;
- enforce a response-size and reasonable element-count limit;
- ignore XML comments and insignificant whitespace;
- tolerate missing optional `srch`, `items`, result fields, pagination links, and dates;
- reject invalid required scalar types and unsafe click URL schemes;
- preserve unknown elements for diagnostics only if doing so has negligible complexity.

Do not validate against the published DTD because the downloaded DTD contains inconsistencies and enabling DTD processing expands the attack surface.

### Response model

`SearchResults` contains status, request echo, counts, offset, searched sections, spelling suggestion, automatic-any flag, immutable items, and local pagination helpers. `SearchResult` contains both raw remote strings and safe normalized strings, with safe strings used by default package views.

Recommended URL policy:

- accept absolute `http` and `https` click URLs;
- normalize an empty target to `null`;
- allow `_blank`, `_self`, `_parent`, `_top`, or a conservative frame-name pattern;
- whenever `_blank` is rendered, add `rel="noopener noreferrer"`;
- do not render `javascript:`, `data:`, protocol-relative, or malformed URLs as links.

Dates are parsed through Laravel's `Date` facade when they match expected formats and exposed through `DateTimeInterface`, while preserving the raw value for compatibility. This respects Laravel's configured date factory instead of coupling the package's public API to Carbon or another concrete date implementation.

### Error taxonomy

- `InvalidConfigurationException`: missing connection/site ID, unsupported mode, insecure endpoint.
- `InvalidSearchRequestException`: empty query, bad option range, reserved/invalid section.
- `SearchTransportException`: timeout, DNS/TLS failure, oversized body, disallowed redirect.
- `MalformedXmlResponseException`: invalid XML or required response shape.
- `UnauthorizedXmlFeedException` for status 2.
- `InvalidOrClosedAccountException` for status 3.
- `RejectedSearchParametersException` for status 4.
- `FreefindServiceException` for status 1/unknown non-zero status.

FreeFind status errors are deterministic service responses and should not be collapsed into generic HTTP failures.

## State, caching, and observability

- `1.0.0` has no migrations, models, queues, schedules, or persistent state.
- Do not cache XML results by default. Any future cache is explicitly user-query scoped and must be reviewed against FreeFind's current terms.
- Do not log raw queries, result excerpts, Basic-auth data, or full XML bodies by default.
- Emit opt-in Laravel events with metadata only: connection name, duration, status, total count, and exception class.
- A correlation ID may be local, but must not be sent as a query parameter FreeFind does not document.

## Runtime dependencies

- Add an explicit Composer platform requirement for the XML parser selected by implementation (`ext-simplexml` plus `ext-libxml`, or `ext-dom` plus `ext-libxml`). Do not assume an extension merely because a developer machine has it.
- Require `laravel/framework` directly for the supported Laravel versions rather than adding a growing list of granular Illuminate components. This is deliberately a Laravel-only package, and the current `illuminate/contracts` requirement does not by itself provide the Foundation, Support, HTTP, View, and Routing classes the package uses. The framework replaces the individual Illuminate packages, so this also states the real runtime assumption cleanly.
- Use Laravel's `Date` facade for parsing and creation while exposing `DateTimeInterface` in public contracts. This lets Laravel determine the underlying date implementation and avoids a direct Carbon contract.
- Use a small package-owned URL value object so the protocol layer does not require a third-party URI package without a demonstrated need.
- Keep an HTML sanitizer out of the first XML milestone unless it is both maintained and necessary. Plain-text result normalization avoids a substantial runtime dependency.
- Continue using `spatie/laravel-package-tools` for package registration and publishing, but keep protocol and rendering classes usable without its static helpers.

## Current skeleton migration

The existing source is a useful proof of concept, not the intended final boundary. Planned migrations:

1. Preserve the facade name while changing its root to the final lightweight service design.
2. Replace `isFreeFindRequest()` directly with the final `isSpiderRequest()` naming; no compatibility alias is required for exploratory pre-release code.
3. Replace the integer site-ID API directly with a string-valued contract; no deprecation bridge is required.
4. Move user-agent matching into `SpiderDetector`.
5. Replace `header()` with response-header mutation.
6. Stop registering state-changing middleware globally without an opt-in configuration decision.
7. Remove request-global `Config::set('session.driver', 'array')` until an isolated implementation is proven.
8. Keep package views under the standard package-tools view path and resolve the currently moved `.gitkeep` separately from this planning work.

Because all existing code is exploratory and the first published version will be `1.0.0`, implementation should optimize for the final API rather than preserve any current surface.
