<?php

declare(strict_types=1);

namespace Freefind\Freefind\Configuration;

use Freefind\Freefind\Exceptions\InvalidConfiguration;

final readonly class SpiderSettings
{
    /**
     * @param  list<string>  $userAgents
     */
    public function __construct(
        public bool $middleware = false,
        public array $userAgents = ['freefind/2.1'],
        public string $cacheControl = 'public, max-age=3600',
    ) {
        if ($this->userAgents === []) {
            throw new InvalidConfiguration('At least one FreeFind spider user-agent signature must be configured.');
        }

        foreach ($this->userAgents as $userAgent) {
            if ($userAgent === '' || trim($userAgent) !== $userAgent) {
                throw new InvalidConfiguration('FreeFind spider user-agent signatures must be non-empty strings without surrounding whitespace.');
            }
        }

        if ($this->cacheControl === '') {
            throw new InvalidConfiguration('The FreeFind spider cache-control value must not be empty.');
        }
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromConfig(array $config): self
    {
        $middleware = $config['middleware'] ?? false;
        $userAgents = $config['user_agents'] ?? ['freefind/2.1'];
        $cacheControl = $config['cache_control'] ?? 'public, max-age=3600';

        if (! is_bool($middleware)) {
            throw new InvalidConfiguration('The freefind-laravel.spider.middleware value must be a boolean.');
        }

        if (! is_array($userAgents) || array_is_list($userAgents) === false || ! array_all($userAgents, fn(mixed $userAgent): bool => is_string($userAgent))) {
            throw new InvalidConfiguration('The freefind-laravel.spider.user_agents value must be a list of strings.');
        }

        if (! is_string($cacheControl)) {
            throw new InvalidConfiguration('The freefind-laravel.spider.cache_control value must be a string.');
        }

        /** @var list<string> $userAgents */
        return new self($middleware, $userAgents, $cacheControl);
    }
}
