<?php

namespace PartechGSS\Laravel\FeatureToggle\Contracts;

interface FeatureToggleClient {
    /**
     * Set the key to use for subsequent treatment requests.  Usually a user ID.
     *
     * @param string $key
     * @return void
     */
    public function setKey($key);

    /**
     * Set attributes to be used for subsequent treatment requests.
     *
     * @param array $attributes
     * @return void
     */
    public function setAttributes($attributes);

    /**
     * Return the treatment for a user and optional attributes.
     *
     * @param string $flag The feature flag to lookup, or list thereof.
     * @param array $attributes Optional attributes to match to a treatment.
     *              When provided, it will be shallowly merged with any
     *              attributes set on the class.
     *
     * @return string
     */
    public function getTreatment($flag, $attributes = null);

    /**
     * Return the treatment for a user and optional attributes.
     *
     * @param string[] $flags The feature flag to lookup, or list thereof.
     * @param array $attributes Optional attributes to match to a treatment.
     *              When provided, it will be shallowly merged with any
     *              attributes set on the class.
     *
     * @return array
     */
    public function getTreatments($flags, $attributes = null);

    /**
     * Return the treatments and any configuration data for a user and optional
     * attributes.
     *
     * @param string $flag The feature flag to lookup, or list thereof.
     * @param array $attributes Optional attributes to match to a treatment.
     *              When provided, it will be shallowly merged with any
     *              attributes set on the class.
     * @param bool $raw When true, return raw config instead of decoding JSON.
     *
     * @return array
     */
    public function getTreatmentWithConfig($flag, $attributes = null, $raw = false);

    /**
     * Return the treatment and any configuration data for a user and optional
     * attributes.  If $flag is a string, returns a single treatment.  Given an
     * array, will return a map of treatments.
     *
     * @param string[] $flags The feature flag to lookup, or list thereof.
     * @param array $attributes Optional attributes to match to a treatment.
     *              When provided, it will be shallowly merged with any
     *              attributes set on the class.
     * @param bool $raw When true, return raw config instead of decoding JSON.
     *
     * @return string|array
     */
    public function getTreatmentsWithConfig($flags, $attributes = null, $raw = false);
}
