<?php

namespace PartechGSS\Laravel\FeatureToggle\Lib;

use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;
use SplitIO\Sdk\Factory\SplitFactoryInterface;

class SplitIOFeatureToggleClient implements FeatureToggleClient {
    private $splitFactory;
    private $splitClient;

    private $key;
    private $attributes;

    function __construct(SplitFactoryInterface $factory) {
        $this->splitFactory = $factory;
        $this->splitClient = optional($factory)->client();
    }

    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * @inheritDoc
     */
    public function getTreatment($flag, $attributes = null)
    {
        return optional($this->getTreatmentWithConfig($flag, $attributes, true))['treatment'];
    }

    /**
     * @inheritDoc
     */
    public function getTreatments($flags, $attributes = null)
    {
        return array_map(
            function ($t) { return $t['treatment']; },
            $this->getTreatmentsWithConfig($flags, $attributes, true) ?? []
        );
    }

    /**
     * @inheritDoc
     */
    public function getTreatmentWithConfig($flag, $attributes = null, $raw = false)
    {
        return optional($this->getTreatmentsWithConfig([$flag], $attributes, $raw))[$flag];
    }

    /**
     * @inheritDoc
     */
    public function getTreatmentsWithConfig($flags, $attributes = null, $raw = false)
    {
        $treatments = $this->splitClient->getTreatmentsWithConfig(
            $this->key,
            $flags,
            array_merge($this->attributes ?? [], $attributes ?? [])
        );
        if (!$raw) {
            $treatments = array_map(
                function($e) {
                    return array_merge($e, ['config' => isset($e['config']) ? json_decode($e['config'], true) : null]);
                },
                $treatments
            );
        }
        return $treatments;
    }
}
