<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Xml\Response;

enum FreefindStatus: int
{
    case Success = 0;
    case Error = 1;
    case Unauthorized = 2;
    case InvalidAccount = 3;
    case InvalidParameters = 4;
}
