<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HteSetupMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Public properties accessible in the view
    public $setupLink;
    public $contactName;
    public $organizationName;
    public $tempPassword;
    public $contactEmail;
    public $hasMoa;
    public $hasInternshipPlan;
    
    // Protected properties for internal use only
    protected $moaAttachmentPath;
    protected $internshipPlanPath;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $setupLink,
        string $contactName,
        string $organizationName,
        string $tempPassword,
        ?string $moaAttachmentPath = null,
        string $contactEmail,
        ?string $internshipPlanPath = null
    ) {
        $this->setupLink = $setupLink;
        $this->contactName = $contactName;
        $this->organizationName = $organizationName;
        $this->tempPassword = $tempPassword;
        $this->contactEmail = $contactEmail;
        $this->hasMoa = !is_null($moaAttachmentPath);
        $this->hasInternshipPlan = !is_null($internshipPlanPath);
        $this->moaAttachmentPath = $moaAttachmentPath;
        $this->internshipPlanPath = $internshipPlanPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->organizationName . ' - HTE Account Setup',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hte-setup',
            with: [
                'setupLink' => $this->setupLink,
                'contactName' => $this->contactName,
                'organizationName' => $this->organizationName,
                'tempPassword' => $this->tempPassword,
                'contactEmail' => $this->contactEmail,
                'hasMoa' => $this->hasMoa,
                'hasInternshipPlan' => $this->hasInternshipPlan,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach MOA template for new HTEs
        if ($this->hasMoa && file_exists($this->moaAttachmentPath)) {
            $attachments[] = Attachment::fromPath($this->moaAttachmentPath)
                ->as('MOA-Template-' . $this->organizationName . '.docx')
                ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        }

        // Attach Student Internship Plan (always attached when provided)
        if ($this->hasInternshipPlan && file_exists($this->internshipPlanPath)) {
            $attachments[] = Attachment::fromPath($this->internshipPlanPath)
                ->as('Student-Internship-Plan-' . $this->organizationName . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}