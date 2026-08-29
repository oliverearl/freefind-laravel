<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;

final readonly class LinkPolicy
{
    public function __construct(
        public ?string $queries = null,
        public ?string $scripts = null,
        public ?string $robots = null,
    ) {
        if ($this->queries === null && $this->scripts === null && $this->robots === null) {
            throw new InvalidMarkupException('A FreeFind link policy must provide at least one setting.');
        }

        self::validate($this->queries, ['strip', 'ignore'], 'queries');
        self::validate($this->scripts, ['follow', 'ignore-page', 'never'], 'scripts');
        self::validate($this->robots, ['honour', 'ignore'], 'robots');
    }

    public static function from(
        ?string $queries = null,
        ?string $scripts = null,
        ?string $robots = null,
    ): self {
        return new self($queries, $scripts, $robots);
    }

    /**
     * @param  list<string>  $allowed
     */
    private static function validate(?string $value, array $allowed, string $name): void
    {
        if ($value !== null && ! in_array($value, $allowed, true)) {
            throw new InvalidMarkupException("The FreeFind {$name} policy is invalid.");
        }
    }
}
