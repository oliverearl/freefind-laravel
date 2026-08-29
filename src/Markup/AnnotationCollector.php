<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

final class AnnotationCollector
{
    public const string REQUEST_ATTRIBUTE = 'freefind.annotation_collector';

    /**
     * @var list<string>
     */
    private array $annotations = [];

    public function add(string $annotation): void
    {
        $this->annotations[] = $annotation;
    }

    public function render(): string
    {
        return implode("\n", $this->annotations);
    }

    public function clear(): void
    {
        $this->annotations = [];
    }
}
