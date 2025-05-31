<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ env('APP_NAME') }} Email</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    @media only screen and (max-width: 620px) {
      .container {
        width: 100% !important;
        padding: 10px !important;
      }
      .content-wrap {
        padding: 20px !important;
      }
      .button {
        width: 100% !important;
        display: block !important;
      }
      .ignore {
        justify-content: center !important;
        text-align: center !important;
      }
    }
  </style>
</head>
<body style="margin: 0; font-family: 'Roboto', sans-serif; background-color: #f2f4f6;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: transparent;">
    <tr>
      <td></td>
      <td class="container" width="600" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div class="content" style="background: #fff; border-radius: 7px; box-shadow: 0 3px 15px rgba(30,32,37,.06); padding: 30px;">
          <table width="100%">
            <tr>
                <td align="center" style="padding: 20px 0; background-color: #007bff;">
                    <img src="{{ $companyLogo }}" alt="Company Logo" width="150" style="display: block;">
                </td>
            </tr>


            @yield('content')

          </table>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #98a6ad;">
          <p style="margin: 0;">&copy; {{ date('Y') }} {{ env('APP_NAME') }}</p>
        </div>
      </td>
      <td></td>
    </tr>
  </table>

</body>
</html>
