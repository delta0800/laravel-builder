<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\GenerateCurd::class => [
            \App\Listeners\SendGeneratedCurd::class
        ],
        \App\Events\GenerateZipFile::class => [
            \App\Listeners\GenerateZipFileListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('App\Events\SendMail', function($event)
        {
            Notification::send(
                User::find(1),
                new \App\Notifications\ZipFileNotification($event->downloadRequest)
            );
        });
    }
}
