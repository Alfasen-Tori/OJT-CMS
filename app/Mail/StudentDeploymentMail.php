<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentDeploymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $hteName;
    protected $contractPath; // Only contract now

    public function __construct(string $studentName, string $hteName, string $contractPath)
    {
        $this->studentName = $studentName;
        $this->hteName = $hteName;
        $this->contractPath = $contractPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Internship Endorsement - Deployment to ' . $this->hteName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student-deployment',
            with: [
                'studentName' => $this->studentName,
                'hteName' => $this->hteName,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if (file_exists($this->contractPath)) {
            $attachments[] = Attachment::fromPath($this->contractPath)
                ->as('Student-Internship-Contract-' . $this->studentName . '.docx')
                ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        }

        return $attachments; // No endorsement
    }
}