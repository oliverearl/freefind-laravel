<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

/**
 * Ordering options supported by FreeFind XML results.
 */
enum SortOrder: string
{
    case Relevance = 'r';
    case Date = 'd';
}
