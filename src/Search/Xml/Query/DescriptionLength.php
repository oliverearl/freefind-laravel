<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Query;

/**
 * FreeFind's requested result-description length.
 */
enum DescriptionLength: string
{
    case Short = 's';
    case Medium = 'm';
    case Long = 'l';
}
