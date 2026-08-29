<?php

declare(strict_types=1);

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use Freefind\Freefind\Search\Hosted\Section;

it('accepts an entire-site or named section', function (): void {
    expect(Section::from('', 'Entire site')->id)->toBe('')
        ->and(Section::from('manuals', 'Manuals')->label)->toBe('Manuals');
});

it('rejects reserved or malformed section identifiers and labels', function (string $id, string $label): void {
    expect(fn(): Section => new Section($id, $label))
        ->toThrow(InvalidMarkupException::class);
})->with([
    ['web', 'Web'],
    ['manuals and releases', 'Sections'],
    ['manuals', ''],
    ['manuals', "invalid\xC3"],
]);
