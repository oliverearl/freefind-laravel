<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use RuntimeException;

/**
 * Indicates that the configured FreeFind account is not authorized for XML search.
 */
final class UnauthorizedXmlFeedException extends RuntimeException {}
