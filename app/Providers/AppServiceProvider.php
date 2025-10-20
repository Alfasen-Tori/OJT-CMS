<?php

namespace App\Providers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SendGrid\Mail\Mail as SendGridMail;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;

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

        // Extend Laravel Mailer with SendGrid Web API Transport
        $this->app->resolving(MailManager::class, function ($mailManager) {
            $mailManager->extend('sendgrid', function () {
                return new class implements TransportInterface {
                    public function send(\Symfony\Component\Mime\RawMessage $message, ?\Symfony\Component\Mailer\Envelope $envelope = null): ?\Symfony\Component\Mailer\SentMessage
                    {
                        $email = new SendGridMail();

                        // Parse Symfony Email message
                        if ($message instanceof Email) {
                            $from = $message->getFrom()[0];
                            $email->setFrom($from->getAddress(), $from->getName());

                            foreach ($message->getTo() as $to) {
                                $email->addTo($to->getAddress(), $to->getName());
                            }

                            $email->setSubject($message->getSubject());
                            $htmlBody = $message->getHtmlBody() ?? $message->getTextBody();
                            $email->addContent("text/html", $htmlBody);
                        }

                        // Send via SendGrid API
                        $sendgrid = new \SendGrid(config('services.sendgrid.api_key'));
                        $sendgrid->send($email);

                        return null;
                    }

                    public function __toString(): string
                    {
                        return 'sendgrid';
                    }
                };
            });
        });
    }
}
