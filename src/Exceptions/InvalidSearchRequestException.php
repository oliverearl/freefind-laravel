<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use InvalidArgumentException;

/**
 * Indicates invalid input for a FreeFind search request or test fixture.
 */
final class InvalidSearchRequestException extends InvalidArgumentException {}
