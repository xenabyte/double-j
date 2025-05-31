<?php

namespace App\Mail\Applicant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registered extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $companyLogo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($applicant)
    {
        $this->applicant = $applicant;
        $this->companyLogo = env('COMPANY_LOGO'); 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@example.com')->subject('Welcome to Double J HR!')->view('mail.applicant.registered')->with(['companyLogo' => $this->companyLogo]);
    }
}
