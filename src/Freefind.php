<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Spider\SpiderContext;

final class Freefind
{
    public function __construct(
        private readonly FreefindConfig $config,
        private readonly SpiderContext $spiderContext,
        private readonly HostedSearch $hostedSearch,
    ) {}

    public function account(): Account
    {
        return $this->config->account;
    }

    public function siteId(): string
    {
        return $this->config->account->siteId;
    }

    public function isSpiderRequest(): bool
    {
        return $this->spiderContext->isSpider();
    }

    public function hostedSearch(): HostedSearch
    {
        return $this->hostedSearch;
    }
}
