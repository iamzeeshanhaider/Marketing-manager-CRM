<!DOCTYPE html>
<html>

<head>
    <title>Wariz Training Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
        }

        .course-features {
            margin-bottom: 20px;
        }

        .course-features li {
            margin-bottom: 10px;
        }

        .meeting-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            padding: 10px 20px;
            background: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>{{ $details['subject'] }}</h2>
        </div>
        <div class="content">
            <p>Dear</p>

            <p>I hope this email finds you well.</p>

            <p>{{ $details['body'] }}</p>


        </div>

        <p>Best regards,</p>
        <p>Wariz Training<br>
            Training Consultant<br>
            +44 20 4576 3938</p>

        <a href="{{ $details['link'] }}" class="meeting-link">Click here to join the meeting</a>
        <p class="footer">Meeting Time: {{ $details['date'] }}</p>
    </div>
</body>

</html>
