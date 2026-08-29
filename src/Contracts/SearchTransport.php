<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

/**
 * Transports one typed XML search request and returns its raw response data.
 */
interface SearchTransport
{
    /**
     * Sends a user-initiated search without parsing its XML body.
     *
     * @throws \Freefind\Freefind\Exceptions\SearchTransportException When transport fails or violates response bounds.
     */
    public function send(XmlSearchRequest $request): XmlTransportResponse;
}
