<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

interface SearchClient
{
    public function execute(XmlSearchRequest $request): SearchResults;
}
