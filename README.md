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

## Testing

    composer test