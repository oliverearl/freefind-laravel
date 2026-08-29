<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

enum MatchMode: string
{
    case All = 'all';
    case Any = 'any';
}
