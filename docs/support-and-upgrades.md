# Support and upgrades

This repository is pre-release. No Packagist package or supported release exists yet, and it should not be used in production. The first supported package release, when it is ready, will be `1.0.0`. Its intended runtime boundary is PHP 8.4 or newer within the package constraint and Laravel 11 or 12. The test matrix covers PHP 8.4 and 8.5, both Laravel majors, and lowest and stable dependency sets.

After `1.0.0`, package changes follow semantic versioning. Review the changelog before upgrading, run the package checks in the application, and keep the package and Laravel versions within the declared Composer constraints:

```bash
composer update oliverearl/freefind-laravel
composer format
composer analyse
composer test
```

## Independent project and FreeFind service changes

This is an independent third-party package and is not created by, affiliated with, sponsored by, endorsed by, approved by, or maintained by FreeFind.com or its authors, owners, employees, or affiliates. FreeFind controls its service, accounts, endpoints, documentation, and trademarks. The project’s MIT license applies only to project-owned work and grants no rights in FreeFind or other third-party material; see the repository [NOTICE](../NOTICE.md).

The package implements the documented Page Search HTML and XML contracts captured in this repository's dated [upstream documentation snapshot](freefind/README.md). FreeFind controls the hosted endpoints, crawler behavior, account entitlements, response contents, and re-index timing. The package cannot guarantee availability or unchanged behavior for that external service.

The package owns these boundaries:

- validated HTTPS endpoint configuration and bounded XML transport;
- deterministic hosted-form and crawler-markup encoding;
- safe plain-text XML result fields and explicitly untrusted raw highlight fields; and
- typed exceptions for transport, malformed-response, account, and documented service-status failures.

The application remains responsible for its routes, input validation, authorization, rate limiting, error presentation, account setup, Control Center changes, and re-index requests. A FreeFind user-agent is never an authorization signal. DataSearch is not part of `1.0.0`.

The XML client does not silently adapt to undocumented response or request changes. Normal tests use local fixtures and Laravel HTTP fakes, while the optional `live-contract` test is manually enabled for a subscribed account and an operator-supplied, non-sensitive query. Do not enable that group in scheduled or ordinary CI.

## When FreeFind changes a contract

If hosted output or XML responses change:

1. Capture the smallest non-sensitive example and record when and how it was obtained.
2. Compare it with the relevant fixture and the upstream source linked from the [planning capability map](planning/freefind-capability-map.md).
3. Preserve safe defaults: do not expose remote markup as trusted HTML, follow redirects, enable DTDs, or broaden the XML API to web search/DataSearch without an accepted design decision.
4. Report the affected FreeFind account plan, endpoint, package version, Laravel/PHP versions, sanitized request shape, and sanitized response shape. Never include credentials, private indexed content, or raw query data that the report does not need.

Protocol changes that require new public behavior are handled as a package change with fixtures, focused regression tests, documentation, and a roadmap/decision update. Until then, applications should treat a transport or malformed-response exception as an integration failure and choose their own user-facing fallback.

## What support does not include

This package does not provide Control Center automation, local crawling/indexing, package-owned application routes/controllers, scheduled or bulk XML searches, password-protected-content safety, or DataSearch functionality. Those boundaries are intentional and remain part of the `1.0.0` support contract.
