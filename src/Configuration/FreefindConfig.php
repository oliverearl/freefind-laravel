<?php

declare(strict_types=1);

namespace Freefind\Freefind\Configuration;

use Freefind\Freefind\Exceptions\InvalidConfigurationException;

/**
 * Immutable, validated configuration for the package's Page Search integration.
 */
final readonly class FreefindConfig
{
    /**
     * Creates an immutable package configuration from validated sections.
     */
    public function __construct(
        public Account $account,
        public HttpSettings $http,
        public SpiderSettings $spider,
    ) {}

    /**
     * Builds package configuration and validates each nested configuration section.
     *
     * @param array<string, mixed> $config
     *
     * @throws InvalidConfigurationException When a nested configuration section is not an array or contains an invalid value.
     */
    public static function fromConfig(array $config): self
    {
        $http = $config['http'] ?? [];
        $spider = $config['spider'] ?? [];

        if (! is_array($http)) {
            throw new InvalidConfigurationException('The freefind-laravel.http value must be an array.');
        }

        if (! is_array($spider)) {
            throw new InvalidConfigurationException('The freefind-laravel.spider value must be an array.');
        }

        return new self(
            Account::fromConfig($config),
            HttpSettings::fromConfig($http),
            SpiderSettings::fromConfig($spider),
        );
    }
}
