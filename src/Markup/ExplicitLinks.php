<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Ordered absolute URLs explicitly supplied to FreeFind's link-discovery marker.
 */
final readonly class ExplicitLinks
{
    /**
     * @var list<AbsoluteUrl>
     */
    public array $urls;

    /**
     * Creates a validated, ordered collection of absolute URLs.
     *
     * @param array<array-key, mixed> $urls  Values are validated as an ordered list of absolute URLs before storage.
     *
     * @throws InvalidMarkupException When the list is empty, too large, or contains an invalid value.
     */
    public function __construct(array $urls)
    {
        if ($urls === [] || count($urls) > 100) {
            throw new InvalidMarkupException('FreeFind explicit links must contain between one and 100 URLs.');
        }

        if (! array_is_list($urls) || ! array_all($urls, fn(mixed $url): bool => $url instanceof AbsoluteUrl)) {
            throw new InvalidMarkupException('FreeFind explicit links must contain only validated absolute URLs.');
        }

        /** @var list<AbsoluteUrl> $urls */
        $this->urls = $urls;
    }

    /**
     * Converts URL strings and existing URL values into an explicit-links collection.
     *
     * @param array<array-key, mixed> $urls  URL strings and existing absolute-URL values are accepted in list order.
     *
     * @throws InvalidMarkupException When a value is not a string or an absolute URL.
     */
    public static function from(array $urls): self
    {
        foreach ($urls as $url) {
            if (! is_string($url) && ! $url instanceof AbsoluteUrl) {
                throw new InvalidMarkupException('FreeFind explicit links must contain URL strings or validated absolute URLs.');
            }
        }

        /** @var list<string|AbsoluteUrl> $urls */
        return new self(array_map(AbsoluteUrl::from(...), $urls));
    }
}
