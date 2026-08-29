<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * A supported or explicitly custom FreeFind hosted-search language code.
 */
final readonly class Language
{
    /**
     * Language codes documented by FreeFind's hosted search.
     *
     * @var list<string>
     */
    private const array SUPPORTED = [
        'bg', 'ca', 'cs', 'da', 'de', 'el', 'en', 'es', 'fr', 'hu', 'it', 'nl', 'no', 'pl', 'pt', 'ro', 'ro2', 'ru', 'sv', 'tr', 'uk', 'zh', 'zh2', 'zh3',
    ];

    /**
     * Creates a syntactically valid language code.
     *
     * @throws InvalidMarkupException When the code is not a short lowercase identifier.
     */
    public function __construct(public string $code)
    {
        if (! preg_match('/^[a-z][a-z0-9_-]{1,7}$/', $this->code)) {
            throw new InvalidMarkupException('FreeFind language codes must be short lowercase identifiers.');
        }
    }

    /**
     * Creates a language value only when FreeFind documents the code as supported.
     *
     * @throws InvalidMarkupException When the code is not in the documented language list.
     */
    public static function fromCode(string $code): self
    {
        if (! in_array($code, self::SUPPORTED, true)) {
            throw new InvalidMarkupException("The FreeFind language [{$code}] is not documented as supported.");
        }

        return new self($code);
    }

    /**
     * Creates a language value for a caller-managed code that passes syntax validation.
     *
     * @throws InvalidMarkupException When the code is not a short lowercase identifier.
     */
    public static function custom(string $code): self
    {
        return new self($code);
    }
}
