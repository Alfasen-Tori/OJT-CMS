<?php

namespace App\Providers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SendGrid\Mail\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Use SendGrid Web API for email sending
        $this->app->resolving(MailManager::class, function ($mailManager) {
            $mailManager->extend('sendgrid', function () {
                return new class {
                    public function send($message)
                    {
                        $email = new Mail();
                        $email->setFrom(
                            config('mail.from.address'),
                            config('mail.from.name')
                        );

                        foreach ($message->getTo() as $address => $name) {
                            $email->addTo($address, $name);
                        }

                        $email->setSubject($message->getSubject());
                        $email->addContent("text/html", $message->getBody());

                        $sendgrid = new \SendGrid(config('services.sendgrid.api_key'));
                        $sendgrid->send($email);
                    }
                };
            });
        });
    }
}
