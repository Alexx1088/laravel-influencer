<?php

namespace App\Providers;

use App\Events\OrderCompletedEvent;
use App\Events\ProductUpdatedEvent;
use App\Jobs\AdminAdded;
use App\Listeners\ProductCacheFlush;
use App\Listeners\UpdateRankingsListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCompletedEvent::class => [
            UpdateRankingsListener::class,
        ],
        ProductUpdatedEvent::class => [
            ProductCacheFlush::class
        ]
    ];

    public function boot()
    {
        \App::bindMethod(AdminAdded::class . 'handle', fn($job) => $job->handle());
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
