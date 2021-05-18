<?php

namespace App\Providers;

use App\Events\MailActivateAccountRequestEvent;
use App\Events\MailCompanyRegisteredVerificationEvent;
use App\Events\MailPasswordResetSuccessEvent;
use App\Events\MailResetPasswordRequestEvent;
use App\Events\UserAccountActivatedEvent;
use App\Listeners\MailActivateAccountRequestListener;
use App\Listeners\MailCompanyRegisteredVerificationListener;
use App\Listeners\MailPasswordResetSuccessListener;
use App\Listeners\MailResetPasswordRequestListener;
use App\Listeners\UserAccountActivatedListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
            //SendEmailVerificationNotification::class,
            MailCompanyRegisteredVerificationEvent::class=>[
                MailCompanyRegisteredVerificationListener::class,
            ],
            MailActivateAccountRequestEvent::class=>[
                MailActivateAccountRequestListener::class,
            ],
            MailResetPasswordRequestEvent::class=>[
                MailResetPasswordRequestListener::class,
            ],
            MailPasswordResetSuccessEvent::class=>[
                MailPasswordResetSuccessListener::class,
            ],
            UserAccountActivatedEvent::class=>[
                UserAccountActivatedListener::class,
            ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
