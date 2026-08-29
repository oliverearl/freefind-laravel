<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml;

use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

final class FreefindXmlClient
{
    public function __construct(
        private readonly SearchTransport $transport,
        private readonly XmlResponseParser $parser,
    ) {}

    public function execute(XmlSearchRequest $request): SearchResults
    {
        return $this->parser->parse($this->transport->send($request), $request);
    }
}
