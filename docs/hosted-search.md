# Hosted search

The hosted search integration sends a browser's normal `GET` form submission to FreeFind. It does not make a server-side request, require the paid XML feature, or register an application route.

## Configuration

Set the Page Search site ID in the application environment and publish the package configuration if it is not already published:

```dotenv
FREEFIND_SITE_ID=3225682
```

```bash
php artisan vendor:publish --tag="freefind-laravel-config"
```

The site ID identifies the public FreeFind index; it is not a password. The package uses FreeFind's HTTPS hosted endpoint by default. Endpoint overrides remain subject to HTTPS validation.

## Search form

Render the semantic, unstyled component in any Blade view:

```blade
<x-freefind::search-form label="Search this site" />
```

The component owns the form action, `GET` method, `query` input, and `si` site-ID field. It escapes labels and the current query value, and submits with a normal button so keyboard users can use the form without JavaScript.

### Sections and language

Sections are an identifier-to-label map. An empty identifier means the whole site. Each section is emitted as its own `s` field; the HTML name is intentionally `s`, not `s[]`, because FreeFind expects repeated fields:

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

The documented language codes are validated before they reach the form. The identifier `web` is reserved by FreeFind and is rejected as a custom section. If a query contains a selected section, the component preserves that selection when rendered again.

`hide-results-form` emits FreeFind's `nsb` hidden field. `extended-styles` emits the empty `css` hidden field used to request FreeFind's extended result CSS classes. These options affect the FreeFind-hosted results page, not this package's unstyled view.

### Attributes and slots

Normal HTML attributes are appended to the owned `<form>` element. The component always retains its configured action and `GET` method, so an attribute cannot redirect the form to another endpoint or change it into a non-GET submission. `target` is an explicit validated component property:

```blade
<x-freefind::search-form
    input-id="site-search"
    target="_blank"
    class="site-search"
    aria-describedby="site-search-help"
>
    <x-slot:before>
        <p id="site-search-help">Search our public documentation.</p>
    </x-slot:before>
    <x-slot:after>
        <small>Results open on FreeFind.</small>
    </x-slot:after>
</x-freefind::search-form>
```

The `before` and `after` slots add application-owned content inside the form while leaving the protocol fields in the package's control. The published view is semantic and has no CSS or JavaScript dependency.

When no `query` prop is supplied, the component uses the current request's scalar `query` value. This is useful when a local page redisplays the search form after a `GET` submission.

## URL helpers

Inject `HostedSearch` or use the package facade to create links without assembling FreeFind's parameters manually:

```php
use Freefind\Freefind\Facades\Freefind;

$search = Freefind::hostedSearch();

$results = $search->url(
    query: 'laravel middleware',
    sections: ['manuals', 'releases'],
    language: 'en',
);

$siteMap = $search->siteMapUrl();
$whatsNew = $search->whatsNewUrl();
$index = $search->indexUrl();
```

Each result is a `HostedSearchUrl` value object and can be cast to a string or read through its `value` property. Values are URL-encoded as HTML form data, so spaces become `+` and repeated sections remain repeated `s` parameters.

## FreeFind Control Center steps

The package cannot create or configure a FreeFind account. Complete these steps in the FreeFind Control Center:

1. Create or select a regular Page Search site and copy its site ID into `FREEFIND_SITE_ID`.
2. Configure the site's crawl settings and start a build/re-index when the site is ready.
3. Choose the hosted result presentation, language, sections, and any FreeFind custom-template settings there.
4. Keep the Laravel form pointed at the package's generated HTTPS endpoint; it supplies the configured `si` value.

FreeFind-hosted custom templates are a separate artifact from this package's Blade view. The downloaded FreeFind documentation describes a 64 KiB template limit and requires absolute links or a suitable `<base>` element; free accounts can also have advertising and JavaScript restrictions. The package does not upload templates or automate Control Center operations. See [Using Custom Templates](freefind/2-display-how-tos/1-using-custom-templates.md), [Setup for Non-English Languages](freefind/2-display-how-tos/5-setup-for-non-english-languages.md), and [Hiding the Results Page Search Box](freefind/2-display-how-tos/7-hiding-the-results-page-search-box.md) for the upstream constraints.

## Boundaries and security

The form is a browser integration, so the browser sends the search to FreeFind after the user submits it. Do not treat the site ID or a FreeFind user-agent match as authorization, and do not expose authenticated or sensitive content to a public FreeFind index. The component does not send application cookies or credentials to FreeFind and does not load remote JavaScript.

The hosted form and URL helpers are separate from the subscriber XML client. XML support will document its own paid-account, user-initiated-query, transport, and result-safety requirements.
