<?php

declare(strict_types=1);

use Freefind\Freefind\Configuration\Account;
use Freefind\Freefind\Search\Xml\Query\AdvancedQuery;
use Freefind\Freefind\Search\Xml\Query\RefinedQuery;
use Freefind\Freefind\Search\Xml\Query\SimpleQuery;
use Freefind\Freefind\Search\Xml\Request\SearchOptions;
use Freefind\Freefind\Search\Xml\Request\XmlSearchRequest;
use Freefind\Freefind\Testing\SentSearch;

it('projects simple, refined, and advanced request intent for assertions', function (): void {
    $account = new Account('0012345');
    $simple = SentSearch::from(new XmlSearchRequest(
        $account,
        new SimpleQuery('simple'),
        new SearchOptions(offset: 5, resultsPerPage: 15, sections: ['manuals']),
    ));
    $refined = SentSearch::from(new XmlSearchRequest(
        $account,
        new RefinedQuery(new SimpleQuery('new'), 'old'),
    ));
    $advanced = SentSearch::from(new XmlSearchRequest(
        $account,
        new AdvancedQuery(allWords: 'all', exactPhrase: 'exact', anyWords: 'any'),
    ));

    expect($simple->query)->toBe('simple')
        ->and($simple->sections)->toBe(['manuals'])
        ->and($simple->offset)->toBe(5)
        ->and($simple->resultsPerPage)->toBe(15)
        ->and($refined->query)->toBe('new')
        ->and($advanced->query)->toBe('all exact any');
});
