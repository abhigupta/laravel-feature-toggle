<?php

namespace PartechGSS\Laravel\FeatureToggle\Middleware;

use Closure;
use PartechGSS\Laravel\FeatureToggle\Contracts\FeatureToggleClient;

class SetFeatureToggleKeyFromUserEmail
{
    private $featureToggleClient;

    /**
     * Create a new middleware instance.
     *
     * @param  ApiManager  $auth
     * @return void
     */
    public function __construct(FeatureToggleClient $featureToggleClient)
    {
        $this->featureToggleClient = $featureToggleClient;
    }

    /**
     * Handle an incoming request.  Sets the feature toggle client key to $request->user()->email
     * when found, and to "__no_user__" otherwise.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // After authentication, set the feature toggle key to the user's email.
        $this->featureToggleClient->setKey(optional($request->user())->email ?? "__no_user__");

        return $next($request);
    }
}
