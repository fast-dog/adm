<?php

namespace FastDog\Adm;

use Dg482\Red\Adapters\Adapter;
use Dg482\Red\Builders\Form;
use Dg482\Red\Builders\Form\Fields\Field;
use FastDog\Adm\Adapters\EloquentAdapter;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;

/**
 * Class AdmServiceProvider
 * @version 1.0.0
 * @package FastDog\Adm
 * @author Андрей Мартынов <d.g.dev482@gmail.com>
 */
class AdmServiceProvider extends LaravelServiceProvider
{
    /** @var string */
    public const NAME = 'adm';

    /**
     *
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->handleConfigs();
        $this->handleRoutes();
        $this->handleMigrations();
        $this->handleViews();
        $this->handleLang();

        $this->publishes([
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR =>
                public_path('vendor/fast_dog/'.self::NAME),
        ], 'public');


        $this->commands([

        ]);

        $adapter = new EloquentAdapter(request());

        // 1.1 register singleton db adapter
        $this->app->singleton(Adapter::class, function () use ($adapter) {
            return $adapter;
        });

        // 1.2 binding fields
        collect($adapter->getTypeFields())->each(function ($class, $id) {
            $this->app->bind('AdmField'.Str::ucfirst($id), function () use ($class) {
                return new $class;
            });
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
//        $this->app->register(UserEventServiceProvider::class);
//        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Определение конфигурации по умолчанию
     */
    private function handleConfigs(): void
    {
        $configPath = __DIR__.'/../config/'.self::NAME.'.php';
        $this->publishes([$configPath => config_path(self::NAME.'.php')]);

        $this->mergeConfigFrom($configPath, self::NAME);
    }

    /**
     * Миграции базы данных
     */
    private function handleMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations/');
    }


    /**
     * Определение маршрутов пакета
     */
    private function handleRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
    }

    /**
     * Определение представлении пакета (шаблонов по умолчанию)
     */
    private function handleViews(): void
    {
        $path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
        $this->loadViewsFrom($path, self::NAME);

        $this->publishes([
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR =>
                base_path('resources/views/vendor/fast_dog/'),
        ]);
    }

    /**
     * Определение локализации
     */
    private function handleLang(): void
    {
        $path = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR;
        $this->loadTranslationsFrom($path, self::NAME);
        $this->publishes([
            $path => resource_path('lang/vendor/fast_dog/'.self::NAME),
        ]);
    }
}
