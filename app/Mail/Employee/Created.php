<?php

namespace App\Mail\Employee;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Created extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $token;
    public $companyLogo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee, $token)
    {
        //

        $this->employee = $employee;
        $this->token = $token;
        $this->companyLogo = env('COMPANY_LOGO'); 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@example.com')->subject('Welcome! Please Set Up Your Account')->view('mail.employee.employeeCreated')->with(['resetLink' => url('/employee/password/reset/' . $this->token), 'companyLogo' => $this->companyLogo,]);
    }
}
