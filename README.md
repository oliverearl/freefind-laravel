# FreeFind Laravel

[![Tests](https://img.shields.io/github/actions/workflow/status/oliverearl/freefind-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/oliverearl/freefind-laravel/actions/workflows/run-tests.yml?query=branch%3Amain)
[![License](https://img.shields.io/github/license/oliverearl/freefind-laravel?style=flat-square)](LICENSE.md)

> **Independent third-party project — no FreeFind affiliation.** This project is created and maintained independently by Oliver Earl. It is not created by, affiliated with, sponsored by, endorsed by, approved by, or maintained by FreeFind.com or any of its owners, authors, employees, or affiliates. “FreeFind” and “FreeFind.com” are used only to identify the external service this package interoperates with; they remain their owners’ trademarks. FreeFind service, website, documentation, search results, and other third-party materials remain the property of their respective rightsholders. See [NOTICE](NOTICE.md) before reusing any upstream material.

Laravel-native integrations for FreeFind Page Search:

- accessible hosted search forms;
- exact crawler comments and metadata through Blade directives;
- opt-in spider context and route annotations; and
- a typed, bounded XML client for subscribed regular Page Search accounts.

The package does not crawl sites, build a local index, configure the FreeFind Control Center, register application routes/controllers, or implement DataSearch. It is currently pre-release: no Packagist package or supported release has been published. The first supported release, when ready, will be the complete `1.0.0`.

Read the [documentation catalogue](docs/README.md) for installation, integration guides, migration, troubleshooting, and project reference material.

## Installation after the first release

This repository is available for review and contribution, but it is not yet published to Packagist or supported for production use. After `1.0.0` is released, install it with:

```bash
composer require oliverearl/freefind-laravel:^1.0
php artisan vendor:publish --tag=freefind-laravel-config
```

Set the public Page Search site ID:

```dotenv
FREEFIND_SITE_ID=3225682
```

The site ID appears in FreeFind URLs and is not a password or API secret. The package validates it as a string.

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

The component submits a `GET` request to FreeFind's HTTPS hosted endpoint with the configured `si` field. It is unstyled, does not load remote JavaScript, and does not require the paid XML feature.

## Crawler markup

Use the directives in the pages FreeFind should index:

```blade
@freefindDocumentDate($page->updated_at)
@freefindKeywords(['laravel', 'freefind'], count: 2)
@freefindNoIndexPage
@freefindLinks([$page->canonicalUrl()])
```

Paired directives cover ignored fragments and links. `freefind.annotate` provides conservative route-level `no-index`, `no-map`, and `not-new` annotations; `freefind.spider` is a separate opt-in presentation/cache hint. Spider detection never authorizes a request and cannot replace authentication or authorization. See the [documentation catalogue](docs/README.md) for the complete markup and configuration guides.

FreeFind must crawl the page again before changed annotations affect its index. Preserve `FreeFind` comments through Blade, HTML minifiers, CDNs, and response optimizers.

## XML search

The XML feed is a subscribed regular Page Search feature. FreeFind documents it for a user-entered query, not for scheduled, robotic, bulk, speculative, queued, or prefetch work. The package exposes an explicit terminal call and leaves the application responsible for its route, validation, authorization, rate limiting, and error UX:

```php
use Freefind\Freefind\Facades\Freefind;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'max:200'],
        ]);

        $results = Freefind::search($validated['q'])
            ->get();

        return view('search.results', compact('results'));
    }
}
```

Only `get()` performs the request. The immutable builder supports sections, relevance/date sorting, page size, and offset. Advanced and refined requests use `Freefind::xml()->execute(...)` with typed request objects. Package-owned result components are semantic and unstyled:

```blade
<x-freefind::results :results="$results" />
```

Result titles, descriptions, and display URLs are safe plain-text fields and are escaped by default. `result->raw` preserves FreeFind's highlight-bearing values as explicitly untrusted data; do not pass them to `{!! !!}` or `HtmlString` without deliberate sanitization. See the [documentation catalogue](docs/README.md) for the complete XML search guide.

## Security and service boundaries

- Configure only HTTPS FreeFind endpoints; the package sends no application cookies or authorization headers.
- XML responses are size-bounded and parsed with network access and entity expansion disabled.
- FreeFind snippets can expose matching text from protected pages; do not index sensitive authenticated content.
- A FreeFind user-agent is spoofable and is never an authorization signal.
- The package does not register routes/controllers or automate account setup, indexing, templates, exclusions, or Control Center changes.
- DataSearch requires a separate ordinary FreeFind account/site ID and is entirely outside `1.0.0`.

Use the [documentation catalogue](docs/README.md) to find troubleshooting and migration guidance for common account, indexing, middleware, and XML issues.

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

The [planning pack](docs/planning/README.md) records accepted design decisions, the capability map, architecture, and delivery state. The downloaded [FreeFind documentation](docs/freefind/README.md) is an immutable research snapshot used for traceability. It is not authored by this project and is not licensed under this repository’s MIT license; see [NOTICE](NOTICE.md) for its attribution and the public-redistribution gate.

## Contributing and security

Read [CONTRIBUTING](CONTRIBUTING.md) before opening a pull request, [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) before participating, and [SECURITY](SECURITY.md) to report a vulnerability privately. General support and remote-service boundaries are documented in [docs/support-and-upgrades.md](docs/support-and-upgrades.md).

## License

The MIT License applies to this project’s original code and documentation. It does not grant rights in FreeFind or other third-party material. See [LICENSE](LICENSE.md) and [NOTICE](NOTICE.md).
