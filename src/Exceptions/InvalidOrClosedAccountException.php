<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use RuntimeException;

/**
 * Indicates that FreeFind rejected the configured account as invalid or closed.
 */
final class InvalidOrClosedAccountException extends RuntimeException {}
