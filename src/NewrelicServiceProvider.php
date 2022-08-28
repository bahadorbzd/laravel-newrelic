<?php

namespace Bzd\Newrelic;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class NewrelicServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Newrelic::class);
    }


    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            /** @var Newrelic $newRelic */
            $newRelic = app(Newrelic::class);
            $newRelic->startTransaction();
            $newRelic->nameTransaction($event->job->resolveName());
        });

        Queue::after(function (JobProcessed $event) {
            /** @var Newrelic $newRelic */
            $newRelic = app(Newrelic::class);
            $newRelic->endTransaction();
        });
    }


}
