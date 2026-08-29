<?php

declare(strict_types=1);

namespace Freefind\Freefind\Search\Hosted;

use Freefind\Freefind\Exceptions\InvalidMarkup;

final readonly class Section
{
    public function __construct(public string $id, public string $label)
    {
        if ($this->id !== '' && ! preg_match('/^[A-Za-z0-9][A-Za-z0-9_-]{0,63}$/', $this->id)) {
            throw new InvalidMarkup('FreeFind section identifiers must be single-word values.');
        }

        if (strtolower($this->id) === 'web') {
            throw new InvalidMarkup('The FreeFind section identifier [web] is reserved for web search.');
        }

        if (
            $this->label === ''
            || preg_match('//u', $this->label) !== 1
            || preg_match('/[\x00-\x1F\x7F]/', $this->label) === 1
        ) {
            throw new InvalidMarkup('FreeFind section labels must be non-empty and free of control characters.');
        }
    }

    public static function from(string $id, string $label): self
    {
        return new self($id, $label);
    }
}
