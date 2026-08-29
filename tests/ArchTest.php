<?php

declare(strict_types=1);

arch()->preset()->laravel();
arch()->preset()->security();

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('package exceptions use an Exception suffix')
    ->expect('Freefind\\Freefind\\Exceptions')
    ->toHaveSuffix('Exception');
