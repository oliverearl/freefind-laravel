# FreeFind Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/freefind/freefind-laravel.svg?style=flat-square)](https://packagist.org/packages/freefind/freefind-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/freefind/freefind-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/freefind/freefind-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/freefind/freefind-laravel.svg?style=flat-square)](https://packagist.org/packages/freefind/freefind-laravel)

Laravel-native integrations for FreeFind Page Search:

- accessible hosted search forms;
- exact crawler comments and metadata through Blade directives;
- opt-in spider context and route annotations; and
- a typed, bounded XML client for subscribed regular Page Search accounts.

The package does not crawl sites, build a local index, configure the FreeFind Control Center, register application routes/controllers, or implement DataSearch. The first supported release is the complete `1.0.0`; internal development milestones are not published prereleases.

## Installation

```bash
composer require freefind/freefind-laravel
php artisan vendor:publish --tag=freefind-laravel-config
```

Set the public Page Search site ID:

```dotenv
FREEFIND_SITE_ID=3225682
```

The site ID appears in FreeFind URLs and is not a password or API secret. The package validates it as a string. See [configuration](docs/configuration.md) for endpoint, timeout, spider, and middleware settings.

## Hosted search

For a normal browser-submitted search, render the semantic form:

```blade
<x-freefind::search-form
    label="Search our documentation"
    :sections="[
        '' => 'Everything',
        'manuals' => 'Manuals',
        'releases' => 'Release notes',
    ]"
    language="en"
/>
```

The component submits a `GET` request to FreeFind's HTTPS hosted endpoint with the configured `si` field. It is unstyled, does not load remote JavaScript, and does not require the paid XML feature. See [hosted search](docs/hosted-search.md).

## Crawler markup

Use the directives in the pages FreeFind should index:

```blade
@freefindDocumentDate($page->updated_at)
@freefindKeywords(['laravel', 'freefind'], count: 2)
@freefindNoIndexPage
@freefindLinks([$page->canonicalUrl()])
```

Paired directives cover ignored fragments and links. `freefind.annotate` provides conservative route-level `no-index`, `no-map`, and `not-new` annotations; `freefind.spider` is a separate opt-in presentation/cache hint. Spider detection never authorizes a request and cannot replace authentication or authorization. See [crawler markup](docs/markup.md) and [configuration](docs/configuration.md).

FreeFind must crawl the page again before changed annotations affect its index. Preserve `FreeFind` comments through Blade, HTML minifiers, CDNs, and response optimizers.

## XML search

The XML feed is a subscribed regular Page Search feature. FreeFind documents it for a user-entered query, not for scheduled, robotic, bulk, speculative, queued, or prefetch work. The package exposes an explicit terminal call and leaves the application responsible for its route, validation, authorization, rate limiting, and error UX:

```php
use Freefind\Freefind\Freefind;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:200'],
        ]);

        $results = app(Freefind::class)
            ->search($validated['q'])
            ->get();

        return view('search.results', compact('results'));
    }
}
```

Only `get()` performs the request. The immutable builder supports sections, relevance/date sorting, page size, and offset. Advanced and refined requests use `app(Freefind::class)->xml()->execute(...)` with typed request objects. Package-owned result components are semantic and unstyled:

```blade
<x-freefind::results :results="$results" />
```

Result titles, descriptions, and display URLs are safe plain-text fields and are escaped by default. `result->raw` preserves FreeFind's highlight-bearing values as explicitly untrusted data; do not pass them to `{!! !!}` or `HtmlString` without deliberate sanitization. See [XML search](docs/xml-search.md).

## Security and service boundaries

- Configure only HTTPS FreeFind endpoints; the package sends no application cookies or authorization headers.
- XML responses are size-bounded and parsed with network access and entity expansion disabled.
- FreeFind snippets can expose matching text from protected pages; do not index sensitive authenticated content.
- A FreeFind user-agent is spoofable and is never an authorization signal.
- The package does not register routes/controllers or automate account setup, indexing, templates, exclusions, or Control Center changes.
- DataSearch requires a separate ordinary FreeFind account/site ID and is entirely outside `1.0.0`.

Read [troubleshooting](docs/troubleshooting.md) for common account, indexing, middleware, and XML failures. [Migration guidance](docs/migration.md) maps legacy snippets to package APIs.

## Testing

```bash
composer format
composer analyse
composer test
```

Normal tests use local XML fixtures and Laravel HTTP fakes. The optional live check is never run by normal or scheduled CI and requires an operator-supplied subscribed account and query:

```bash
FREEFIND_LIVE_CONTRACT=1 \
FREEFIND_SITE_ID=3225682 \
FREEFIND_LIVE_QUERY='blade directives' \
vendor/bin/pest --group=live-contract
```

## Planning and upstream references

The [planning pack](docs/planning/README.md) records accepted design decisions, the capability map, architecture, and delivery state. The downloaded [FreeFind documentation](docs/freefind/README.md) is an immutable research snapshot and remains the source for legacy protocol details.

## License

The MIT License. See [LICENSE](LICENSE.md).
