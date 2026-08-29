<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

enum Stemming: string
{
    case Auto = '';
    case None = 'n';
    case English = 'en';
    case Portuguese = 'pt';
}
