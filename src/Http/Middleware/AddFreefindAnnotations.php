<?php

declare(strict_types=1);

namespace Freefind\Freefind\Http\Middleware;

use Closure;
use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Freefind\Freefind\Markup\AnnotationCollector;
use Freefind\Freefind\Markup\Renderer;
use Illuminate\Http\Request;

final class AddFreefindAnnotations
{
    /**
     * @var array<string, string>
     */
    private const array ANNOTATIONS = [
        'no-index' => 'noIndexPage',
        'no-map' => 'noMap',
        'not-new' => 'notNew',
    ];

    public function __construct(
        private readonly AnnotationCollector $collector,
        private readonly Renderer $renderer,
    ) {}

    /**
     * @param  Closure(Request): mixed  $next
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
     * @param  list<string>  $annotations
     * @return list<string>
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
