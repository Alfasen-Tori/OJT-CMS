<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $role;
    public $name;

    public function __construct($token, $role, $name)
    {
        $this->token = $token;
        $this->role = $role;
        $this->name = $name;
    }

    public function build()
    {
        $resetUrl = route('password.reset', ['token' => $this->token]);
        
        return $this->subject('Password Reset Request - OJT-CMS')
                    ->view('emails.password-reset')
                    ->with([
                        'name' => $this->name,
                        'role' => $this->role,
                        'resetUrl' => $resetUrl,
                        'expiryHours' => 1
                    ]);
    }
}