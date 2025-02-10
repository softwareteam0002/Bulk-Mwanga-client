<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #E4792F;
            color: white;
            padding: 15px;
            font-size: 18px;
            text-align: left;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            background: #00A5D8;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .reset-text{
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    @switch($type)
        @case(1)
            <div class="header">Login Token</div>
            <div class="content">Your Token for login into Mwanga Hakika Disbursement Portal is
                <strong>{{$token}}</strong>.
            </div>
            @break

        @case(2)
            <div class="header">Credential</div>
            <div class="content">Your access credential for Mwanga Hakika Disbursement Portal Account is <strong>{{$token}}</strong>.</div>
            @break

        @case(3)
            <div class="header">Payment Reset</div>
            <div class="content">Your account credentials have been successfully updated. Please find your new login credentials: <strong>{{$token}}</strong>
            </div>
            @break

        @case(4)
            <div class="header">Payment Notification</div>
            <div class="content">You have a new batch with number <strong>{{$token}}</strong> that needs your action.
                Visit the system for further details.
            </div>
            @break

        @case(5)
            <div class="header">TQS Exception</div>
            <div class="content">Hello Team,<br> Kindly check the below exception that occurred while processing
                TQS:<br><strong>{{$token}}</strong>.
            </div>
            @break

        @case(6)
            <div class="header">Biller Not Whitelisted</div>
            <div class="content">Hello Team,<br> Kindly assist in whitelisting <strong>{{$token}}</strong> in the B2C
                Portal to proceed with TQS.
            </div>
            @break

        @case(7)
            <div class="header">Pending Transactions</div>
            <div class="content">Hello Team,<br> Please find the attached file with transactions that failed to update
                status on the B2C Portal as of <strong>{{$token}}</strong>. Kindly assist in clearing these
                transactions.
            </div>
            @break

        @case(8)
            <div class="header">Password Reset Link</div>
            <div class="content">We received a request to reset your password. Click the button below to proceed:</div>
            <div style="text-align: center;">
                <a href="{{$token}}" class="button"><span class="reset-text">Reset Password</span></a>
            </div>
            <div class="content">The link is valid for 30 minutes. If expired, request another one.</div>
            <div class="footer">If you did not request this, contact support at
                <strong>Mwanga Hakika Bank</strong>.
            </div>
            @break

        @case(9)
            <div class="header">Disbursement {{$token}} Report</div>
            <div class="content">Please find the attached Disbursement report.</div>
            @break
    @endswitch
</div>
</body>
</html>
