<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

interface XmlResponseParser
{
    public function parse(XmlTransportResponse $response, XmlSearchRequest $request): SearchResults;
}
