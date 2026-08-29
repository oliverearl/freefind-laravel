<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml;

use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Contracts\SearchClient;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\SearchResults;

/**
 * Coordinates one XML transport call with the secure FreeFind response parser.
 */
final class FreefindXmlClient implements SearchClient
{
    /**
     * Creates a client from a transport and the corresponding response parser.
     */
    public function __construct(
        private readonly SearchTransport $transport,
        private readonly XmlResponseParser $parser,
    ) {}

    /**
     * Sends and parses one user-initiated XML Page Search request.
     *
     * @throws SearchTransportException When the request cannot be completed.
     * @throws \Freefind\Freefind\Exceptions\MalformedXmlResponseException When the response cannot be parsed safely.
     * @throws \Freefind\Freefind\Exceptions\FreefindServiceException When FreeFind reports a service error.
     * @throws \Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException When the account is not authorized for XML search.
     * @throws \Freefind\Freefind\Exceptions\InvalidOrClosedAccountException When the account is invalid or closed.
     * @throws \Freefind\Freefind\Exceptions\RejectedSearchParametersException When FreeFind rejects the request parameters.
     */
    public function execute(XmlSearchRequest $request): SearchResults
    {
        return $this->parser->parse($this->transport->send($request), $request);
    }
}
