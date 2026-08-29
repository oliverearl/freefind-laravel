# FreeFind capability map

This is the traceability document for the downloaded FreeFind material. “Package treatment” is a design recommendation, not a claim made by FreeFind.

## Service model and constraints

| FreeFind capability or rule | Evidence | Package treatment |
|---|---|---|
| FreeFind remotely spiders linked pages and hosts the search result experience. | [How it works](../freefind/1-getting-started/1-how-it-works.md) | Keep crawling, indexing, and Control Center operations outside the package. |
| Search boxes may appear on many pages and are ordinary HTML forms. | [Page search setup](../freefind/1-getting-started/2-page-search-setup.md) | Provide an accessible Blade form component and lower-level renderer. |
| Result pages include basic search, advanced search, results, site map, what's new, and index surfaces. | [Page search setup](../freefind/1-getting-started/2-page-search-setup.md) | Model only documented URL contracts; do not imply all surfaces are in the XML feed. |
| FreeFind-hosted custom templates use `::title::` and `::content::`, must use absolute URLs or `<base>`, and are limited to 64 KiB. Free accounts have further ad/JavaScript restrictions. | [Custom templates](../freefind/2-display-how-tos/1-using-custom-templates.md) | Document integration and perhaps export a template later; no upload automation is documented. |
| Extended result CSS classes require a `css` hidden input. | [Style sheets](../freefind/2-display-how-tos/2-using-style-sheets.md) | Search form option; publish CSS-class reference, but do not couple package views to FreeFind's table markup. |
| Results-page search box can be hidden with `nsb`. | [Hiding the search panel](../freefind/2-display-how-tos/7-hiding-the-results-page-search-box.md) | Boolean search-form option. |
| Prompt language is selected with `lang`; unsupported values fall back to English and web results stay English. | [Non-English setup](../freefind/2-display-how-tos/5-setup-for-non-english-languages.md) | Validated language enum plus deliberate raw-code escape hatch for forward compatibility. |
| Sections use single-word identifiers; `web` is reserved; multiple checkbox values may be sent. | [Sections](../freefind/3-content-how-tos/3-how-to-use-sections.md) | `Section` value object, repeated form fields, connection-level named labels. |
| Index changes require another spider run. | [Page search setup](../freefind/1-getting-started/2-page-search-setup.md) | Every crawl-time API is documented as “takes effect after re-index.” |
| Index logs expose crawler requests, server responses, summary counts, and builder inclusion status. | [Indexing logs](../freefind/3-content-how-tos/1-indexing-logs.md) | Troubleshooting guide; no parser until log export format and access are documented. |

## Crawl-time HTML features

| Concern | FreeFind syntax/behavior | Proposed package API |
|---|---|---|
| Weighted keywords | `<!-- FreeFind Keywords Words="…" Count="5" -->` | `@freefindKeywords(...)` and `Keywords` value object; positive bounded integer policy. |
| Search description | Standard description meta tag; duplicates may be discarded. | Prefer Laravel/SEO tooling; document interoperability rather than own generic `<meta>` tags. |
| Search title | Standard `<title>`. | Leave to application layout. |
| Document date | `document-date` meta, including GMT offset, overrides HTTP Last-Modified. | `@freefindDocumentDate($date)` with `DateTimeInterface` formatting. |
| Ignore page text, follow links | `<!-- FreeFind No Index Page -->`; standard robots `noindex` is ignored. | `@freefindNoIndexPage` and `freefind.annotate` middleware. |
| Ignore fragment text | Begin/end no-index comments; link following unaffected. | Paired `@freefindNoIndex` / `@endFreefindNoIndex`. |
| Do not follow page links | FreeFind or robots `nofollow` meta. | Page policy object/directive; do not alter application robots behavior unless requested. |
| Do not follow fragment links | Begin/end `FreeFind nofollow` comments. | Paired `@freefindNoFollow` / `@endFreefindNoFollow`. |
| Query-string link policy | Global strip or do-not-follow meta. | Global/page policy emitted early in the first crawled page. See source discrepancy below. |
| JavaScript link extraction | Global never-follow or page follow/no-follow meta; deprecated fragment tag exists. | Global/page directives only; omit deprecated fragment tag. |
| Ignore standard robots meta | Global `noRobotsTag`. | Expert-only configuration/directive with warning. |
| Explicit discovery | `FreeFind Links` comment with one or more same-site URLs. | `@freefindLinks([...])`, absolute-URL and allowed-host validation. |
| Result image | `FreeFind image` comment or standard thumbnail meta; image attributes and link supported. | Typed `ResultImage`; absolute `src`, optional absolute `href`, conservative attribute allowlist. |
| Site-map exclusion/title | `No Map` and `Map Title`. | Page directives/middleware metadata. |
| What's-new exclusion/entry | `Not New` and `New` with date, icon, comment. | `WhatsNewEntry` value object; absolute icon URL. |
| Site-map structure | Determined by discovery order/links; no-follow can suppress an early relationship. | Explain behavior; avoid presenting directives as a deterministic sitemap builder. |

The complete source for the core tag set is the [HTML tag reference](../freefind/5-reference/2-html-tag-reference.md); result images are detailed separately in [How to use images](../freefind/2-display-how-tos/3-images-in-search-results.md).

## DataSearch

DataSearch is a different FreeFind operating mode for searching “lists of things.” Page Search indexes a page and returns a title, excerpt, and link to that page. DataSearch indexes each marked fragment on a page as a separate item and returns the item's captured HTML itself. One product, directory, biography, classified advert, or other record can therefore become one result even when many records share a Laravel page. See [Data Search setup](../freefind/4-advanced/2-basic-data-search-setup.md).

### Relationship to an existing FreeFind account

DataSearch cannot be switched on alongside Page Search for the same FreeFind account/site ID. Both accounts are ordinary FreeFind accounts, but they have separate account credentials and site IDs. One is configured for regular Page Search and the other for DataSearch. This is not a special DataSearch subscription or an additional mode within the existing account.

FreeFind's documentation requires two accounts when both capabilities are wanted and prohibits those accounts from sharing the same URL/email combination. In practice, the Page Search account should use the site's home page and the second account should use a different page on the same site. The email may be reused because it is the combined URL/email pair that must differ, but the accounts and credentials remain separate. See [Data Search setup](../freefind/4-advanced/2-basic-data-search-setup.md).

### Operational differences

- The first page read by the second account must contain `<!-- FreeFind Index Listings Only id="…" -->` near its start.
- Every searchable item is delimited by `FreeFind Listing` comments; a page may contain many items.
- An optional category contains HTML prepended to every result item from that page.
- Every link and image URL captured inside an item/category must be absolute because FreeFind serves the result HTML from its own origin.
- DataSearch has no site-map feature, and changes still require a re-spider.
- The captured fragments become third-party-hosted result presentation, so they require stricter HTML and privacy review than ordinary Page Search annotations.
- The documented XML feed is limited to regular site search. There is no documented way for this Laravel package to fetch DataSearch items through XML and render them locally.

### Scope recommendation

All DataSearch implementation is deferred beyond `1.0.0`. The first release explains the two-account requirement but ships no DataSearch configuration, directives, components, or client behavior. A later version can use the available dedicated DataSearch account with non-sensitive sample listings to validate the feature before designing its API.

Package consequences:

- if DataSearch is added, configuration needs an explicit mode and support for a second site ID;
- DataSearch output must be an opt-in module, not a flag casually mixed into page search;
- a listing directive cannot reliably validate arbitrary rendered slot HTML at compile time, so optional runtime absolute-URL validation should be available in local/testing environments;
- category HTML is executable presentation markup later served by FreeFind and needs a deliberately unsafe/HTML-safe type rather than an ordinary string;
- the XML documentation explicitly limits its feed to regular page search, so the XML client must reject DataSearch accounts unless live evidence and terms establish support.

## XML request contract

The feed is `GET https://search.freefind.com/find.xml`, available only to subscription accounts, only for regular site search, and only in response to a user-entered query. Requests are form-URL-encoded. See the [XML API reference](../freefind/4-advanced/1-xml-api.md).

| Input | Rules | Model |
|---|---|---|
| `si` | Required site/account ID; documented as string. | Connection-owned non-empty string. |
| `query` | Simple words or Boolean expression; one value. | `SimpleQuery`; mutually exclusive with `q1`–`q4`. |
| `q1` | All words. Ignored when `query` exists. | `AdvancedQuery::allWords`. |
| `q2` | Exact phrase. Ignored when `query` exists. | `AdvancedQuery::exactPhrase`. |
| `q3` | Any word. Ignored when `query` exists. | `AdvancedQuery::anyWords`. |
| `q4` | Excluded words. Ignored when `query` exists. | `AdvancedQuery::withoutWords`. |
| `asen`, `csen` | `y`/`n`, default `n`. | Booleans serialized explicitly only when non-default. |
| `dl` | `s`, `m`, `l`; default `m`. | `DescriptionLength` enum. |
| `dtd` | `y`/`n`; default `n`. | Always `n` in the high-level client. No public enable switch. |
| `fr` | Non-negative zero-based result offset. | Integer validation and pagination object. |
| `mode` | `any`/`all`, simple `query` only; default `all`. | `MatchMode` enum on `SimpleQuery`. |
| `rpp` | 1–25; default 10; site search only. | Validated integer. |
| `oq`, `search=these` | Refinement requires previous and new queries. | Separate `RefinedQuery` so invalid combinations are unrepresentable. |
| `s` | `site` or subsection; repeatable. | Ordered list encoded as repeated `s=value` pairs, not bracket syntax. |
| `search` | Docs list `new`, `these`, `web`. | Only `new` and `these`; high-level XML API rejects `web`. |
| `srt` | `r` relevance or `d` date. | `SortOrder` enum and date-support warning. |
| `stm` | empty auto, `n`, `en`, `pt`. | `Stemming` enum. |
| `xslt` | Fully specified, percent-encoded XSLT URL. | Exclude from high-level server client; transformation belongs locally. |
| `ics`, `id` | Deprecated/ignored for XML. | Do not expose. Parse legacy pagination links without relying on them. |

## XML response contract

| Response data | Treatment |
|---|---|
| `sts` | Map 0–4 into status enum; non-zero response becomes a typed service exception with `msg`. |
| `srch/nttl`, `nret`, `idx` | Total, returned count, and offset on `SearchResults`. Validate as non-negative integers. |
| `q`, `qe` | Preserve returned query and encoded query, but generate application pagination from the original request rather than trusting remote links. |
| `spell`, `spelle`, `spelll` | Model optional suggestion text; do not render the remote URL blindly. |
| `ss/s` | Ordered searched-section list. |
| `pl`, `nl` | Preserve only as diagnostic raw data if useful; prefer local pagination request objects. |
| `aor` | Boolean flag indicating automatic any-word fallback. |
| `items/i` | Immutable list of result items. |
| `n`, `t`, `d`, `u`, `tg`, `du`, `dt` | Item number, title, excerpt, click URL, target, display URL, and optional date. Validate click URL scheme/host policy before rendering. |

The reference labels several response strings as “HTML.” Titles and descriptions in the example contain `<b>` highlights. They must not become Laravel `HtmlString` instances automatically. Default package views escape or render sanitized text; any raw/highlighted accessor must be unmistakably opt-in.

### Verified live observations

On 2026-08-29, one deliberate live-contract trial used site ID `56428610` and the operator-supplied query `Uses`. FreeFind's XML endpoint was reachable, but the response reported the unauthorized-feed status, which the package mapped to `UnauthorizedXmlFeedException`. This confirms transport reachability and status mapping only; it does not establish a successful response shape or provide a contract fixture. A subscribed Page Search account or XML access enabled for this site is required before repeating the trial.

## Source discrepancies and ambiguities

These should become fixtures or live-contract questions, not silent assumptions:

1. The exclusion guide names a global query policy `noQueries`, while the tag reference names it `noFollowQueries`. Prefer the tag reference but verify against a controlled live site before promising the directive.
2. The XML introduction says only regular site search is available, while the request table includes `search=web`. The package should not expose XML web search.
3. The XML DTD contains apparent typos (`spelle` linked as `ret`, item `date` vs `dt`) and says `srch` is optional in one place but required elsewhere. The parser must be tolerant of absent optional containers and not validate against the published DTD.
4. `s` defaults to `site`, while the HTML section form uses an empty value for the entire site. Keep separate HTML and XML encoders.
5. The docs say XML response fields are HTML but also define XML PCDATA. Fixtures must cover whether markup is entity-escaped text, CDATA, or nested elements.
6. The XML example's copyright comment appears malformed in the downloaded Markdown. Never depend on comments or exact whitespace.
7. The DataSearch tutorial says “up to four” page types but lists two. Do not build behavior around the claimed count.
8. Language documentation lists two traditional-Chinese codes (`zh2`, `zh3`) and two Romanian codes (`ro`, `ro2`) without explaining the variants. Preserve documented codes and label variants neutrally.
9. Result-image support is in a how-to but absent from the downloaded core tag-reference list. Treat its output as supported but cover it with dedicated fixtures.
10. The current package casts `FREEFIND_SITE_ID` to an integer, while FreeFind calls it a string and DataSearch embeds it as text. The package should migrate to a validated string.

## Security-critical facts

- A FreeFind site ID is public, appears in URLs, is guessable, and is not changeable. It is not an API secret.
- Password-protected indexing uses HTTP Basic authentication; credentials are transmitted/stored with low security according to FreeFind, and search snippets remain public to anyone with the site ID.
- Password-protected indexed documents can leak matching extracts even though clicking the source still requires authentication.
- The XML feed cannot be used for robotic queries and results may not be reused or meta-searched according to the example response notice.
- FreeFind ignores the `noindex` portion of the standard robots meta tag; its own no-index page comment or Control Center exclusion is required.
- HTML minification/CDN tooling that removes comments can destroy most crawler annotations.

The package documentation should strongly discourage password-protected indexing and point to [FreeFind's full warning](../freefind/3-content-how-tos/5-indexing-password-protected-pages.md).
