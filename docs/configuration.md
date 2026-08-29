# Configuration

FreeFind Laravel uses one default Page Search account. Set its public site ID in the application environment:

```dotenv
FREEFIND_SITE_ID=3225682
```

The site ID identifies the FreeFind account; it is not a password or API secret. Do not use it as an authorization check.

Publish the package configuration when you need to customize its defaults:

```bash
php artisan vendor:publish --tag=freefind-laravel-config
```

The published file contains HTTPS endpoints for hosted HTML search, the subscriber XML feed, and the site index. It also contains conservative HTTP bounds for the XML client and spider settings:

```php
'endpoints' => [
    'html' => 'https://search.freefind.com/find.html',
    'xml' => 'https://search.freefind.com/find.xml',
    'index' => 'https://search.freefind.com/siteindex.html',
],

'http' => [
    'connect_timeout' => 2,
    'timeout' => 5,
    'max_response_bytes' => 2_000_000,
],

'spider' => [
    'middleware' => false,
    'user_agents' => ['freefind/2.1'],
    'cache_control' => 'public, max-age=3600',
],
```

Site IDs must remain strings. All configured service endpoints must be HTTPS URLs without credentials, query strings, or fragments. The package validates this configuration when its services are resolved, so a missing or malformed site ID fails clearly rather than being silently converted to `0`.

## Spider middleware

Spider handling is opt-in. The package always provides the `freefind.spider` middleware alias, but does not add it to every application request unless `spider.middleware` is explicitly set to `true`.

Prefer route-level opt-in when only crawlable pages need the behavior:

```php
Route::middleware('freefind.spider')->group(function (): void {
    // Public pages that FreeFind may crawl.
});
```

Detection is only a presentation and response-policy hint. A user-agent can be spoofed, so spider detection must never bypass authentication, authorization, CSRF, tenancy, unpublished-content rules, or other access controls. The middleware stores a request-local context and may apply the configured cache policy to ordinary HTML responses. It does not change the session driver or call PHP's global `header()` function.

Keep a dedicated crawlable route group outside `StartSession` if your application needs crawler requests to avoid session startup. That decision belongs to the application because middleware order and authentication requirements are application-specific.
