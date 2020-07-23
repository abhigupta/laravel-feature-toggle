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
    public function getTreatment(string $flag, ?array $attributes = null)
    {
        return optional($this->getTreatmentWithConfig($flag, $attributes, true))['treatment'];
    }

    /**
     * @inheritDoc
     */
    public function getTreatments(array $flags, ?array $attributes = null)
    {
        return array_map(
            fn($t) => $t['treatment'],
            $this->getTreatmentsWithConfig($flags, $attributes, true) ?? []
        );
    }

    /**
     * @inheritDoc
     */
    public function getTreatmentWithConfig(string $flag, ?array $attributes = null, bool $raw = false)
    {
        return optional($this->getTreatmentsWithConfig([$flag], $attributes, $raw))[$flag];
    }

    /**
     * @inheritDoc
     */
    public function getTreatmentsWithConfig(array $flags, ?array $attributes = null, bool $raw = false)
    {
        $treatments = $this->splitClient->getTreatmentsWithConfig(
            $this->key,
            $flags,
            array_merge($this->attributes ?? [], $attributes ?? [])
        );
        if (!$raw) {
            $treatments = array_map(
                fn($e) => array_merge($e, ['config' => isset($e['config']) ? json_decode($e['config'], true) : null]),
                $treatments
            );
        }
        return $treatments;
    }
}
