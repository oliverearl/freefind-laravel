<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

/**
 * Collects request-scoped annotations for deferred rendering in the document head.
 */
final class AnnotationCollector
{
    /**
     * Request attribute used to store the current annotation collector.
     */
    public const string REQUEST_ATTRIBUTE = 'freefind.annotation_collector';

    /**
     * Rendered annotations queued for the current request.
     *
     * @var list<string>
     */
    private array $annotations = [];

    /**
     * Appends one already-rendered annotation to the current request's collection.
     */
    public function add(string $annotation): void
    {
        $this->annotations[] = $annotation;
    }

    /**
     * Returns collected annotations in insertion order, separated by newlines.
     */
    public function render(): string
    {
        return implode("\n", $this->annotations);
    }

    /**
     * Removes all annotations currently held by this collector.
     */
    public function clear(): void
    {
        $this->annotations = [];
    }
}
