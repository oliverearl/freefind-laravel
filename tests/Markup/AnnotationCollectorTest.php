<?php

declare(strict_types=1);

use Freefind\Freefind\Markup\AnnotationCollector;

it('collects annotations in insertion order and can be cleared', function (): void {
    $collector = new AnnotationCollector();
    $collector->add('first');
    $collector->add('second');

    expect($collector->render())->toBe("first\nsecond");

    $collector->clear();

    expect($collector->render())->toBe('');
});
