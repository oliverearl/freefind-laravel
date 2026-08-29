# XML search request core

The package's XML integration is for subscribed regular Page Search accounts only. The current milestone provides typed request models, deterministic HTTPS URL encoding, a bounded Laravel HTTP transport, a tolerant response parser with typed result models, an explicit terminal client call, semantic result components, and immutable simple-query builder ergonomics.

FreeFind documents the XML feed as available only in response to a user entering a search term. Do not use it for scheduled, robotic, speculative, bulk, or prefetch queries. DataSearch and web-wide XML search are outside this package's scope.

## Request models

The connection owns the site ID and XML endpoint. Query intent is represented by one of three types:

```php
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlRequestEncoder;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;

$request = new XmlSearchRequest(
    app(Freefind::class)->account(),
    new SimpleQuery('laravel middleware'),
    new SearchOptions(resultsPerPage: 15, sections: ['manuals']),
);

$url = (new XmlRequestEncoder)->url($request);
```

`SimpleQuery` produces FreeFind's `query` value and can opt into `mode=any`. `AdvancedQuery` maps its `allWords`, `exactPhrase`, `anyWords`, and `withoutWords` properties to `q1`–`q4`. `RefinedQuery` combines a new `SimpleQuery` with a required previous query and emits `search=these` plus `oq`.

`SearchOptions` validates the non-negative `fr` offset, the 1–25 `rpp` range, documented description lengths, relevance/date sorting, stemming values, and non-repeated section identifiers. Defaults are omitted where FreeFind supplies them. The encoder always sends `dtd=n`; callers cannot enable remote DTD output through this API.

The returned `XmlSearchUrl` is a validated HTTPS value object. It can be cast to a string or read through `value`. Query fields use `application/x-www-form-urlencoded` encoding, so spaces become `+` and multiple sections remain repeated `s=value` fields rather than PHP bracket syntax.

## Query builder

For a simple user-entered search, the package also provides an immutable convenience builder:

```php
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Xml\Query\SortOrder;

$results = app(Freefind::class)->search($request->string('q')->toString())
    ->inSections(['manuals', 'releases'])
    ->sortBy(SortOrder::Relevance)
    ->perPage(15)
    ->startingAt(max(0, $request->integer('offset')))
    ->get();
```

Each option method returns a new builder. `get()` is the terminal operation and performs one XML request. The builder covers simple queries; use `app(Freefind::class)->xml()->execute(...)` with `AdvancedQuery`, `RefinedQuery`, and explicit `SearchOptions` when the request needs a different query shape.

## Test fake

Package tests can use a fixture-backed fake without replacing Laravel's normal `Http::fake()` support:

```php
use Freefind\Freefind\Facades\Freefind;
use Freefind\Freefind\Testing\SearchFixture;
use Freefind\Freefind\Testing\SentSearch;

Freefind::fake([
    SearchFixture::for('blade directives')->fromFile(
        base_path('tests/Fixtures/xml/success.xml'),
    ),
]);

$results = Freefind::search('blade directives')->get();

Freefind::assertSearched(fn (SentSearch $search): bool =>
    $search->query === 'blade directives'
);
```

The fake records query text, selected sections, offset, and page size, and returns the fixture through the same response parser used by the real client. It is test support only; application code should continue to call the client explicitly with a user-initiated query.

## Optional live contract check

The `live-contract` Pest test is skipped unless all of these environment values are supplied for one deliberate operator run:

```bash
FREEFIND_LIVE_CONTRACT=1 \
FREEFIND_SITE_ID=3225682 \
FREEFIND_LIVE_QUERY='blade directives' \
vendor/bin/pest --group=live-contract
```

Use a subscribed regular Page Search account and a controlled, non-sensitive query. The test performs one request against the default HTTPS XML endpoint, accepts a successful zero-result response, and is never part of normal or scheduled CI. Do not set these variables for automated builds.

## Executing a search

Build the request from the caller's validated, user-entered query and invoke the XML client explicitly:

```php
use Freefind\Freefind\Freefind;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;

$searchRequest = new XmlSearchRequest(
    app(Freefind::class)->account(),
    new SimpleQuery($request->string('q')->toString()),
    new SearchOptions(sections: ['manuals']),
);

$results = app(Freefind::class)->xml()->execute($searchRequest);
```

`execute()` performs exactly one request and returns `SearchResults` after transport and XML validation. Constructing the request or resolving the client does not send anything. The application remains responsible for request validation, authorization, rate limiting, its own search route, and presenting transport or service errors to the user. Do not call this method from a scheduler, queue, crawler, prefetch path, or bulk loop.

## Parsed responses

The parser accepts a successful `<ret>` response without enabling DTD or entity expansion. It maps status codes 1–4 to distinct exceptions, rejects unknown non-zero statuses, and tolerates absent optional search containers and fields. Result click URLs must be absolute `http` or `https` URLs, and link targets are restricted to normal browsing-context names.

Successful results expose counts, the returned query, searched sections, the automatic-any flag, spelling text, immutable result items, and local pagination offsets. Pagination is calculated from the validated request window rather than trusting FreeFind's remote `pl`, `nl`, or `spelll` links.

Result titles, descriptions, and display URLs are available as safe plain-text fields for normal Blade escaping. `raw` separately preserves the remote highlight-bearing title, description, and display URL values. These raw fields are untrusted remote data and must be sanitized before deliberate HTML rendering; the package never wraps them in `HtmlString` automatically.

## Result components

Render the parsed result set with the package's semantic, unstyled components:

```blade
<x-freefind::results
    :results="$results"
    :previous-url="$previousUrl"
    :next-url="$nextUrl"
    :spelling-url="$spellingUrl"
    heading="Documentation results"
    empty-message="No documentation matched your search."
    heading-id="documentation-results"
    class="search-results"
/>
```

The component renders a heading, an ordered result list, an empty-state message, an optional spelling suggestion, and pagination. It accepts application-generated URLs because the package does not register a search route or assume a route name. Previous and next links appear only when the local `SearchWindow` says that page exists. The component validates absolute `http`/`https` and root-relative navigation URLs, rejects script/data/protocol-relative targets, and escapes all output.

The result item uses the safe `title`, `description`, and `displayUrl` fields. It adds `rel="noopener noreferrer"` for a `_blank` result target and formats a parsed date as a semantic `<time>` element. The package views never render `result->raw` fields; sanitize those explicitly before using them in an application-owned custom view.

## Deliberate boundaries

The request core does not expose deprecated `id`/`ics` fields, `xslt`, DTD enablement, or `search=web`. It does not cache or schedule searches. The transport uses HTTPS, short configured timeouts, no application cookies or authorization headers, one retry for connection/transient-server failures, no automatic redirect following, and a response-size limit. A request is sent only when the client's explicit `execute()` or the builder's terminal `get()` method is called. Exception presentation remains the application's responsibility.

FreeFind result fields are documented as HTML and may contain highlight markup. The package result model keeps safe plain text separate from explicitly raw, untrusted fields; do not render remote XML values as trusted HTML.
