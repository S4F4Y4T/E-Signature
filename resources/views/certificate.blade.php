<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature Certificate</title>
    <style>
        /*@page { margin: 30px; }*/

        body {
            font-family: Arial, sans-serif;
            padding: 10px 35px;
            margin: 10px;
        }

        h1 {
            color: #333;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h2 {
            margin-bottom: 5px;
            color: #666;
        }

        .info-section p {
            margin-top: 5px;
        }

        .info-list {
            list-style-type: none;
            padding: 0;
        }

        .info-list li {
            margin-bottom: 5px;
        }

        .signData {
            background: #eeeeee;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signData::after {
            content: "";
            display: table;
            clear: both;
        }

        .signer,
        .signature {
            float: left;
            width: 50%;
        }

        .signer p,
        .signature p {
            margin: 0;
        }

        .signature {
            text-align: right;
        }

        .frame {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            border: 10px solid darkslateblue;
            border-radius: 15px;
        }

        .footerImg {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 350px
        }
    </style>
</head>

<body>
    <div class="frame"></div>

    <h1>Signature Certificate</h1>
    <div class="info-section">
        @if($data->completed_signed)
        <span>Document completed by all parties on {{ \Carbon\Carbon::parse($data->last_signed_time)->format('M j, Y g:i A') }}</span>
        <br>
        @endif
        <span>Document ID: {{ $data->short_url ?? "" }}</span>
    </div>

    <div class="info-section">
        <h4>Sender Information</h4>
        <span>Sent On: {{ $data->created_at ?? "" }}</span><br>
        <span>Timezone: {{ config('app.timezone') ?? "" }}</span><br>
        <span>Sender: {{ $data->sender_name ?? "" }} <a href="mailto:{{ $data->sender_name ?? "" }}">{{ $data->sender_email ?? "" }}</a></span><br>
        <span>Sender IP: {{ $data->sender_ip ?? "" }}</span>
    </div>

    <hr>

    @foreach ($data->signers as $signer)
    @if ($signer->signed === 1)
    <div class="info-section">

        <div class="signData">
            <div class="signer">
                <img class="signerImg" height="auto" width="100px" src="{{ resource_path('images/user.png') }}">
                <div class="signerContent">
                    <p><b>{{ @$signer->name }}</b></p>
                    <p>{{ @$signer->email }}</p>
                    <p>Received: {{ \Carbon\Carbon::parse($data->created_at)->format('M j, Y g:i A') }}</p>
                    <p>Viewed: {{ \Carbon\Carbon::parse($data->viewed_time)->format('M j, Y g:i A') }}</p>
                    <p>Signed: {{ \Carbon\Carbon::parse($data->signed_time)->format('M j, Y g:i A') }}</p>
                </div>
            </div>
            <div class="signature">

                <img style="max-height: 50%; max-width: 50%;"
                     src="{{ storage_path(config('documents.storage_paths.storage_signature') . basename(@$signer->signature)) }}">

                <p>IP Address: {{ @$signer->ip_address ?? "0.0.0.0" }}</p>
                @if(@$signer->location)
                <p>Location: {{ @$signer->location }}</p>
                @endif
            </div>
        </div>

    </div>
    @endif
    @endforeach
</body>

</html>
