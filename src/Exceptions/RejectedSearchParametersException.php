<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use RuntimeException;

/**
 * Indicates that FreeFind rejected the supplied XML search parameters.
 */
final class RejectedSearchParametersException extends RuntimeException {}
