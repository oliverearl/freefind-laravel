<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use InvalidArgumentException;

/**
 * Indicates invalid or unsafe crawler markup or component input.
 */
final class InvalidMarkupException extends InvalidArgumentException {}
