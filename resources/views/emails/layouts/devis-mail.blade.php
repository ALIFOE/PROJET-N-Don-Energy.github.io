<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a56db;
        }
        h1 {
            color: #111827;
            font-size: 24px;
            margin-bottom: 20px;
        }
        h2 {
            color: #374151;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        p {
            color: #4b5563;
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            color: #4b5563;
            margin-bottom: 10px;
        }
        .btn-primary {
            display: inline-block;
            background-color: #1a56db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn-primary:hover {
            background-color: #1e40af;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">CREFER</div>
        </div>

        @yield('content')

        <div class="footer">
            © {{ date('Y') }} CREFER. Tous droits réservés.
        </div>
    </div>
</body>
</html>
