# XML search request core

The package's XML integration is for subscribed regular Page Search accounts only. The current milestone provides typed request models, deterministic HTTPS URL encoding, and a bounded Laravel HTTP transport; the tolerant response parser and result models are still being implemented. Do not treat these request objects and transport as a complete server-side XML client yet.

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

## Deliberate boundaries

The request core does not expose deprecated `id`/`ics` fields, `xslt`, DTD enablement, or `search=web`. It does not cache or schedule searches. The transport uses HTTPS, short configured timeouts, no application cookies or authorization headers, one retry for connection/transient-server failures, no automatic redirect following, and a response-size limit. A request is sent only when its explicit transport method is called. It returns a bounded transport response; status mapping and XML parsing belong to the next milestone.

FreeFind result fields are documented as HTML and may contain highlight markup. The eventual package result model will keep safe plain text separate from explicitly raw, untrusted fields; do not render remote XML values as trusted HTML.
