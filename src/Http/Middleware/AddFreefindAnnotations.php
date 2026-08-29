<?php

declare(strict_types=1);

namespace Freefind\Freefind\Http\Middleware;

use Closure;
use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Freefind\Freefind\Markup\AnnotationCollector;
use Freefind\Freefind\Markup\Renderer;
use Illuminate\Http\Request;

/**
 * Adds selected page annotations to the request-scoped head collector.
 */
final class AddFreefindAnnotations
{
    /**
     * Maps route-facing annotation names to renderer methods.
     *
     * @var array<string, string>
     */
    private const array ANNOTATIONS = [
        'no-index' => 'noIndexPage',
        'no-map' => 'noMap',
        'not-new' => 'notNew',
    ];

    /**
     * Creates middleware backed by the request-scoped annotation renderer.
     */
    public function __construct(
        private readonly AnnotationCollector $collector,
        private readonly Renderer $renderer,
    ) {}

    /**
     * Collects the route's annotations and passes the request onward.
     *
     * @param Closure(Request): mixed $next
     *
     * @throws InvalidMarkupException When a route annotation name is unsupported.
     */
    public function handle(Request $request, Closure $next, string ...$annotations): mixed
    {
        foreach ($this->annotationNames($annotations) as $annotation) {
            $method = self::ANNOTATIONS[$annotation];
            $this->collector->add($this->renderer->{$method}());
        }

        return $next($request);
    }

    /**
     * Expands comma-separated route middleware arguments into unique annotation names.
     *
     * @param list<string> $annotations
     *
     * @return list<string>
     *
     * @throws InvalidMarkupException When an annotation name is unsupported.
     */
    private function annotationNames(array $annotations): array
    {
        $names = [];

        foreach ($annotations as $annotationGroup) {
            foreach (explode(',', $annotationGroup) as $annotation) {
                $annotation = trim($annotation);

                if ($annotation === '') {
                    continue;
                }

                if (! array_key_exists($annotation, self::ANNOTATIONS)) {
                    throw new InvalidMarkupException("Unsupported FreeFind route annotation [{$annotation}].");
                }

                if (! in_array($annotation, $names, true)) {
                    $names[] = $annotation;
                }
            }
        }

        return $names;
    }
}
