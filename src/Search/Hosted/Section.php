<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Illuminate\Support\Str;

/**
 * A labeled section identifier for hosted or XML Page Search requests.
 */
final readonly class Section
{
    /**
     * Creates a validated section identifier and display label.
     *
     * @throws InvalidMarkupException When the identifier is reserved or malformed, or the label is unsafe.
     */
    public function __construct(public string $id, public string $label)
    {
        if ($this->id !== '' && ! preg_match('/^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$/', $this->id)) {
            throw new InvalidMarkupException('FreeFind section identifiers must be single-word values.');
        }

        if (Str::lower($this->id) === 'web') {
            throw new InvalidMarkupException('The FreeFind section identifier [web] is reserved for web search.');
        }

        if (
            $this->label === ''
            || preg_match('//u', $this->label) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $this->label) === 1
        ) {
            throw new InvalidMarkupException('FreeFind section labels must be non-empty and free of control characters.');
        }
    }

    /**
     * Creates a validated section value object.
     *
     * @throws InvalidMarkupException When the identifier is reserved or malformed, or the label is unsafe.
     */
    public static function from(string $id, string $label): self
    {
        return new self($id, $label);
    }
}
