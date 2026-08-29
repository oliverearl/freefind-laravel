<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Exceptions\FreefindServiceException;
use Freefind\Freefind\Exceptions\InvalidOrClosedAccountException;
use Freefind\Freefind\Exceptions\MalformedXmlResponseException;
use Freefind\Freefind\Exceptions\RejectedSearchParametersException;
use Freefind\Freefind\Exceptions\SearchTransportException;
use Freefind\Freefind\Exceptions\UnauthorizedXmlFeedException;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Search\Xml\Response\FreefindStatus;
use Freefind\Freefind\Search\Xml\Response\FreefindXmlResponseParser;
use Freefind\Freefind\Search\Xml\Transport\XmlTransportResponse;

beforeEach(function (): void {
    $this->request = new XmlSearchRequest(new Account('0012345'), new SimpleQuery('blade directive'));
    $this->parser = new FreefindXmlResponseParser(new HttpSettings(maxResponseBytes: 5000));
});

it('parses safe text separately from explicitly raw highlight fields', function (): void {
    $results = $this->parser->parse(
        new XmlTransportResponse(200, file_get_contents(__DIR__ . '/../../Fixtures/xml/success.xml')),
        $this->request,
    );

    expect($results->status)->toBe(FreefindStatus::Success)
        ->and($results->query)->toBe('blade directive')
        ->and($results->total)->toBe(12)
        ->and($results->returned)->toBe(2)
        ->and($results->sections)->toBe(['manuals', 'releases'])
        ->and($results->usedAutomaticAnyMode)->toBeTrue()
        ->and($results->items[0]->title)->toBe('How to use Blade')
        ->and($results->items[0]->raw->title)->toBe('How to use <b>Blade</b>')
        ->and($results->items[0]->description)->toBe('Use Blade directives in your templates.')
        ->and($results->items[0]->raw->description)->toContain('<b>Blade</b>')
        ->and($results->items[0]->target)->toBe('_blank')
        ->and($results->items[0]->date)->toBeInstanceOf(DateTimeInterface::class)
        ->and($results->items[1]->target)->toBeNull()
        ->and($results->window->hasNext())->toBeTrue()
        ->and($results->window->nextOffset())->toBe(10);
});

it('accepts empty successful result containers and builds local pagination', function (): void {
    $request = new XmlSearchRequest(
        new Account('0012345'),
        new SimpleQuery('missing'),
        new \Freefind\Freefind\Search\Xml\Request\SearchOptions(offset: 10, resultsPerPage: 10),
    );
    $xml = '<ret><sts>0</sts><srch><nttl>10</nttl><nret>0</nret><idx>10</idx><q>missing</q><items/></srch></ret>';

    $results = $this->parser->parse(new XmlTransportResponse(200, $xml), $request);

    expect($results->items)->toBe([])
        ->and($results->window->hasPrevious())->toBeTrue()
        ->and($results->window->previousOffset())->toBe(0)
        ->and($results->window->hasNext())->toBeFalse();
});

it('maps documented service statuses without exposing remote messages', function (int $code, string $exception): void {
    expect(fn(): mixed => $this->parser->parse(
        new XmlTransportResponse(200, "<ret><sts>{$code}</sts><msg>private query text</msg></ret>"),
        $this->request,
    ))->toThrow($exception);
})->with([
    [1, FreefindServiceException::class],
    [2, UnauthorizedXmlFeedException::class],
    [3, InvalidOrClosedAccountException::class],
    [4, RejectedSearchParametersException::class],
    [9, FreefindServiceException::class],
]);

it('rejects malformed XML, unsafe result links, oversized bodies, and non-success HTTP responses', function (): void {
    expect(fn(): mixed => $this->parser->parse(new XmlTransportResponse(200, '<ret>'), $this->request))
        ->toThrow(MalformedXmlResponseException::class)
        ->and(fn(): mixed => $this->parser->parse(
            new XmlTransportResponse(200, '<ret><sts>0</sts><srch><items><i><u>javascript:alert(1)</u></i></items></srch></ret>'),
            $this->request,
        ))->toThrow(MalformedXmlResponseException::class)
        ->and(fn(): mixed => $this->parser->parse(
            new XmlTransportResponse(200, str_repeat('x', 5001)),
            $this->request,
        ))->toThrow(SearchTransportException::class)
        ->and(fn(): mixed => $this->parser->parse(new XmlTransportResponse(503, ''), $this->request))
        ->toThrow(SearchTransportException::class);
});

it('does not expand external XML entities', function (): void {
    $xml = <<<'XML'
        <!DOCTYPE ret [<!ENTITY secret SYSTEM "file:///etc/passwd">]>
        <ret><sts>0</sts><srch><q>&secret;</q></srch></ret>
        XML;

    $results = $this->parser->parse(new XmlTransportResponse(200, $xml), $this->request);

    expect($results->query)->not->toContain('root:');
});

it('is registered behind the response-parser contract', function (): void {
    expect(resolve(XmlResponseParser::class))->toBeInstanceOf(FreefindXmlResponseParser::class);
});
