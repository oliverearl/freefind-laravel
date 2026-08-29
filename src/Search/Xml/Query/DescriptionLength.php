<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

enum DescriptionLength: string
{
    case Short = 's';
    case Medium = 'm';
    case Long = 'l';
}
