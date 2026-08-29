<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class ResultImage
{
    /**
     * @param  array<mixed>  $attributes
     * @param  array<mixed>  $linkAttributes
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

        if ($this->target !== null && ! preg_match('/^(?:_(?:blank|self|parent|top)|[A-Za-z][A-Za-z0-9:_-]{0,63})$/', $this->target)) {
            throw new InvalidMarkup('FreeFind image targets must be valid browsing-context names.');
        }
    }

    /**
     * @param  string|AbsoluteUrl  $src
     * @param  array<mixed>  $attributes
     * @param  array<mixed>  $linkAttributes
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
            throw new InvalidMarkup("FreeFind image {$name} must be between 1 and 10000 pixels.");
        }
    }

    /**
     * @param  array<mixed>  $attributes
     * @param  list<string>  $allowed
     */
    private static function validateAttributes(array $attributes, array $allowed): void
    {
        foreach ($attributes as $name => $value) {
            if (! in_array($name, $allowed, true) || ! is_scalar($value)) {
                throw new InvalidMarkup("The FreeFind image attribute [{$name}] is not allowed.");
            }

            HtmlCommentEscaper::assertSafe((string) $value);
        }
    }
}
