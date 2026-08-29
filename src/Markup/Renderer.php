<?php

declare(strict_types=1);

namespace Freefind\Freefind\Markup;

use Freefind\Freefind\Exceptions\InvalidMarkupException;
use LogicException;
use Illuminate\Support\Facades\Date;

/**
 * Renders validated value objects into FreeFind comments and meta annotations.
 */
final class Renderer
{
    /**
     * Creates a renderer using the request-scoped escaping and region state services.
     */
    public function __construct(
        private readonly HtmlCommentEscaper $escaper,
        private readonly MarkupState $state,
    ) {}

    /**
     * Renders the weighted keyword annotation.
     */
    public function keywords(Keywords $keywords): string
    {
        return $this->comment(
            'FreeFind Keywords Words=' . $this->escaper->attribute(implode(' ', $keywords->words))
            . ' Count=' . $this->escaper->attribute((string) $keywords->count),
        );
    }

    /**
     * Renders the document-date meta tag in FreeFind's expected format.
     */
    public function documentDate(DocumentDate $date): string
    {
        return '<meta name="document-date" content="' . $this->formatDate($date->date) . '">';
    }

    /**
     * Renders the page-level no-index annotation.
     */
    public function noIndexPage(): string
    {
        return $this->comment('FreeFind No Index Page');
    }

    /**
     * Renders the no-site-map annotation.
     */
    public function noMap(): string
    {
        return $this->comment('FreeFind No Map');
    }

    /**
     * Renders the site-map title annotation.
     */
    public function mapTitle(MapTitle $title): string
    {
        return $this->comment('FreeFind Map Title=' . $this->escaper->attribute($title->title));
    }

    /**
     * Renders the annotation that excludes a page from what's-new results.
     */
    public function notNew(): string
    {
        return $this->comment('FreeFind Not New');
    }

    /**
     * Renders a what's-new annotation with its supplied optional attributes.
     */
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

    /**
     * Renders the explicit-link discovery annotation.
     */
    public function links(ExplicitLinks $links): string
    {
        return $this->comment('FreeFind Links ' . implode(' ', array_map(
            fn(AbsoluteUrl $url): string => $this->escaper->attribute($url->value),
            $links->urls,
        )));
    }

    /**
     * Renders the result-image annotation and its validated attributes.
     */
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

    /**
     * Renders a site-wide link policy as FreeFind meta tags.
     */
    public function globalLinkPolicy(LinkPolicy $policy): string
    {
        return $this->renderMetaPolicy($policy);
    }

    /**
     * Renders a page-level link policy as FreeFind meta tags.
     */
    public function pageLinkPolicy(LinkPolicy $policy): string
    {
        return $this->renderMetaPolicy($policy);
    }

    /**
     * Opens a no-index content region and renders its start marker.
     */
    public function beginNoIndex(): string
    {
        $this->state->begin('no-index');

        return $this->comment('FreeFind Begin No Index');
    }

    /**
     * Closes a no-index content region and renders its end marker.
     *
     * @throws InvalidMarkupException When no matching no-index region is open.
     */
    public function endNoIndex(): string
    {
        $this->state->end('no-index');

        return $this->comment('FreeFind End No Index');
    }

    /**
     * Opens a no-follow content region and renders its start marker.
     */
    public function beginNoFollow(): string
    {
        $this->state->begin('no-follow');

        return $this->comment('FreeFind nofollow');
    }

    /**
     * Closes a no-follow content region and renders its end marker.
     *
     * @throws InvalidMarkupException When no matching no-follow region is open.
     */
    public function endNoFollow(): string
    {
        $this->state->end('no-follow');

        return $this->comment('FreeFind end nofollow');
    }

    /**
     * Formats a date using FreeFind's expected GMT representation.
     */
    private function formatDate(\DateTimeInterface $date): string
    {
        return str_replace(' UTC', ' GMT', Date::instance($date)->format('d M Y H:i:s T'));
    }

    /**
     * Renders validated link-policy values as FreeFind meta tags.
     *
     * @throws LogicException When a script policy value was not validated.
     */
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
                default => throw new LogicException('The FreeFind script policy was not validated.'),
            };
        }

        if ($policy->robots === 'ignore') {
            $values[] = 'noRobotsTag';
        }

        return implode(PHP_EOL, array_map(
            fn(string $value): string => '<meta name="FreeFind" content="' . $value . '">',
            $values,
        ));
    }

    /**
     * Wraps a rendered annotation body in an HTML comment.
     */
    private function comment(string $body): string
    {
        return '<!-- ' . $body . ' -->';
    }
}
