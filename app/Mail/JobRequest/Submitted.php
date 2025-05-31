<?php

namespace App\Mail\JobRequest;

use App\Models\JobRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class Submitted extends Mailable
{
    use Queueable, SerializesModels;

    public $companyLogo;
    public $jobRequest;
    public $recipientType;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(JobRequest $jobRequest, $recipientType = 'client')
    {
        //
        $this->companyLogo = env('COMPANY_LOGO'); 
        $this->jobRequest = $jobRequest;
        $this->recipientType = $recipientType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $mail = $this->from('no-reply@example.com')
        ->subject('New Job Request Submitted')
        ->view('mail.jobRequest.submitted')
        ->with([
            'jobRequest' => $this->jobRequest,
            'recipientType' => $this->recipientType,
            'companyLogo' => $this->companyLogo,
        ]);

        // Attach the job image if available
       if ($this->jobRequest->image && File::exists(public_path($this->jobRequest->image))) {
            $mail->attach(public_path($this->jobRequest->image), [
                'as' => basename($this->jobRequest->image),
                'mime' => File::mimeType(public_path($this->jobRequest->image)),
            ]);
        }

        return $mail;
    }
}
