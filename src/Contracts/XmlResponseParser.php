<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

/**
 * Converts a bounded FreeFind XML response into typed result data.
 */
interface XmlResponseParser
{
    /**
     * Parses the response in the context of the request that produced it.
     *
     * @throws \Freefind\Freefind\Exceptions\SearchTransportException When the HTTP response or body bound is invalid.
     * @throws \Freefind\Freefind\Exceptions\MalformedXmlResponseException When the XML structure or data is unsafe.
     * @throws \Freefind\Freefind\Exceptions\FreefindServiceException When FreeFind reports a service error.
     * @throws \Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException When the account is not authorized for XML search.
     * @throws \Freefind\Freefind\Exceptions\InvalidOrClosedAccountException When the account is invalid or closed.
     * @throws \Freefind\Freefind\Exceptions\RejectedSearchParametersException When FreeFind rejects the request parameters.
     */
    public function parse(XmlTransportResponse $response, XmlSearchRequest $request): SearchResults;
}
