<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use RuntimeException;

/**
 * Indicates an HTTP, response-size, or other XML transport failure.
 */
final class SearchTransportException extends RuntimeException {}
