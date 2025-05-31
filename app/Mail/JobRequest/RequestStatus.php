<?php

namespace App\Mail\JobRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $jobRequest;
    public $status;
    public $companyLogo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($jobRequest, $status)
    {
        //
        $this->jobRequest = $jobRequest;
        $this->status = $status;
        $this->companyLogo = env('COMPANY_LOGO'); 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = match ($this->status) {
            'approved' => 'Your Job Request Has Been Approved',
            'rejected' => 'Your Job Request Has Been Rejected',
            'pending' => 'Your Job Request Is Pending Review',
            default    => 'Job Request Status Updated',
        }; 

        return $this->from('no-reply@example.com')->subject($subject)->view('mail.jobRequest.requestStatus')->with(['companyLogo' => $this->companyLogo]);
    
    }
}
