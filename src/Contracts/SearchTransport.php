<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

interface SearchTransport
{
    public function send(XmlSearchRequest $request): XmlTransportResponse;
}
