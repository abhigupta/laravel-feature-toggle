<?php

namespace PartechGSS\Laravel\FeatureToggle;

use Exception;
use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;
use PartechGSS\Laravel\FeatureToggle\Lib\SplitIOFeatureToggleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use SplitIO\Sdk;
use SplitIO\Sdk\Factory\SplitFactoryInterface;

class FeatureToggleClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/feature-toggle.php', 'feature-toggle'
        );

        $this->app->singleton(FeatureToggleClient::class, function($app) {
            if (config('feature-toggle')) {
                try {
                    // This code fails when when the $HOME/split.yaml file
                    // is missing, most notable when run from composer.
                    // Just log, then ignore it.
                    return new SplitIOFeatureToggleClient(static::getSplitIOFactory());
                } catch (Exception $e) {
                    Log::warning("Unable to initialize Split.IO: ({$e->getMessage()}).  All features will default to control treatments.");
                    return;
                }
            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/feature-toggle.php' => config_path('feature-toggle.php'),
        ]);
    }

    private static $splitIOFactory;
    /**
     * Return the singleton factory instance.  This is only needed for testing, since
     * SplitIO enforces a singleton factory by returning an error the second time one
     * is instantiated instead of simply returning the original instance.
     * 
     * When testing, the test wrapper resets the list of providers so, despite being
     * declared as a singleton, our register function gets called a second time, at
     * which time it tries to re-instantiate the factory and blows up.
     *
     * @return SplitFactoryInterface
     */
    private static function getSplitIOFactory(): SplitFactoryInterface {
        if (!static::$splitIOFactory) {
            static::$splitIOFactory = Sdk::factory(
                config('feature-toggle.splitio.api_key'),
                config('feature-toggle.splitio.factory')
            );
        }

        return static::$splitIOFactory;
    }
}
