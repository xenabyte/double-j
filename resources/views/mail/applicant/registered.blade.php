@extends('mail.layout.mail')
@section('content')
    <tr>
        <td class="content-block" style="padding-top: 30px; padding-bottom: 10px;">
            <h2 style="text-align: center; color: #007bff; font-size: 22px;">Welcome To Double J HR!</h2>
        </td>
    </tr>
    <tr>
        <td style="font-size: 14px; line-height: 1.6; color: #343a40;">
            <p>Thank you for registering on our platform. We're excited to have you onboard.</p>
            <p>Please complete your biodata so we can match you with relevant job opportunities.</p>

            <!-- Button block -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="padding: 30px 0;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" bgcolor="#007bff" style="border-radius: 5px;">
                                    <a href="{{ url('/applicant/login') }}" target="_blank"
                                    style="font-size: 16px; font-family: 'Roboto', sans-serif; color: #ffffff; text-decoration: none; padding: 12px 25px; display: inline-block;">
                                    Log In Now
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p class="ignore">If you did not register, please ignore this email.</p>
        </td>
    </tr>
@endsection
