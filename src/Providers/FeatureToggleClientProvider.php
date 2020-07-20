<?php

namespace PartechGSS\Laravel\FeatureToggle\Providers;

use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;
use PartechGSS\Laravel\FeatureToggle\Lib\SplitIOFeatureToggleClient;
use Illuminate\Http\Request;
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
            __DIR__.'/../../config/feature-toggle.php', 'feature-toggle'
        );

        $this->app->singleton(FeatureToggleClient::class, function($app) {
            if (config('feature-toggle.provider') === 'splitio') {
                return new SplitIOFeatureToggleClient(static::getSplitIOFactory());
            }

            // I used to throw this exception here, but it breaks during composer install
            // because the config isn't set yet.
            // throw new \Exception("Unrecognized feature toggle provider " . config('feature-toggle.provider'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(FeatureToggleClient $client, Request $request)
    {
        $this->publishes([
            __DIR__.'/../../config/feature-toggle.php' => config_path('feature-toggle.php'),
        ]);

        // The SplitIO SDK doesn't allow empty keys.
        $client->setKey(optional($request->user())->email ?? "__dummy_key__");
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
