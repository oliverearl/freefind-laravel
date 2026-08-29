<?php

declare(strict_types=1);

namespace Freefind\Freefind\Exceptions;

use RuntimeException;

/**
 * Indicates malformed, unsafe, or structurally unexpected FreeFind XML.
 */
final class MalformedXmlResponseException extends RuntimeException {}
