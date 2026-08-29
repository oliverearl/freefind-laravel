<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

/**
 * Validated image metadata for a FreeFind result-image annotation.
 */
final readonly class ResultImage
{
    /**
     * Creates an image annotation value with constrained HTML and link attributes.
     *
     * @param  array<string, mixed>  $attributes  Allowed image attribute names; values are validated as scalar.
     * @param  array<string, mixed>  $linkAttributes  Allowed linked-image attribute names; values are validated as scalar.
     *
     * @throws InvalidMarkupException When an image value, dimension, target, or attribute is unsafe or invalid.
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
     * Creates an image annotation from URL strings or existing absolute-URL values.
     *
     * @param  array<string, mixed>  $attributes  Allowed image attribute names; values are validated as scalar.
     * @param  array<string, mixed>  $linkAttributes  Allowed linked-image attribute names; values are validated as scalar.
     *
     * @throws InvalidMarkupException When an image value, dimension, target, or attribute is unsafe or invalid.
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

    /**
     * Validates an optional image dimension.
     *
     * @throws InvalidMarkupException When a supplied dimension is outside the supported pixel range.
     */
    private static function validateDimension(?int $dimension, string $name): void
    {
        if ($dimension !== null && ($dimension < 1 || $dimension > 10_000)) {
            throw new InvalidMarkupException("FreeFind image {$name} must be between 1 and 10000 pixels.");
        }
    }

    /**
     * Validates linked-image attributes against the supported names and scalar values.
     *
     * @param  array<string, mixed>  $attributes
     * @param  list<string>  $allowed
     *
     * @throws InvalidMarkupException When an attribute name or value is not permitted.
     */
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
