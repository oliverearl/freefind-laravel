<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Illuminate\Support\Facades\Date;

final class Renderer
{
    public function __construct(
        private readonly HtmlCommentEscaper $escaper,
        private readonly MarkupState $state,
    ) {}

    public function keywords(Keywords $keywords): string
    {
        return $this->comment(
            'FreeFind Keywords Words=' . $this->escaper->attribute(implode(' ', $keywords->words))
            . ' Count=' . $this->escaper->attribute((string) $keywords->count),
        );
    }

    public function documentDate(DocumentDate $date): string
    {
        return '<meta name="document-date" content="' . $this->formatDate($date->date) . '">';
    }

    public function noIndexPage(): string
    {
        return $this->comment('FreeFind No Index Page');
    }

    public function noMap(): string
    {
        return $this->comment('FreeFind No Map');
    }

    public function mapTitle(MapTitle $title): string
    {
        return $this->comment('FreeFind Map Title=' . $this->escaper->attribute($title->title));
    }

    public function notNew(): string
    {
        return $this->comment('FreeFind Not New');
    }

    public function whatsNew(WhatsNewEntry $entry): string
    {
        $attributes = [];

        if ($entry->date !== null) {
            $attributes[] = 'Date=' . $this->escaper->attribute($this->formatDate($entry->date));
        }

        if ($entry->icon !== null) {
            $attributes[] = 'Icon=' . $this->escaper->attribute($entry->icon->value);
        }

        if ($entry->comment !== null) {
            $attributes[] = 'Comment=' . $this->escaper->attribute($entry->comment);
        }

        return $this->comment('FreeFind New ' . implode(' ', $attributes));
    }

    public function links(ExplicitLinks $links): string
    {
        return $this->comment('FreeFind Links ' . implode(' ', array_map(
            fn(AbsoluteUrl $url): string => $this->escaper->attribute($url->value),
            $links->urls,
        )));
    }

    public function resultImage(ResultImage $image): string
    {
        $attributes = [
            'src=' . $this->escaper->attribute($image->src->value),
        ];

        if ($image->alt !== null) {
            $attributes[] = 'alt=' . $this->escaper->attribute($image->alt);
        }

        if ($image->height !== null) {
            $attributes[] = 'height=' . $this->escaper->attribute((string) $image->height);
        }

        if ($image->width !== null) {
            $attributes[] = 'width=' . $this->escaper->attribute((string) $image->width);
        }

        foreach ($image->attributes as $name => $value) {
            $attributes[] = $name . '=' . $this->escaper->attribute((string) $value);
        }

        if ($image->href !== null) {
            $attributes[] = 'href=' . $this->escaper->attribute($image->href->value);
        }

        if ($image->target !== null) {
            $attributes[] = 'target=' . $this->escaper->attribute($image->target);
        }

        foreach ($image->linkAttributes as $name => $value) {
            $attributes[] = $name . '=' . $this->escaper->attribute((string) $value);
        }

        return $this->comment('FreeFind image ' . implode(' ', $attributes));
    }

    public function globalLinkPolicy(LinkPolicy $policy): string
    {
        return $this->renderMetaPolicy($policy);
    }

    public function pageLinkPolicy(LinkPolicy $policy): string
    {
        return $this->renderMetaPolicy($policy);
    }

    public function beginNoIndex(): string
    {
        $this->state->begin('no-index');

        return $this->comment('FreeFind Begin No Index');
    }

    public function endNoIndex(): string
    {
        $this->state->end('no-index');

        return $this->comment('FreeFind End No Index');
    }

    public function beginNoFollow(): string
    {
        $this->state->begin('no-follow');

        return $this->comment('FreeFind nofollow');
    }

    public function endNoFollow(): string
    {
        $this->state->end('no-follow');

        return $this->comment('FreeFind end nofollow');
    }

    private function formatDate(\DateTimeInterface $date): string
    {
        return str_replace(' UTC', ' GMT', Date::instance($date)->format('d M Y H:i:s T'));
    }

    private function renderMetaPolicy(LinkPolicy $policy): string
    {
        $values = [];

        if ($policy->queries !== null) {
            $values[] = $policy->queries === 'strip' ? 'stripQueries' : 'noFollowQueries';
        }

        if ($policy->scripts !== null) {
            $values[] = match ($policy->scripts) {
                'follow' => 'followScript',
                'ignore-page' => 'noFollowScript',
                'never' => 'neverFollowScript',
                default => throw new \LogicException('The FreeFind script policy was not validated.'),
            };
        }

        if ($policy->robots === 'ignore') {
            $values[] = 'noRobotsTag';
        }

        return implode("\n", array_map(
            fn(string $value): string => '<meta name="FreeFind" content="' . $value . '">',
            $values,
        ));
    }

    private function comment(string $body): string
    {
        return '<!-- ' . $body . ' -->';
    }
}
