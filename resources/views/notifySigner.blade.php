<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Signature</title>
    <style>
        /* CSS for centering elements */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .logo {
            margin-bottom: 20px;
        }
        .otp-box {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            display: inline-block;
        }
        .heading {
            font-size: 18px;
            font-weight: bold;
            margin-top: 35px;
        }
        .text {
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <!-- Content -->
        <div class="content">

            <!-- Text -->
            <p class="text">You have been invited to sign a document. Please visit the below url.</p>

            <!-- OTP Box -->
            <div class="otp-box">
                <strong>{{ $url ?? "" }}</strong>
            </div>

            <div class="text" style="margin-top:35px">Best,</div>
            <div class="text">E-signature Team</div>
        </div>
    </div>
</div>
</body>
</html>
