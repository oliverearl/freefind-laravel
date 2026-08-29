# Migrating legacy FreeFind snippets

The package replaces repeated raw snippets with typed PHP objects and Blade APIs. It does not change how FreeFind crawls a page: updated annotations still require another crawl, and comment-preserving delivery infrastructure remains part of the application deployment.

| Legacy FreeFind material | Package API | Boundary |
| --- | --- | --- |
| Hosted `<form>` with `si`, `query`, repeated `s`, `lang`, `nsb`, or `css` fields | `<x-freefind::search-form>` or `HostedSearch` | Browser submits to FreeFind; no Laravel route is registered. |
| `<!-- FreeFind Keywords ... -->` | `@freefindKeywords(...)` | Values are validated and comment-escaped. |
| `document-date` metadata | `@freefindDocumentDate(...)` | Uses Laravel's date factory and `DateTimeInterface`. |
| `<!-- FreeFind No Index Page -->` / fragment markers | `@freefindNoIndexPage`, `@freefindNoIndex` / `@endFreefindNoIndex` | Deprecated `<nofollow>` forms are not emitted. |
| `FreeFind nofollow` fragment markers | `@freefindNoFollow` / `@endFreefindNoFollow` | Link-following hints do not authorize visitors. |
| `FreeFind Links` comment | `@freefindLinks(...)` | Absolute URL shape is validated; same-site ownership remains an application decision. |
| `No Map`, `Map Title`, `Not New`, and `New` comments | `@freefindNoMap`, `@freefindMapTitle`, `@freefindNotNew`, `@freefindWhatsNew(...)` | Effects begin after FreeFind re-spiders the page. |
| `FreeFind image` comment | `@freefindResultImage(...)` | Absolute URLs and conservative attributes are required. |
| Global/page link-policy meta tags | `@freefindGlobalLinkPolicy(...)`, `@freefindLinkPolicy(...)` | The query-policy naming discrepancy is documented; verify before making it site-wide. |
| Hand-built `find.xml` query strings | `XmlSearchRequest`, `XmlRequestEncoder`, or `Freefind::search(...)->get()` | The XML feed is for subscribed regular Page Search and explicit user-entered searches. |
| Manual XML field interpolation into HTML | `SearchResults` and `<x-freefind::results>` | Safe plain text is the default; raw highlights are explicitly untrusted. |

## Migration sequence

1. Configure the existing regular Page Search site ID and verify that it is treated as a public identifier, not a secret.
2. Replace hosted forms first; confirm the browser request still contains the expected `si`, query, and section values.
3. Add crawler directives to the pages FreeFind should crawl. Keep `@freefindHead` in the document head when using route annotations.
4. Configure the `freefind.spider` alias only where the application wants its presentation/cache hint. Keep it independent of authentication and authorization middleware.
5. For a subscribed bespoke results page, validate the incoming query in an application-owned controller and call the XML client only from that user request.
6. Replace raw result interpolation with safe fields or the package result components. Sanitize `raw` highlight fields in application code if deliberate rich rendering is required.
7. Ask FreeFind to re-spider changed annotations and confirm that deployment tooling preserves comments.

The package does not provide compatibility aliases for unpublished exploratory APIs. Deprecated FreeFind syntax, Control Center operations, and DataSearch require the explicit boundaries described in the [planning decisions](planning/decisions-and-open-questions.md).
