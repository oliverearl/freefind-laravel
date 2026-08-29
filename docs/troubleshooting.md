# Troubleshooting

## Configuration fails when the package is resolved

Check `FREEFIND_SITE_ID` and the published config. Site IDs must be non-empty strings. Every configured endpoint must be an HTTPS URL without credentials, query parameters, or fragments. XML HTTP settings must use a connect timeout no greater than the total timeout and a positive response-size limit.

## FreeFind does not show changed annotations

Crawler directives are read during a later FreeFind crawl, not during a Laravel request. Trigger or wait for a re-index in the FreeFind Control Center. Inspect the delivered HTML and ensure `FreeFind` comments survive Blade rendering, minification, CDN transformations, and caching layers.

## Spider behavior appears on the wrong routes

Spider detection is opt-in. Use `freefind.spider` on the route group that needs the presentation/cache hint, or explicitly enable `spider.middleware` for global execution. It never bypasses authentication or authorization. If session startup must be avoided, create an application-owned route group outside `StartSession`; the package does not mutate the process-global session driver.

## XML searches fail before returning results

Confirm that the account is subscribed to the regular Page Search XML feature. Status errors are intentionally distinct:

- `UnauthorizedXmlFeed`: the XML feed is not authorized for the account;
- `InvalidOrClosedAccount`: the account is invalid or closed;
- `RejectedSearchParameters`: FreeFind rejected the request parameters; and
- `FreefindServiceError`: a generic or unknown service status was returned.

`SearchTransportException` indicates an HTTP, timeout, redirect, or response-size problem. `MalformedXmlResponse` indicates that the bounded response was not a valid supported XML shape. The application should choose the user-facing message; exception messages do not include raw XML or remote `<msg>` content.

## XML search is unexpectedly not sent

Constructing a request, resolving the client, or configuring the immutable builder does not perform I/O. Only `execute()` or `get()` sends a request. This is intentional: call it from the application response to a validated user search, not from a view, scheduler, queue, crawler, prefetch path, or bulk loop.

## Result highlights are missing or unsafe

The standard components use escaped plain-text `title`, `description`, and `displayUrl` fields. FreeFind's highlight-bearing values are available under `result->raw` only for an application that explicitly chooses to sanitize and render them. The package never treats them as `HtmlString` automatically.

## Running the remote contract check

Normal tests do not contact FreeFind. A deliberate operator run requires a subscribed regular Page Search site ID, a controlled non-sensitive query, and all three environment values:

```bash
FREEFIND_LIVE_CONTRACT=1 \
FREEFIND_SITE_ID=3225682 \
FREEFIND_LIVE_QUERY='blade directives' \
vendor/bin/pest --group=live-contract
```

The test accepts a successful zero-result response. Do not set these variables in normal or scheduled CI.
