<?php

namespace SplitIO\Sdk;
function posix_getpwuid() {
    return ['dir' => __DIR__ . '/__data__'];
}

namespace PartehGSS\Laravel\FreatureToggle\Tests;

use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;
use PartechGSS\Laravel\FeatureToggle\Providers\FeatureToggleClientProvider;

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
        $toggleClient = app(FeatureToggleClient::class);
        $this->assertEquals("control", $toggleClient->getTreatment("AintNoSuchFlag"));
    }

    /**
     * Test that an 'off' treatment can be returned.
     * @test
     * @return void
     */
    public function it_renders_an_off_treatment() {
        $toggleClient = app(FeatureToggleClient::class);
        $this->assertEquals("off", $toggleClient->getTreatment("FancyFeatureFlag"));
    }

    /**
     * Test that an 'off' treatment can be returned.
     * @test
     * @return void
     */
    public function it_renders_an_on_treatment_for_user() {
        $toggleClient = app(FeatureToggleClient::class);
        $toggleClient->setKey("user@example.org");
        $this->assertEquals("on", $toggleClient->getTreatment("FancierFeatureFlag"));
    }
}