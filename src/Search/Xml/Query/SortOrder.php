<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

enum SortOrder: string
{
    case Relevance = 'r';
    case Date = 'd';
}
