<?php

namespace FastDog\Adm;

use Dg482\Red\Adapters\Adapter;
use FastDog\Adm\Adapters\EloquentAdapter;
use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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

        $adapter = app()->make(EloquentAdapter::class);

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


        /** @var CacheManager $cache */
        $cache = app()->get('cache');
        // 1.3 init resources
        $resources = $cache->getStore()->get('FastDogAdmResources');
        if (null === $resources) {
            $resources = [];
            /** @var Filesystem $filesystem */
            $filesystem = app()->get(Filesystem::class);
            // 1.3.1 default resources
            if (is_dir(__DIR__.'/Resources')) {
                array_map(function (string $directory) use (&$resources) {
                    array_push($resources, [
                        'namespace' => 'FastDog\\Adm\\Resources\\',
                        'idx' => Arr::last(explode('/', $directory)),
                    ]);
                }, $filesystem->directories(__DIR__.'/Resources'));
            }

            // 1.3.2 app resources
            if (is_dir(app_path('Resources'))) {
                array_map(function (string $directory) use (&$resources) {
                    array_push($resources, [
                        'namespace' => 'App\\Resources\\',
                        'idx' => Arr::last(explode('/', $directory)).'Resource',
                    ]);
                }, $filesystem->directories(app_path('Resources')));
            }
            // 1.3.3 cache resource array
            $cache->getStore()->put('FastDogAdmResources', $resources, config('adm.resource_ttl', 60));
        }

        // 1.6 include resource
        array_map(function (array $res) {
            $this->includeResource($res['namespace'], $res['idx']);
        }, $resources);
    }

    /**
     * @param  string  $namespace
     * @param  string  $idx
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function includeResource(string $namespace, string $idx)
    {
        $targetClass = $namespace.$idx.'\\'.$idx.'Resource';
        if (class_exists($targetClass)) {
            app()->bind($idx.'Resource', $targetClass);
        }
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(AdmEventServiceProvider::class);
//        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            \Spatie\Permission\PermissionServiceProvider::class,
        ];
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
