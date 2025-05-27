<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title : 'Document PDF' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 20px;
        }
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 10px;
        }
        h2 {
            color: #4a5568;
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }        h3 {
            color: #4a5568;
            font-size: 18px;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .info-grid {
            display: block;
            margin-bottom: 15px;
        }
        .info-grid > div {
            margin-bottom: 8px;
        }
        strong {
            color: #2d3748;
        }
        .status-text {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
        }
        .status-text.green {
            background-color: #48bb78;
            color: white;
        }
        .status-text.red {
            background-color: #f56565;
            color: white;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
            padding-left: 0;
        }
        li {
            margin-bottom: 5px;
        }
        .recommendations {
            background-color: #f7fafc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .financial {
            background-color: #f0fff4;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
