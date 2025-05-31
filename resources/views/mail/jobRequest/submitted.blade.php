@extends('mail.layout.mail')

@section('content')
    <tr>
        <td class="content-block" style="padding-top: 30px; padding-bottom: 10px;">
            <h2 style="text-align: center; color: #007bff; font-size: 22px;">
                @if ($recipientType === 'admin')
                    A new job request has been submitted by {{ $jobRequest->client->company_name }}
                @else
                    Thank you for submitting your job request
                @endif
            </h2>
        </td>
    </tr>

    <tr>
        <td style="font-size: 14px; line-height: 1.6; color: #343a40;">
            <p><strong>Job Title:</strong> {{ $jobRequest->job_title }}</p>
            <p><strong>Description:</strong> {!! $jobRequest->description !!}</p>
            <p><strong>Requirements:</strong> {!! $jobRequest->requirements !!}</p>
            <p><strong>Vacancies:</strong> {{ $jobRequest->vacancies }}</p>

            {{-- Display the image if available --}}
            @if ($jobRequest->image)
                <p>
                    <strong>Flyer:</strong>
                   <a href="{{ url($jobRequest->image) }}" target="_blank">
                        flyer.{{ pathinfo($jobRequest->image, PATHINFO_EXTENSION) }}
                    </a>
                </p>
            @endif



            @if ($recipientType === 'admin')
                <p>Please review and publish this request as needed.</p>
            @else
                <p>Our team will review your request and get back to you shortly.</p>

                <!-- Optional button for client -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center" style="padding: 30px 0;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#007bff" style="border-radius: 5px;">
                                        <a href="{{ url('/client/login') }}" target="_blank"
                                           style="font-size: 16px; font-family: 'Roboto', sans-serif; color: #ffffff; text-decoration: none; padding: 12px 25px; display: inline-block;">
                                            Log In Now
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            @endif

            @if ($recipientType === 'client')
                <p class="ignore">If you did not submit this request, please ignore this email.</p>
            @endif
        </td>
    </tr>
@endsection
