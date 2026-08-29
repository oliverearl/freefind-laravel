<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

final readonly class ResultImage
{
    /**
     * @throws InvalidMarkupException When an image value is unsafe or invalid.
     */
    public function __construct(
        public AbsoluteUrl $src,
        public ?string $alt = null,
        public ?int $width = null,
        public ?int $height = null,
        public ?AbsoluteUrl $href = null,
        public ?string $target = null,
        public array $attributes = [],
        public array $linkAttributes = [],
    ) {
        self::validateDimension($this->width, 'width');
        self::validateDimension($this->height, 'height');
        self::validateAttributes($this->attributes, ['alt', 'class', 'id', 'loading', 'style', 'title']);
        self::validateAttributes($this->linkAttributes, ['class', 'id', 'rel', 'title']);

        if ($this->alt !== null) {
            HtmlCommentEscaper::assertSafe($this->alt);
        }

        if ($this->target !== null && ! BrowsingContextName::isValid($this->target)) {
            throw new InvalidMarkupException('FreeFind image targets must be valid browsing-context names.');
        }
    }

    /**
     * @throws InvalidMarkupException When an image value is unsafe or invalid.
     */
    public static function from(
        string|AbsoluteUrl $src,
        ?string $alt = null,
        ?int $width = null,
        ?int $height = null,
        string|AbsoluteUrl|null $href = null,
        ?string $target = null,
        array $attributes = [],
        array $linkAttributes = [],
    ): self {
        return new self(
            AbsoluteUrl::from($src),
            $alt,
            $width,
            $height,
            $href === null ? null : AbsoluteUrl::from($href),
            $target,
            $attributes,
            $linkAttributes,
        );
    }

    private static function validateDimension(?int $dimension, string $name): void
    {
        if ($dimension !== null && ($dimension < 1 || $dimension > 10_000)) {
            throw new InvalidMarkupException("FreeFind image {$name} must be between 1 and 10000 pixels.");
        }
    }

    /** @param list<string> $allowed */
    private static function validateAttributes(array $attributes, array $allowed): void
    {
        foreach ($attributes as $name => $value) {
            if (! in_array($name, $allowed, true) || ! is_scalar($value)) {
                throw new InvalidMarkupException("The FreeFind image attribute [{$name}] is not allowed.");
            }

            HtmlCommentEscaper::assertSafe((string) $value);
        }
    }
}
