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
    public function register()
    {
        //
    }

    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        $this->app->resolving(MailManager::class, function ($mailManager) {
            $mailManager->extend('sendgrid', function () {
                return new class implements TransportInterface {
                    public function send(\Symfony\Component\Mime\RawMessage $message, ?\Symfony\Component\Mailer\Envelope $envelope = null): ?\Symfony\Component\Mailer\SentMessage
                    {
                        $email = new SendGridMail();

                        if ($message instanceof Email) {
                            // From
                            $from = $message->getFrom()[0];
                            $email->setFrom($from->getAddress(), $from->getName());

                            // To
                            foreach ($message->getTo() as $to) {
                                $email->addTo($to->getAddress(), $to->getName());
                            }

                            // Subject and Body
                            $email->setSubject($message->getSubject());
                            $htmlBody = $message->getHtmlBody() ?? $message->getTextBody();
                            $email->addContent("text/html", $htmlBody);

                            // ✅ Attachments
                            foreach ($message->getAttachments() as $attachment) {
                                // Get attachment metadata
                                $filename = method_exists($attachment, 'getFilename')
                                    ? $attachment->getFilename()
                                    : ($attachment->getPreparedHeaders()->getHeaderParameter('Content-Disposition', 'filename') ?? 'attachment');

                                $mimeType = $attachment->getContentType();

                                // Get raw attachment body (binary stream)
                                $stream = $attachment->getBody();
                                $content = '';

                                if (is_resource($stream)) {
                                    // If it's a resource (file handle)
                                    $content = base64_encode(stream_get_contents($stream));
                                } elseif (is_string($stream) && file_exists($stream)) {
                                    // If it's a file path
                                    $content = base64_encode(file_get_contents($stream));
                                } elseif (is_string($stream)) {
                                    // If it's a string (already loaded data)
                                    $content = base64_encode($stream);
                                }

                                if (!empty($content)) {
                                    $email->addAttachment($content, $mimeType, $filename);
                                }
                            }

                        }

                        // Send via SendGrid
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
