<?php

declare(strict_types=1);

namespace Freefind\Freefind\View;

use Freefind\Freefind\Markup\AnnotationCollector;
use Freefind\Freefind\Markup\DocumentDate;
use Freefind\Freefind\Markup\ExplicitLinks;
use Freefind\Freefind\Markup\Keywords;
use Freefind\Freefind\Markup\LinkPolicy;
use Freefind\Freefind\Markup\MapTitle;
use Freefind\Freefind\Markup\Renderer;
use Freefind\Freefind\Markup\ResultImage;
use Freefind\Freefind\Markup\WhatsNewEntry;
use Freefind\Freefind\View\Components\EmptyState;
use Freefind\Freefind\View\Components\Pagination;
use Freefind\Freefind\View\Components\ResultItem;
use Freefind\Freefind\View\Components\Results;
use Freefind\Freefind\View\Components\SearchForm;
use Freefind\Freefind\View\Components\SpellingSuggestion;
use Illuminate\Support\Facades\Blade;

final class BladeRegistrar
{
    public function register(): void
    {
        $this->registerComponents();
        $this->registerDirectives();
    }

    private function registerComponents(): void
    {
        Blade::component(SearchForm::class, 'freefind::search-form');
        Blade::component(Results::class, 'freefind::results');
        Blade::component(ResultItem::class, 'freefind::result-item');
        Blade::component(Pagination::class, 'freefind::pagination');
        Blade::component(SpellingSuggestion::class, 'freefind::spelling-suggestion');
        Blade::component(EmptyState::class, 'freefind::empty-state');
    }

    private function registerDirectives(): void
    {
        Blade::directive('freefindKeywords', fn(string $expression): string => $this->valueDirective(
            'keywords',
            Keywords::class,
            $expression,
        ));
        Blade::directive('freefindDocumentDate', fn(string $expression): string => $this->valueDirective(
            'documentDate',
            DocumentDate::class,
            $expression,
        ));
        Blade::directive('freefindNoIndexPage', fn(): string => $this->rendererDirective('noIndexPage'));
        Blade::directive('freefindNoIndex', fn(): string => $this->rendererDirective('beginNoIndex'));
        Blade::directive('endFreefindNoIndex', fn(): string => $this->rendererDirective('endNoIndex'));
        Blade::directive('freefindNoFollow', fn(): string => $this->rendererDirective('beginNoFollow'));
        Blade::directive('endFreefindNoFollow', fn(): string => $this->rendererDirective('endNoFollow'));
        Blade::directive('freefindLinks', fn(string $expression): string => $this->valueDirective(
            'links',
            ExplicitLinks::class,
            $expression,
        ));
        Blade::directive('freefindNoMap', fn(): string => $this->rendererDirective('noMap'));
        Blade::directive('freefindMapTitle', fn(string $expression): string => $this->valueDirective(
            'mapTitle',
            MapTitle::class,
            $expression,
        ));
        Blade::directive('freefindNotNew', fn(): string => $this->rendererDirective('notNew'));
        Blade::directive('freefindWhatsNew', fn(string $expression): string => $this->valueDirective(
            'whatsNew',
            WhatsNewEntry::class,
            $expression,
        ));
        Blade::directive('freefindResultImage', fn(string $expression): string => $this->valueDirective(
            'resultImage',
            ResultImage::class,
            $expression,
        ));
        Blade::directive('freefindLinkPolicy', fn(string $expression): string => $this->valueDirective(
            'pageLinkPolicy',
            LinkPolicy::class,
            $expression,
        ));
        Blade::directive('freefindGlobalLinkPolicy', fn(string $expression): string => $this->valueDirective(
            'globalLinkPolicy',
            LinkPolicy::class,
            $expression,
        ));
        Blade::directive('freefindHead', fn(): string => '<?php echo app(' . AnnotationCollector::class . '::class)->render(); ?>');
    }

    private function rendererDirective(string $method): string
    {
        return '<?php echo app(' . Renderer::class . '::class)->' . $method . '(); ?>';
    }

    private function valueDirective(string $method, string $valueClass, string $expression): string
    {
        return '<?php echo app(' . Renderer::class . '::class)->' . $method . '(' . $valueClass . '::from(' . $expression . ')); ?>';
    }
}
