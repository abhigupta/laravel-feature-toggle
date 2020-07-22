# Abstract Feature Flags from Providers
This package allows you to implement feature flags in Laravel.  Right now the only supported implementation is split.io.

## Basic Usage
### Change Behavior Based on a Flag
    use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;

    $client = resolve(FeatureToggleClient::class);
    switch ($client->getTreatment('my_flag')) {
        case "on":
            do_a_thing();
            break;

        default:
            do_another_thing();
            break;
    }

### Retrieve Configuration Attached to a Treatment
    $treatmentWithConfig = $client->getTreatmentWithConfig('my_flag');
    $treatment = $treatmentWithConfig['treatment'];
    $config = $treatmentWithConfig['config'];
    set_some_css_options($config);

### Retrieve Multiple Flags in a Batch
    $treatments = $client->getTreatments(['my_flag', 'another_flag']);
    switch($treatments['my_flag']) {
        ...
    }

### Retrieve Configurations in a Batch
    $treatmentsWithConfig = $client->getTreatmentsWithConfig('my_flag');
    $treatment = $treatmentsWithConfig['my_flag']['treatment'];
    $config = $treatmentsWithConfig['my_flgag']['config'];
    set_some_css_options($config);

## Installation
You can install the package via Composer:

    composer require partechgss/laravel-feature-toggles

## Configuration
### Config File
Looks for `config/feature-toggle.php`.  To install the default one:

    php artisan vendor:publish

### Middleware
You need to set the toggle "key" somewhere.  This is usually something like a user's email address, used to decide which treatment the user gets for a particular flag.  This package provides middleware that automatically sets the key based on the user's email addesss.  This must be run after your authentication middleware, so, first make it available as route middleware and set the priority in your `app/Http/Kernel.php`.

    use PartechGSS\Laravel\FeatureToggle\Middleware\SetFeatureToggleKeyFromUserEmail;
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'feature-toggle' => SetFeatureToggleKeyFromUserEmail::class,
    ];
    protected $middlewarePriority = [
        \App\Http\Middleware\Authenticate::class,
        SetFeatureToggleKeyFromUserEmail::class,
    ];

#### Execute as Route Middleware
    # routes/api.php
    Route::middleware(['auth:api', 'feature-toggle'])->group(function() {
        ...,
    });

#### Execute via Route Middleware Groups
    # app/Http/Kernel.php
    protected $middlewareGroups = [
        'web' => [
            ...,
            'feature-toggle',
        ],

        'api' => [
            \Barryvdh\Cors\HandleCors::class,
            'throttle:60,1',
            'bindings',
            'feature-toggle',
        ],
    ];

## Testing

    composer test