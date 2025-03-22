<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature</title>

    <style>

        body {
            background-color: transparent;
        }
        .frame{
            position: relative;
            display: inline-block;
            border: 3px solid blue;
            border-right: 0px;
            background-color: transparent;
            border-radius: 20px;
            padding: 10px;
        }

        .frame:before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            width: 100%;
            height: 15px;
            background-color: white;
            transform: translateY(-5px);
        }

        .frame:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            width: 100%;
            height: 15px;
            background-color: white;
            transform: translateY(5px);
        }

        .companyName{
            position: absolute;
            top: 0;
            left: 22%;
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            color: black;
            z-index: 555;
            transform: translateY(-10px);
        }
        .ip{
            position: absolute;
            bottom: 0;
            left: 22%;
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            color: black;
            z-index: 555;
            transform: translateY(10px);
        }
    </style>
</head>
<body>
    <div class="frame">
        <span class="companyName">Ecom.sign.co</span>
        <span class="ip">0.0.0.0</span>
                <img width="250px" height="80px" src="{{ storage_path('app/public/signatures/66312602e1161.png') }}" >
{{--        <img width="250px" height="80px" src="http://127.0.0.1:8000/storage/signatures/6635df1862572.png" >--}}
    </div>
</body>
</html>
