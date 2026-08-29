# Contributing

Thank you for considering a contribution to FreeFind Laravel. The repository is currently pre-release: no Packagist package or supported release exists, and the first intended release is `1.0.0`.

## Before you contribute

- Read [NOTICE.md](NOTICE.md). This independent project is not affiliated with FreeFind.com, and contributions must not imply that it is.
- Keep changes within the agreed Page Search scope. DataSearch, Control Center automation, package-owned routes/controllers, and automated or bulk XML searches are intentionally out of scope.
- Never include credentials, site IDs that are not already public, private indexed content, production queries, XML result excerpts, or copies of upstream FreeFind documentation, images, logos, or other third-party material unless the maintainer has documented permission to include it.
- Discuss material API or scope changes in an issue before investing in a large pull request. Report vulnerabilities privately under [SECURITY.md](SECURITY.md), not in a public issue.

## Development workflow

1. Start from the current `main` branch and describe the user-visible problem in the pull request.
2. Keep each pull request focused. Include production code, focused tests, user documentation, and planning updates together when the public behaviour changes.
3. Preserve the module boundaries and security rules in [AGENTS.md](AGENTS.md) and the planning pack. The upstream snapshot under `docs/freefind/**` is read-only.
4. Add a regression test for every bug fix. XML and crawler-markup contracts need exact fixtures or exact-string assertions; security boundaries need hostile-input coverage.
5. Run the complete local quality gate before requesting review:

   ```bash
   composer format
   composer analyse
   composer test
   ```

Normal tests must remain network-free. The `live-contract` group is manually enabled only with a subscribed account, an operator-supplied query, and explicit environment configuration; never add it to normal or scheduled CI.

## Pull request checklist

- [ ] The change is in scope and does not claim an official FreeFind relationship.
- [ ] Tests cover valid, invalid, and security-relevant behaviour.
- [ ] Public documentation and planning status are accurate.
- [ ] No secrets, personal data, private search content, or unlicensed third-party material are included.
- [ ] `composer format`, `composer analyse`, and `composer test` pass.

## Contributor licence

By submitting a contribution, you confirm that you have the right to submit it and license it under the repository’s MIT licence. You retain your copyright in your contribution. Do not submit material that you cannot license on those terms.

Participation is also governed by the [Code of Conduct](CODE_OF_CONDUCT.md).
