<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

use DateTimeInterface;
use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Exceptions\FreefindServiceException;
use Freefind\Freefind\Exceptions\InvalidOrClosedAccountException;
use Freefind\Freefind\Exceptions\MalformedXmlResponseException;
use Freefind\Freefind\Exceptions\RejectedSearchParametersException;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException;
use Freefind\Freefind\Markup\AbsoluteUrl;
use Freefind\Freefind\Markup\BrowsingContextName;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

/**
 * Securely parses bounded FreeFind XML responses into typed result models.
 */
final class FreefindXmlResponseParser implements XmlResponseParser
{
    /**
     * Maximum number of XML elements accepted in one response tree.
     */
    private const int MAX_ELEMENTS = 10000;

    /**
     * Creates a parser with the configured response-size limit.
     */
    public function __construct(private readonly HttpSettings $settings) {}

    /**
     * Parses a successful XML response and maps service statuses to package exceptions.
     *
     * @throws SearchTransportException When the HTTP status or response size is invalid.
     * @throws MalformedXmlResponseException When the XML structure or a result field is invalid.
     * @throws FreefindServiceException When FreeFind returns an unknown or general service status.
     * @throws UnauthorizedXmlFeedException When the account is not authorized for XML search.
     * @throws InvalidOrClosedAccountException When the account is invalid or closed.
     * @throws RejectedSearchParametersException When FreeFind rejects the request parameters.
     */
    public function parse(XmlTransportResponse $response, XmlSearchRequest $request): SearchResults
    {
        if ($response->status < 200 || $response->status > 299) {
            throw new SearchTransportException("The FreeFind XML endpoint returned HTTP status {$response->status}.");
        }

        if (strlen($response->body) > $this->settings->maxResponseBytes) {
            throw new SearchTransportException('The FreeFind XML response exceeded the configured size limit.');
        }

        $xml = $this->loadXml($response->body);
        $this->assertElementCount($xml);

        if ($xml->getName() !== 'ret') {
            throw new MalformedXmlResponseException('The FreeFind XML response did not contain a ret root element.');
        }

        $statusCode = $this->integer($xml, 'sts', required: true);
        $status = FreefindStatus::tryFrom($statusCode);

        if ($status === null) {
            throw new FreefindServiceException('FreeFind returned an unknown service status.');
        }

        if ($status !== FreefindStatus::Success) {
            $this->throwForStatus($status);
        }

        $search = $xml->srch[0] ?? null;
        $total = $this->integer($search, 'nttl') ?? 0;
        $returned = $this->integer($search, 'nret') ?? 0;
        $offset = $this->integer($search, 'idx') ?? $request->options->offset;
        $query = $this->text($search, 'q') ?? '';
        $sections = $this->sections($search);
        $items = $this->items($search);
        $spelling = $this->spelling($search);
        $automaticAny = $this->boolean($search, 'aor');

        return new SearchResults(
            status: $status,
            query: $query,
            total: $total,
            returned: $returned,
            offset: $offset,
            sections: $sections,
            spelling: $spelling,
            usedAutomaticAnyMode: $automaticAny,
            items: $items,
            window: new SearchWindow($request->options->offset, $request->options->resultsPerPage, $total),
        );
    }

    /**
     * Loads XML without allowing external entities or network access.
     *
     * @throws MalformedXmlResponseException When the response is not well-formed XML.
     */
    private function loadXml(string $body): SimpleXMLElement
    {
        $previous = libxml_use_internal_errors(true);

        try {
            $xml = simplexml_load_string($body, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOBLANKS);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if ($xml === false) {
            throw new MalformedXmlResponseException('The FreeFind XML response could not be parsed.');
        }

        return $xml;
    }

    /**
     * Counts descendants and rejects unexpectedly large element trees.
     *
     * @throws MalformedXmlResponseException When the element tree exceeds the safety limit.
     */
    private function assertElementCount(SimpleXMLElement $element): int
    {
        $count = 1;

        foreach ($element->children() as $child) {
            $count += $this->assertElementCount($child);

            if ($count > self::MAX_ELEMENTS) {
                throw new MalformedXmlResponseException('The FreeFind XML response contained too many elements.');
            }
        }

        return $count;
    }

    /**
     * Converts a non-success FreeFind status into its public exception type.
     *
     * @throws FreefindServiceException For general or unexpected service errors.
     * @throws UnauthorizedXmlFeedException For an unauthorized XML feed.
     * @throws InvalidOrClosedAccountException For an invalid or closed account.
     * @throws RejectedSearchParametersException For rejected search parameters.
     */
    private function throwForStatus(FreefindStatus $status): never
    {
        throw match ($status) {
            FreefindStatus::Error => new FreefindServiceException('FreeFind returned a service error.'),
            FreefindStatus::Unauthorized => new UnauthorizedXmlFeedException('The FreeFind XML feed is not authorized for this account.'),
            FreefindStatus::InvalidAccount => new InvalidOrClosedAccountException('The FreeFind account is invalid or closed.'),
            FreefindStatus::InvalidParameters => new RejectedSearchParametersException('FreeFind rejected the XML search parameters.'),
            FreefindStatus::Success => new FreefindServiceException('FreeFind returned an unexpected success status.'),
        };
    }

    /**
     * Reads the response's returned section identifiers.
     *
     * @return list<string>
     */
    private function sections(?SimpleXMLElement $search): array
    {
        if ($search === null || ! isset($search->ss[0])) {
            return [];
        }

        $sections = [];

        foreach ($search->ss[0]->s as $section) {
            $sections[] = trim((string) $section);
        }

        return $sections;
    }

    /**
     * Parses all result items in the response.
     *
     * @return list<SearchResult>
     *
     * @throws MalformedXmlResponseException When a result contains an invalid URL, target, or field.
     */
    private function items(?SimpleXMLElement $search): array
    {
        if ($search === null || ! isset($search->items[0])) {
            return [];
        }

        $items = [];

        foreach ($search->items[0]->i as $item) {
            $items[] = $this->item($item);
        }

        return $items;
    }

    /**
     * Converts one XML result item into its safe and raw representations.
     *
     * @throws MalformedXmlResponseException When the result URL, target, or scalar data is invalid.
     */
    private function item(SimpleXMLElement $item): SearchResult
    {
        $url = $this->text($item, 'u');

        if ($url === null || trim($url) === '') {
            throw new MalformedXmlResponseException('A FreeFind result did not contain a click URL.');
        }

        try {
            $safeUrl = AbsoluteUrl::from(trim($url));
        } catch (Throwable $exception) {
            throw new MalformedXmlResponseException('A FreeFind result contained an unsafe click URL.', previous: $exception);
        }

        $target = $this->text($item, 'tg');
        $target = $target === null || trim($target) === '' ? null : trim($target);

        if ($target !== null && ! BrowsingContextName::isValid($target)) {
            throw new MalformedXmlResponseException('A FreeFind result contained an unsafe link target.');
        }

        return new SearchResult(
            number: $this->integer($item, 'n'),
            title: $this->plain($item, 't') ?? '',
            description: $this->plain($item, 'd') ?? '',
            url: $safeUrl,
            target: $target,
            displayUrl: $this->plain($item, 'du') ?? trim($url),
            date: $this->date($this->text($item, 'dt')),
            raw: new RawResultFields(
                title: $this->raw($item, 't'),
                description: $this->raw($item, 'd'),
                displayUrl: $this->raw($item, 'du'),
            ),
        );
    }

    /**
     * Reads an optional spelling suggestion from the search element.
     *
     * @throws MalformedXmlResponseException When the suggestion is present but invalid.
     */
    private function spelling(?SimpleXMLElement $search): ?SpellingSuggestion
    {
        $query = $this->text($search, 'spell');

        if ($query === null || trim($query) === '') {
            return null;
        }

        return new SpellingSuggestion($query, $this->text($search, 'spelle'));
    }

    /**
     * Reads a `0`/`1` XML boolean, treating an absent field as false.
     *
     * @throws MalformedXmlResponseException When the field contains another value.
     */
    private function boolean(?SimpleXMLElement $parent, string $name): bool
    {
        $value = $this->text($parent, $name);

        if ($value === null || trim($value) === '') {
            return false;
        }

        if (! in_array(trim($value), ['0', '1'], true)) {
            throw new MalformedXmlResponseException("The FreeFind XML field [{$name}] was not boolean.");
        }

        return trim($value) === '1';
    }

    /**
     * Reads a non-negative XML integer, optionally requiring the field to exist.
     *
     * @throws MalformedXmlResponseException When a required field is absent or a value is not a non-negative integer.
     */
    private function integer(?SimpleXMLElement $parent, string $name, bool $required = false): ?int
    {
        $value = $this->text($parent, $name);

        if ($value === null || trim($value) === '') {
            if ($required) {
                throw new MalformedXmlResponseException("The FreeFind XML field [{$name}] was missing.");
            }

            return null;
        }

        $value = trim($value);

        if (! preg_match('/^(?:0|[1-9][0-9]*)$/', $value) || filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new MalformedXmlResponseException("The FreeFind XML field [{$name}] was not a non-negative integer.");
        }

        return (int) $value;
    }

    /**
     * Reads the text content of an optional child element.
     */
    private function text(?SimpleXMLElement $parent, string $name): ?string
    {
        if ($parent === null || ! isset($parent->{$name}[0])) {
            return null;
        }

        return (string) $parent->{$name}[0];
    }

    /**
     * Reads an XML field and removes remote markup for safe plain-text display.
     */
    private function plain(?SimpleXMLElement $parent, string $name): ?string
    {
        $value = $this->raw($parent, $name);

        return $value === null ? null : strip_tags($value);
    }

    /**
     * Reads an XML field while preserving its remote highlight markup as untrusted text.
     */
    private function raw(?SimpleXMLElement $parent, string $name): ?string
    {
        if ($parent === null || ! isset($parent->{$name}[0])) {
            return null;
        }

        $serialized = $parent->{$name}[0]->asXML();

        if ($serialized === false) {
            return null;
        }

        $openingEnd = strpos($serialized, '>');
        $closingStart = strrpos($serialized, '</');

        if ($openingEnd === false || $closingStart === false || $closingStart <= $openingEnd) {
            return null;
        }

        $value = substr($serialized, $openingEnd + 1, $closingStart - $openingEnd - 1);

        if (Str::startsWith($value, '<![CDATA[') && Str::endsWith($value, ']]>')) {
            $value = substr($value, 9, -3);
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Parses an optional result date through Laravel's configured date factory.
     */
    private function date(?string $value): ?DateTimeInterface
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        try {
            return Date::parse($value);
        } catch (Throwable) {
            return null;
        }
    }
}
