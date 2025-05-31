<?php

namespace App\Mail\Application;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $companyLogo;
    public $application;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        //
        $this->companyLogo = env('COMPANY_LOGO'); 
        $this->application = $application; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@example.com')->subject('Application Status Updated')->view('mail.application.statusUpdated')->with(['companyLogo' => $this->companyLogo]);
    }
}
