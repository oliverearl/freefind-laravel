<?php

declare(strict_types=1);

namespace Freefind\Freefind\Configuration;

use Freefind\Freefind\Exceptions\InvalidConfigurationException;

/**
 * Bounds applied to outbound FreeFind XML requests and response bodies.
 */
final readonly class HttpSettings
{
    /**
     * Default connection timeout in seconds.
     */
    public const int DEFAULT_CONNECT_TIMEOUT = 2;

    /**
     * Default total request timeout in seconds.
     */
    public const int DEFAULT_TIMEOUT = 5;

    /**
     * Default maximum XML response size in bytes.
     */
    public const int DEFAULT_MAX_RESPONSE_BYTES = 2_000_000;

    /**
     * Creates transport settings with positive, internally consistent limits.
     *
     * @throws InvalidConfigurationException When a timeout or response-size limit is invalid.
     */
    public function __construct(
        public int $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT,
        public int $timeout = self::DEFAULT_TIMEOUT,
        public int $maxResponseBytes = self::DEFAULT_MAX_RESPONSE_BYTES,
    ) {
        if ($this->connectTimeout < 1) {
            throw new InvalidConfigurationException('The FreeFind HTTP connect timeout must be at least one second.');
        }

        if ($this->timeout < $this->connectTimeout) {
            throw new InvalidConfigurationException('The FreeFind HTTP timeout must be greater than or equal to the connect timeout.');
        }

        if ($this->maxResponseBytes < 1) {
            throw new InvalidConfigurationException('The FreeFind maximum response size must be greater than zero.');
        }
    }

    /**
     * Builds transport settings from the package configuration array.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException When a configured value is not an integer or violates the limits.
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            connectTimeout: self::integer($config, 'connect_timeout', self::DEFAULT_CONNECT_TIMEOUT),
            timeout: self::integer($config, 'timeout', self::DEFAULT_TIMEOUT),
            maxResponseBytes: self::integer($config, 'max_response_bytes', self::DEFAULT_MAX_RESPONSE_BYTES),
        );
    }

    /**
     * Reads one integer setting while preserving the configured default when it is absent.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException When the configured value is not an integer.
     */
    private static function integer(array $config, string $key, int $default): int
    {
        $value = $config[$key] ?? $default;

        if (! is_int($value)) {
            throw new InvalidConfigurationException("The freefind-laravel.http.{$key} value must be an integer.");
        }

        return $value;
    }
}
