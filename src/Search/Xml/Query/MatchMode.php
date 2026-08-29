<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

/**
 * Whether a simple query requires all terms or matches any term.
 */
enum MatchMode: string
{
    case All = 'all';
    case Any = 'any';
}
