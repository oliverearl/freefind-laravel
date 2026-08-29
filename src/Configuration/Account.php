<?php

declare(strict_types=1);

namespace Freefind\Freefind\Configuration;

use Freefind\Freefind\Exceptions\InvalidConfigurationException;

/**
 * Validated endpoints and the public site identifier for one FreeFind account.
 */
final readonly class Account
{
    /**
     * Default HTTPS endpoint for FreeFind's hosted HTML search.
     */
    public const string DEFAULT_HTML_ENDPOINT = 'https://search.freefind.com/find.html';

    /**
     * Default HTTPS endpoint for FreeFind's XML search.
     */
    public const string DEFAULT_XML_ENDPOINT = 'https://search.freefind.com/find.xml';

    /**
     * Default HTTPS endpoint for FreeFind's hosted site index.
     */
    public const string DEFAULT_INDEX_ENDPOINT = 'https://search.freefind.com/siteindex.html';

    /**
     * Creates an account and validates every configured endpoint.
     *
     * @throws InvalidConfigurationException When the site ID or an endpoint is invalid.
     */
    public function __construct(
        public string $siteId,
        public string $htmlEndpoint = self::DEFAULT_HTML_ENDPOINT,
        public string $xmlEndpoint = self::DEFAULT_XML_ENDPOINT,
        public string $indexEndpoint = self::DEFAULT_INDEX_ENDPOINT,
    ) {
        self::validateSiteId($this->siteId);
        self::validateEndpoint($this->htmlEndpoint, 'html');
        self::validateEndpoint($this->xmlEndpoint, 'xml');
        self::validateEndpoint($this->indexEndpoint, 'index');
    }

    /**
     * Builds an account from the package configuration array.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException When a site ID, endpoint map, or endpoint value is invalid.
     */
    public static function fromConfig(array $config): self
    {
        $siteId = $config['site_id'] ?? null;

        if (! is_string($siteId)) {
            throw new InvalidConfigurationException('The freefind-laravel.site_id value must be a non-empty string.');
        }

        $endpoints = $config['endpoints'] ?? [];

        if (! is_array($endpoints)) {
            throw new InvalidConfigurationException('The freefind-laravel.endpoints value must be an array.');
        }

        return new self(
            siteId: $siteId,
            htmlEndpoint: self::endpoint($endpoints, 'html', self::DEFAULT_HTML_ENDPOINT),
            xmlEndpoint: self::endpoint($endpoints, 'xml', self::DEFAULT_XML_ENDPOINT),
            indexEndpoint: self::endpoint($endpoints, 'index', self::DEFAULT_INDEX_ENDPOINT),
        );
    }

    /**
     * Validates the configured public site identifier.
     *
     * @throws InvalidConfigurationException When the site ID is empty or contains unsafe characters.
     */
    private static function validateSiteId(string $siteId): void
    {
        if ($siteId === '' || trim($siteId) !== $siteId || preg_match('/[\x00-\x1F\x7F]/', $siteId) === 1) {
            throw new InvalidConfigurationException('The freefind-laravel.site_id value must be a non-empty string without surrounding whitespace or control characters.');
        }
    }

    /**
     * Validates one configured account endpoint.
     *
     * @throws InvalidConfigurationException When the endpoint is not a credential-free HTTPS URL.
     */
    private static function validateEndpoint(string $endpoint, string $name): void
    {
        $parts = parse_url($endpoint);

        if (
            $parts === false
            || ($parts['scheme'] ?? null) !== 'https'
            || ! is_string($parts['host'] ?? null)
            || ($parts['user'] ?? null) !== null
            || ($parts['pass'] ?? null) !== null
            || ($parts['query'] ?? null) !== null
            || ($parts['fragment'] ?? null) !== null
        ) {
            throw new InvalidConfigurationException("The freefind-laravel.endpoints.{$name} value must be an HTTPS URL without credentials, query parameters, or fragments.");
        }
    }

    /**
     * Reads one endpoint from the configuration, falling back to its documented default.
     *
     * @param  array<string, mixed>  $endpoints
     *
     * @throws InvalidConfigurationException When the configured endpoint is not a string.
     */
    private static function endpoint(array $endpoints, string $name, string $default): string
    {
        $endpoint = $endpoints[$name] ?? $default;

        if (! is_string($endpoint)) {
            throw new InvalidConfigurationException("The freefind-laravel.endpoints.{$name} value must be a string.");
        }

        return $endpoint;
    }
}
