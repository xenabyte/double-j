@extends('mail.layout.mail')

@section('content')
<tr>
    <td class="content-block" style="padding-top: 30px; padding-bottom: 10px;">
        <h2 style="text-align: center; color: #007bff; font-size: 22px;">Welcome Aboard!</h2>
    </td>
</tr>
<tr>
    <td style="font-size: 14px; line-height: 1.6; color: #343a40;">
        <p>Hello {{ $employee->title }} {{ $employee->last_name }},</p>
        <p>Congratulations! You have been successfully onboarded to our system as an employee for the role of 
            <strong>{{ $employee->jobPosting->title ?? 'your new position' }}</strong>.
        </p>
        <p>Please click the button below to set up your account password and complete your profile.</p>

        <!-- Button block -->
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" style="padding: 30px 0;">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center" bgcolor="#007bff" style="border-radius: 5px;">
                                <a href="{{ $resetLink }}" target="_blank"
                                   style="font-size: 16px; font-family: 'Roboto', sans-serif; color: #ffffff; text-decoration: none; padding: 12px 25px; display: inline-block;">
                                    Set Password
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <p class="ignore">If you did not expect this email, please ignore it.</p>
    </td>
</tr>
@endsection
