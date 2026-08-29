<?php

declare(strict_types=1);

namespace Freefind\Freefind\Contracts;

use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

/**
 * Executes typed FreeFind XML Page Search requests.
 */
interface SearchClient
{
    /**
     * Executes one user-initiated search and returns normalized results.
     *
     * @throws \Freefind\Freefind\Exceptions\SearchTransportException When the request cannot be completed.
     * @throws \Freefind\Freefind\Exceptions\MalformedXmlResponseException When the response is malformed or unsafe.
     * @throws \Freefind\Freefind\Exceptions\FreefindServiceException When FreeFind reports a service error.
     * @throws \Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException When the account is not authorized for XML search.
     * @throws \Freefind\Freefind\Exceptions\InvalidOrClosedAccountException When the account is invalid or closed.
     * @throws \Freefind\Freefind\Exceptions\RejectedSearchParametersException When FreeFind rejects the request parameters.
     */
    public function execute(XmlSearchRequest $request): SearchResults;
}
