<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __("Historique des activités") }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .date {
            width: 20%;
        }
        .action {
            width: 20%;
        }
        .description {
            width: 60%;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>{{ __("Historique des activités") }}</h1>

    <table>
        <thead>
            <tr>
                <th class="date">{{ __("Date") }}</th>
                <th class="action">{{ __("Action") }}</th>
                <th class="description">{{ __("Description") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activites as $activite)
                <tr>
                    <td>{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($activite->action) }}</td>
                    <td>{{ $activite->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __("Document généré le") }} {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>