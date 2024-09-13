<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Masauk Notification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .dark-mode body {
            background-color: #1a1a1d;
            color: #ddd;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .container {
            background-color: #2d2d33;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .dark-mode .header {
            border-bottom: 1px solid #444;
        }

        .header h1 {
            font-size: 24px;
            color: #0073e6;
        }

        .dark-mode .header h1 {
            color: #4ba3ff;
        }

        .content {
            padding: 20px 0;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0073e6;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .dark-mode .button {
            background-color: #4ba3ff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }

        .dark-mode .footer {
            color: #666;
        }
    </style>
</head>

<body class="light-mode">
    <div class="container">
        <div class="header">
            <h1>{{ $suratMasuk->perihal }}</h1>
        </div>
        <div class="content">
            <p>Hello [User],</p>
            <p>You have a new notification:</p>
            <p><strong>[Notification Message]</strong></p>
            <p>Click the button below to view more details:</p>
            <a href="#" class="button">View Notification</a>
        </div>
        <div class="footer">
            <p>If you did not expect this email, please ignore it.</p>
        </div>
    </div>
</body>

</html>
