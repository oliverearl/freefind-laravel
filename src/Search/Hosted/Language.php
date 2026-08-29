<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class Language
{
    /**
     * @var list<string>
     */
    private const array SUPPORTED = [
        'bg', 'ca', 'cs', 'da', 'de', 'el', 'en', 'es', 'fr', 'hu', 'it', 'nl', 'no', 'pl', 'pt', 'ro', 'ro2', 'ru', 'sv', 'tr', 'uk', 'zh', 'zh2', 'zh3',
    ];

    public function __construct(public string $code)
    {
        if (! preg_match('/^[a-z][a-z0-9_-]{1,7}$/', $this->code)) {
            throw new InvalidMarkup('FreeFind language codes must be short lowercase identifiers.');
        }
    }

    public static function fromCode(string $code): self
    {
        if (! in_array($code, self::SUPPORTED, true)) {
            throw new InvalidMarkup("The FreeFind language [{$code}] is not documented as supported.");
        }

        return new self($code);
    }

    public static function custom(string $code): self
    {
        return new self($code);
    }
}
