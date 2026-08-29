<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class ExplicitLinks
{
    /**
     * @var list<AbsoluteUrl>
     */
    public array $urls;

    /**
     * @param  array<mixed>  $urls
     */
    public function __construct(array $urls)
    {
        if ($urls === [] || count($urls) > 100) {
            throw new InvalidMarkup('FreeFind explicit links must contain between one and 100 URLs.');
        }

        if (! array_is_list($urls) || ! array_all($urls, fn(mixed $url): bool => $url instanceof AbsoluteUrl)) {
            throw new InvalidMarkup('FreeFind explicit links must contain only validated absolute URLs.');
        }

        /** @var list<AbsoluteUrl> $urls */
        $this->urls = $urls;
    }

    /**
     * @param  array<mixed>  $urls
     */
    public static function from(array $urls): self
    {
        foreach ($urls as $url) {
            if (! is_string($url) && ! $url instanceof AbsoluteUrl) {
                throw new InvalidMarkup('FreeFind explicit links must contain URL strings or validated absolute URLs.');
            }
        }

        /** @var list<string|AbsoluteUrl> $urls */
        return new self(array_map(AbsoluteUrl::from(...), $urls));
    }
}
