@php
    $status = $application->status ?? 'pending';

    $color = match($status) {
        'pending' => '#ffc107',   
        'reviewed' => '#17a2b8',  
        'accepted' => '#28a745',  
        'rejected' => '#dc3545',  
    };
@endphp


@extends('mail.layout.mail')

@section('content')
    <tr>
        <td class="content-block" style="padding-top: 30px; padding-bottom: 10px;">
            <h2 style="text-align: center; color: #007bff; font-size: 22px;">
                Your Job Application Status Has Been Updated
            </h2>
        </td>
    </tr>

    <tr>
        <td style="font-size: 14px; line-height: 1.6; color: #343a40;">
            <p>Dear {{ $application->applicant->title . ' ' . $application->applicant->last_name ?? 'Applicant' }},</p>

            <p>
                Your application for the position of 
                <strong>{{ $application->jobPosting->title ?? 'Job Title' }}</strong> 
                has been updated to: 
                <span style="color: {{ $color }};"><strong>{{ ucfirst($status) }}</strong></span>.
            </p>

            <hr style="margin: 20px 0; border: none; border-top: 1px solid #dee2e6;">

            <p><strong>Job Details:</strong></p>
            <ul style="padding-left: 20px;">
                <li><strong>Title:</strong> {{ $application->jobPosting->title ?? 'N/A' }}</li>
                <li><strong>Description:</strong> {!! $application->jobPosting->description ?? 'N/A' !!}</li>
                <li><strong>Requirements:</strong> {!! $application->jobPosting->requirements ?? 'N/A' !!}</li>
            </ul>

            <p>If you have any questions, please reach out to our support team.</p>

            <p class="ignore" style="margin-top: 20px;">If you did not apply for this job, you can safely ignore this email.</p>
        </td>
    </tr>

    <!-- Optional: CTA -->
    <tr>
        <td align="center" style="padding: 30px 0;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" bgcolor="#007bff" style="border-radius: 5px;">
                        <a href="{{ url('/applicant/applications') }}" target="_blank"
                           style="font-size: 16px; font-family: 'Roboto', sans-serif; color: #ffffff; text-decoration: none; padding: 12px 25px; display: inline-block;">
                            View Application
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
