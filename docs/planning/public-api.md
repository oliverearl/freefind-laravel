# Proposed public API

This is a design sketch for naming and usability, not a compatibility commitment. Exact class namespaces may change during implementation, but the examples define the intended developer experience.

## Installation and minimal hosted search

```dotenv
FREEFIND_SITE_ID=3225682
```

```blade
<x-freefind::search-form label="Search this site" />
```

The component renders a semantic `GET` form targeting FreeFind's HTTPS HTML endpoint, with the configured `si` and a `query` field. It works without the paid XML feature.

Custom sections remain an ordinary label-to-identifier map:

```blade
<x-freefind::search-form
    label="Search the knowledge base"
    :sections="[
        '' => 'Everything',
        'manuals' => 'Manuals',
        'releases' => 'Release notes',
    ]"
    language="en"
    hide-results-form
    extended-styles
/>
```

The package rejects `web` as a custom section identifier. If hosted web search is ever exposed, it should be a separately named, explicit option.

## Crawler directives

### Page and head annotations

```blade
<head>
    <title>{{ $page->title }}</title>
    @freefindDocumentDate($page->updated_at)
    @freefindKeywords(['laravel', 'search', 'freefind'], count: 3)
    @freefindMapTitle($page->navigation_title)
</head>
```

```blade
@freefindNoIndexPage
@freefindNoMap
@freefindNotNew
```

```blade
@freefindWhatsNew(
    date: $page->updated_at,
    icon: asset('images/new.svg'),
    comment: 'New Laravel integration guide',
)
```

```blade
@freefindResultImage(
    src: $page->absoluteThumbnailUrl(),
    alt: $page->title,
    width: 160,
    height: 90,
    href: $page->canonicalUrl(),
)
```

### Fragment annotations

```blade
@freefindNoIndex
    <nav>Repeated navigation that should not affect relevance</nav>
@endFreefindNoIndex

@freefindNoFollow
    <a href="{{ route('calendar', ['month' => $nextMonth]) }}">Next month</a>
@endFreefindNoFollow
```

### Discovery and link policy

```blade
@freefindLinks([
    route('guides.show', 'search'),
    route('guides.show', 'indexing'),
])

@freefindLinkPolicy(queries: 'strip', scripts: 'ignore-page')
```

Global policies require explicit syntax and should normally appear in the first crawl entry page:

```blade
@freefindGlobalLinkPolicy(queries: 'ignore', scripts: 'never', robots: 'honour')
```

The query-policy output is provisional until the `noQueries` versus `noFollowQueries` documentation discrepancy is verified.

### Layout hook for route middleware

Applications using annotation middleware add one hook in the document head:

```blade
<head>
    @freefindHead
    {{-- normal application head content --}}
</head>
```

The hook renders request-scoped page annotations collected by middleware or application code. It does not make a network request.

## Middleware

Suggested aliases:

```php
Route::middleware('freefind.spider')->group(function (): void {
    // Detection and response policy; this does not grant access.
});

Route::get('/account', AccountController::class)
    ->middleware('freefind.annotate:no-index,no-map');
```

Application checks use a request-scoped context:

```php
use Freefind\Freefind\Spider\SpiderContext;

if (app(SpiderContext::class)->isSpider()) {
    // Presentation/performance adjustment only. Never an auth decision.
}
```

Facade compatibility convenience:

```php
Freefind::isSpiderRequest();
```

Recommended registration policy: aliases are always registered, while global execution is disabled unless `freefind-laravel.spider.middleware` is enabled.

## Hosted URL generation

```php
$url = Freefind::hostedSearch()
    ->url(query: 'laravel middleware', sections: ['manuals']);
```

Additional explicit builders:

```php
Freefind::hostedSearch()->siteMapUrl();
Freefind::hostedSearch()->whatsNewUrl();
Freefind::hostedSearch()->indexUrl();
```

These helpers should be shipped only after their parameter contracts are covered by a fixture or verified sample. A generic `url(array $parameters)` escape hatch can exist but is marked low-level.

## XML search client

### Laravel-friendly query builder

```php
use Freefind\Freefind\Search\SortOrder;

$results = Freefind::search($request->string('q')->toString())
    ->inSections(['manuals', 'releases'])
    ->sortBy(SortOrder::Relevance)
    ->perPage(15)
    ->startingAt(max(0, $request->integer('offset')))
    ->get();
```

The builder is immutable: each option returns a new builder, and only `get()` performs I/O.

### Advanced search

```php
use Freefind\Freefind\Search\AdvancedQuery;
use Freefind\Freefind\Search\SearchOptions;

$results = Freefind::xml()
    ->execute(
        query: new AdvancedQuery(
            allWords: 'laravel package',
            exactPhrase: 'blade directive',
            anyWords: 'middleware component',
            withoutWords: 'wordpress',
        ),
        options: new SearchOptions(resultsPerPage: 20),
    );
```

### Refinement

```php
$results = Freefind::refine(previousQuery: 'laravel', query: 'middleware')
    ->get();
```

The high-level client will not expose `search=web`, `dtd=y`, `xslt`, deprecated `id`, or `ics`.

### Dependency injection

Consumers who prefer contracts over the facade can inject a connection-aware client:

```php
use Freefind\Freefind\Contracts\SearchClient;

final class SearchController
{
    public function __construct(private SearchClient $search) {}

    public function __invoke(SearchRequest $request): View
    {
        return view('search.results', [
            'results' => $this->search->query($request->validated('q'))->get(),
        ]);
    }
}
```

The injected client represents the configured Page Search account. A future named-account resolver is introduced only if the DataSearch investigation or real multi-account usage justifies it.

## Result objects

Illustrative read-only shape:

```php
final readonly class SearchResults
{
    /** @param list<SearchResult> $items */
    public function __construct(
        public string $query,
        public int $total,
        public int $returned,
        public int $offset,
        public array $sections,
        public ?SpellingSuggestion $spelling,
        public bool $usedAutomaticAnyMode,
        public array $items,
        public SearchWindow $window,
    ) {}
}
```

```php
final readonly class SearchResult
{
    public function __construct(
        public ?int $number,
        public string $title,
        public string $description,
        public Uri $url,
        public ?string $target,
        public string $displayUrl,
        public ?DateTimeInterface $date,
        public RawResultFields $raw,
    ) {}
}
```

`title` and `description` are safe plain text. `raw` preserves FreeFind's HTML-bearing values for consumers who explicitly choose to sanitize and render highlights. Package Blade views never use `{!! !!}` with remote fields.

The standard unstyled component uses those safe fields:

```blade
<x-freefind::results :results="$results" />
```

Consumers that deliberately want FreeFind highlights can use explicitly raw fields in their own sanitized presentation:

```php
$untrustedHighlightedTitle = $result->raw->title;
$untrustedHighlightedDescription = $result->raw->description;
```

The package preserves these raw values in `1.0.0` but does not label them safe or wrap them in `HtmlString`. A later allowlist sanitizer can make highlighted rendering convenient without weakening the default.

Pagination stays within the original validated request:

```blade
@foreach ($results->items as $result)
    <article>
        <a href="{{ $result->url }}" @if ($result->target) target="{{ $result->target }}" @endif>
            {{ $result->title }}
        </a>
        <p>{{ $result->description }}</p>
    </article>
@endforeach

@if ($results->window->hasPrevious())
    <a href="{{ route('search', ['q' => $results->query, 'offset' => $results->window->previousOffset()]) }}">
        Previous
    </a>
@endif
```

The application builds its own pagination links rather than rendering FreeFind's `pl`, `nl`, or `spelll` strings.

## Testing API

The package should provide an ergonomic fake without replacing Laravel's normal HTTP fake:

```php
Freefind::fake([
    SearchFixture::for('blade directives')->fromFile(
        base_path('tests/Fixtures/freefind/success.xml'),
    ),
]);

// ...exercise application...

Freefind::assertSearched(fn (SentSearch $search): bool =>
    $search->query === 'blade directives'
    && $search->sections === ['manuals']
);
```

Lower-level tests can continue to use `Http::fake()` and the XML parser directly.

## Naming rules

- PHP uses `Freefind`, matching the service's current package namespace; user-facing prose uses “FreeFind.”
- Blade directives use a `freefind` prefix and paired directives have symmetrical names.
- Avoid abbreviations such as `q1`, `nret`, or `srt` outside the encoder/parser layer.
- Methods describe behavior (`noIndexPage`, `sortByDate`) rather than legacy syntax.
- APIs that accept raw HTML include `Trusted` or `Raw` in the type/method name.
