# Crawler markup

The package renders the non-deprecated Page Search annotations from FreeFind's [HTML tag reference](freefind/5-reference/2-html-tag-reference.md). These are crawl-time hints: they take effect after FreeFind spiders the page again, and they do not make an HTTP request while a Blade view renders.

## Page annotations

Use the directives wherever the corresponding FreeFind comment or meta tag is valid:

```blade
@freefindDocumentDate($page->updated_at)
@freefindKeywords(['laravel', 'search', 'freefind'], count: 3)
@freefindMapTitle($page->navigation_title)
@freefindLinks([
    route('guides.show', 'search'),
    route('guides.show', 'indexing'),
])
@freefindNoIndexPage
@freefindNoMap
@freefindNotNew
@freefindWhatsNew(
    date: $page->updated_at,
    icon: asset('images/new.svg'),
    comment: 'New Laravel integration guide',
)
@freefindResultImage(
    src: $page->absoluteThumbnailUrl(),
    alt: $page->title,
    width: 160,
    height: 90,
    href: $page->canonicalUrl(),
)
```

Dates are formatted as `d M Y H:i:s T` using Laravel's date factory. Keywords have a defensive limit of 100 words and a weight from 1 to 100. Links, icons, image sources, and image targets must be absolute `http` or `https` URLs without credentials. FreeFind still requires explicit links to belong to the configured site; the package validates URL shape but cannot infer that site from a Laravel route.

## Fragment annotations

Paired directives render the exact begin/end comments and reject mismatched closing markers:

```blade
@freefindNoIndex
    <nav>Repeated navigation that should not affect relevance</nav>
@endFreefindNoIndex

@freefindNoFollow
    <a href="{{ route('calendar') }}">Calendar</a>
@endFreefindNoFollow
```

All values placed inside FreeFind comments are escaped and validated against comment breakouts, control characters, invalid UTF-8, and arbitrary image/link attributes. Result-image extra attributes are deliberately allow-listed rather than accepting an unparsed attribute string.

## Link policies and head collection

The policy directives render FreeFind's `FreeFind` meta tags:

```blade
@freefindGlobalLinkPolicy(queries: 'ignore', scripts: 'never', robots: 'ignore')
@freefindLinkPolicy(queries: 'strip', scripts: 'ignore-page')
```

Supported query policies are `strip` and `ignore`; script policies are `follow`, `ignore-page`, and `never`; the robots policy is `honour` or `ignore`. The package uses `noFollowQueries`, matching the current HTML tag reference. Another downloaded FreeFind guide calls this value `noQueries`; verify the live behavior before making this directive a site-wide dependency.

Applications that collect annotations from middleware or application code can place them in the document head:

```blade
<head>
    @freefindHead
    {{-- normal application head content --}}
</head>
```

The hook only renders the request-scoped collector. It does not authorize the request, inspect the user agent, or contact FreeFind.

The package does not provide directives for DataSearch (`Index Listings Only`, `Listing`, `Category`, and related tags), deprecated `<nofollow>`/`<nofollowscript>`, or the deprecated `FreeFind No Parameters` comment. DataSearch is outside the `1.0.0` scope.

After changing annotations, ask FreeFind to re-spider the site. Ensure Blade, HTML minifiers, CDNs, and response optimizers preserve `FreeFind` comments; removing them prevents the crawler from seeing the intended instructions.
