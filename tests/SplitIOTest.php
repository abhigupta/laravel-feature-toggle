<?php

namespace SplitIO\Sdk;
function posix_getpwuid() {
    return ['dir' => __DIR__ . '/__data__'];
}

namespace PartehGSS\Laravel\FreatureToggle\Tests;

use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;
use PartechGSS\Laravel\FeatureToggle\FeatureToggleClientProvider;

class SplitIOTest extends \Orchestra\Testbench\TestCase {
    protected function getPackageProviders($app)
    {
        return [FeatureToggleClientProvider::class];
    }


    /**
     * Test that the control treatment is returned for a non-existant flag.
     * @test
     * @return void
     */
    public function it_renders_the_control_treatment() {
        config()->set('feature-toggle.splitio.factory.log.adapter', 'void');
        $toggleClient = app(FeatureToggleClient::class);
        $toggleClient->setKey("unrecognized@example.com");
        $this->assertEquals("control", $toggleClient->getTreatment("AintNoSuchFlag"));
    }

    /**
     * Test that the control treatment is returned when the user has not been set.
     * @test
     * @return void
     */
    public function it_renders_the_control_treatment_without_key() {
        config()->set('feature-toggle.splitio.factory.log.adapter', 'void');
        $toggleClient = app(FeatureToggleClient::class);
        $this->assertEquals("control", $toggleClient->getTreatment("FancyFeatureFlag"));
    }

    /**
     * Test that an 'off' treatment can be returned.
     * @test
     * @return void
     */
    public function it_renders_an_off_treatment() {
        config()->set('feature-toggle.splitio.factory.log.adapter', 'void');
        $toggleClient = app(FeatureToggleClient::class);
        $toggleClient->setKey("unrecognized@example.com");
        $this->assertEquals("off", $toggleClient->getTreatment("FancyFeatureFlag"));
    }

    /**
     * Test that an 'off' treatment can be returned.
     * @test
     * @return void
     */
    public function it_renders_an_on_treatment_for_user() {
        config()->set('feature-toggle.splitio.factory.log.adapter', 'void');
        $toggleClient = app(FeatureToggleClient::class);
        $toggleClient->setKey("user@example.org");
        $this->assertEquals("on", $toggleClient->getTreatment("FancierFeatureFlag"));
    }
}