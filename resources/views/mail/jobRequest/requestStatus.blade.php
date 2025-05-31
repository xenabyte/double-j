@extends('mail.layout.mail')

@section('content')
    <tr>
        <td class="content-block" style="padding-top: 30px; padding-bottom: 10px;">
            <h2 style="text-align: center; color: #007bff; font-size: 22px;">
                @if ($status === 'approved')
                    🎉 Your Job Request Has Been Approved!
                @elseif ($status === 'rejected')
                    ❌ Your Job Request Was Rejected
                @elseif ($status === 'pending')
                    ⏳ Your Job Request Status is Pending
                @else
                    📨 Job Request Status Update
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

            @if ($jobRequest->image)
                <p>
                    <strong>Flyer:</strong>
                    <a href="{{ url($jobRequest->image) }}" target="_blank">
                        flyer.{{ pathinfo($jobRequest->image, PATHINFO_EXTENSION) }}
                    </a>
                </p>
            @endif

            @if ($status === 'approved')
                <p>Your job request has been approved and is now visible to potential applicants.</p>
            @elseif ($status === 'rejected')
                <p>We're sorry, but your job request did not meet our approval criteria.</p>
            @elseif ($status === 'pending')
                <p>Your job request is under review. We’ll notify you once a decision is made.</p>
            @endif

            <!-- Optional login button -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="padding: 30px 0;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" bgcolor="#007bff" style="border-radius: 5px;">
                                    <a href="{{ url('/client/login') }}" target="_blank"
                                       style="font-size: 16px; font-family: 'Roboto', sans-serif; color: #ffffff; text-decoration: none; padding: 12px 25px; display: inline-block;">
                                        Log In to View
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p class="ignore">If you did not submit this job request, please ignore this email.</p>
        </td>
    </tr>
@endsection
