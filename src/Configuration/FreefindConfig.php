<?php

declare(strict_types=1);

namespace Freefind\Freefind\Configuration;

use Freefind\Freefind\Exceptions\InvalidConfigurationException;

final readonly class FreefindConfig
{
    public function __construct(
        public Account $account,
        public HttpSettings $http,
        public SpiderSettings $spider,
    ) {}

    /**
     * @param  array<string, mixed>  $config
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
