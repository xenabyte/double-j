<?php

namespace App\Mail\Applicant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppliedForJob extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $companyLogo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        //
        $this->application = $application;
        $this->companyLogo = env('COMPANY_LOGO');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@example.com')->subject('Your Job Application Was Submitted')->view('mail.applicant.appliedForJob')->with(['companyLogo' => $this->companyLogo]);
    }
}
