<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

/**
 * Stemming modes supported by FreeFind XML search.
 */
enum Stemming: string
{
    case Auto = '';
    case None = 'n';
    case English = 'en';
    case Portuguese = 'pt';
}
