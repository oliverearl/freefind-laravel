<?php

declare(strict_types=1);

namespace Freefind\Freefind;

use Freefind\Freefind\Configuration\FreefindConfig;
use Freefind\Freefind\Configuration\HttpSettings;
use Freefind\Freefind\Configuration\SpiderSettings;
use Freefind\Freefind\Contracts\SearchClient;
use Freefind\Freefind\Contracts\SearchTransport;
use Freefind\Freefind\Contracts\XmlResponseParser;
use Freefind\Freefind\Http\Middleware\AddFreefindAnnotations;
use Freefind\Freefind\Http\Middleware\DetectFreefindSpider;
use Freefind\Freefind\Markup\AnnotationCollector;
use Freefind\Freefind\Markup\HtmlCommentEscaper;
use Freefind\Freefind\Markup\MarkupState;
use Freefind\Freefind\Markup\Renderer;
use Freefind\Freefind\Search\Hosted\HostedSearch;
use Freefind\Freefind\Search\Xml\FreefindXmlClient;
use Freefind\Freefind\Search\Xml\Transport\LaravelXmlSearchTransport;
use Freefind\Freefind\Search\Xml\Response\FreefindXmlResponseParser;
use Freefind\Freefind\View\Components\EmptyState;
use Freefind\Freefind\View\Components\Pagination;
use Freefind\Freefind\View\Components\ResultItem;
use Freefind\Freefind\View\Components\Results;
use Freefind\Freefind\View\Components\SearchForm;
use Freefind\Freefind\View\Components\SpellingSuggestion;
use Freefind\Freefind\Spider\SpiderContext;
use Freefind\Freefind\Spider\SpiderDetector;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FreefindServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('freefind-laravel')
            ->hasConfigFile()
            ->hasViews();
    }

    #[Override]
    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->bind(FreefindConfig::class, function (Application $app): FreefindConfig {
            $config = $app->make('config')->get('freefind-laravel', []);

            if (! is_array($config)) {
                throw new \RuntimeException('The freefind-laravel configuration must be an array.');
            }

            return FreefindConfig::fromConfig($config);
        });

        $this->app->singleton(SpiderDetector::class, fn(Application $app): SpiderDetector => SpiderDetector::fromSettings(
            $app->make(FreefindConfig::class)->spider,
        ));

        $this->app->bind(SpiderSettings::class, fn(Application $app): SpiderSettings => $app->make(
            FreefindConfig::class,
        )->spider);
        $this->app->bind(HttpSettings::class, fn(Application $app): HttpSettings => $app->make(
            FreefindConfig::class,
        )->http);
        $this->app->bind(HostedSearch::class, fn(Application $app): HostedSearch => new HostedSearch(
            $app->make(FreefindConfig::class)->account,
        ));
        $this->app->bind(LaravelXmlSearchTransport::class);
        $this->app->bind(SearchTransport::class, fn(Application $app): SearchTransport => $app->make(
            LaravelXmlSearchTransport::class,
        ));
        $this->app->bind(FreefindXmlResponseParser::class);
        $this->app->bind(XmlResponseParser::class, fn(Application $app): XmlResponseParser => $app->make(
            FreefindXmlResponseParser::class,
        ));
        $this->app->bind(FreefindXmlClient::class);
        $this->app->bind(SearchClient::class, fn(Application $app): SearchClient => $app->make(
            FreefindXmlClient::class,
        ));

        $this->app->singleton(HtmlCommentEscaper::class);
        $this->app->bind(MarkupState::class, function (Application $app): MarkupState {
            if (! $app->bound('request')) {
                return new MarkupState();
            }

            $request = $app->make('request');
            $state = $request->attributes->get(MarkupState::REQUEST_ATTRIBUTE);

            if (! $state instanceof MarkupState) {
                $state = new MarkupState();
                $request->attributes->set(MarkupState::REQUEST_ATTRIBUTE, $state);
            }

            return $state;
        });
        $this->app->bind(AnnotationCollector::class, function (Application $app): AnnotationCollector {
            if (! $app->bound('request')) {
                return new AnnotationCollector();
            }

            $request = $app->make('request');
            $collector = $request->attributes->get(AnnotationCollector::REQUEST_ATTRIBUTE);

            if (! $collector instanceof AnnotationCollector) {
                $collector = new AnnotationCollector();
                $request->attributes->set(AnnotationCollector::REQUEST_ATTRIBUTE, $collector);
            }

            return $collector;
        });
        $this->app->bind(Renderer::class, fn(Application $app): Renderer => new Renderer(
            $app->make(HtmlCommentEscaper::class),
            $app->make(MarkupState::class),
        ));

        $this->app->bind(SpiderContext::class, function (Application $app): SpiderContext {
            if (! $app->bound('request')) {
                return SpiderContext::notSpider();
            }

            $context = $app->make('request')->attributes->get(
                SpiderContext::REQUEST_ATTRIBUTE,
            );

            return $context instanceof SpiderContext ? $context : SpiderContext::notSpider();
        });

        $config = $this->app->make('config')->get('freefind-laravel', []);
        $spiderConfig = is_array($config) && is_array($config['spider'] ?? null)
            ? $config['spider']
            : [];

        if (($spiderConfig['middleware'] ?? false) === true) {
            $this->app->make(Kernel::class)->pushMiddleware(DetectFreefindSpider::class);
        }
    }

    #[Override]
    public function bootingPackage(): void
    {
        parent::bootingPackage();

        $this->app->make(Router::class)->aliasMiddleware('freefind.spider', DetectFreefindSpider::class);
        $this->app->make(Router::class)->aliasMiddleware('freefind.annotate', AddFreefindAnnotations::class);
        Blade::component(SearchForm::class, 'freefind::search-form');
        Blade::component(Results::class, 'freefind::results');
        Blade::component(ResultItem::class, 'freefind::result-item');
        Blade::component(Pagination::class, 'freefind::pagination');
        Blade::component(SpellingSuggestion::class, 'freefind::spelling-suggestion');
        Blade::component(EmptyState::class, 'freefind::empty-state');

        Blade::directive('freefindKeywords', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->keywords(\\Freefind\\Freefind\\Markup\\Keywords::from(' . $expression . ')); ?>');
        Blade::directive('freefindDocumentDate', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->documentDate(\\Freefind\\Freefind\\Markup\\DocumentDate::from(' . $expression . ')); ?>');
        Blade::directive('freefindNoIndexPage', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->noIndexPage(); ?>');
        Blade::directive('freefindNoIndex', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->beginNoIndex(); ?>');
        Blade::directive('endFreefindNoIndex', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->endNoIndex(); ?>');
        Blade::directive('freefindNoFollow', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->beginNoFollow(); ?>');
        Blade::directive('endFreefindNoFollow', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->endNoFollow(); ?>');
        Blade::directive('freefindLinks', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->links(\\Freefind\\Freefind\\Markup\\ExplicitLinks::from(' . $expression . ')); ?>');
        Blade::directive('freefindNoMap', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->noMap(); ?>');
        Blade::directive('freefindMapTitle', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->mapTitle(\\Freefind\\Freefind\\Markup\\MapTitle::from(' . $expression . ')); ?>');
        Blade::directive('freefindNotNew', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->notNew(); ?>');
        Blade::directive('freefindWhatsNew', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->whatsNew(\\Freefind\\Freefind\\Markup\\WhatsNewEntry::from(' . $expression . ')); ?>');
        Blade::directive('freefindResultImage', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->resultImage(\\Freefind\\Freefind\\Markup\\ResultImage::from(' . $expression . ')); ?>');
        Blade::directive('freefindLinkPolicy', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->pageLinkPolicy(\\Freefind\\Freefind\\Markup\\LinkPolicy::from(' . $expression . ')); ?>');
        Blade::directive('freefindGlobalLinkPolicy', fn(string $expression): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\Renderer::class)->globalLinkPolicy(\\Freefind\\Freefind\\Markup\\LinkPolicy::from(' . $expression . ')); ?>');
        Blade::directive('freefindHead', fn(): string => '<?php echo app(\\Freefind\\Freefind\\Markup\\AnnotationCollector::class)->render(); ?>');
    }
}
