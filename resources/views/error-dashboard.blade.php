<!DOCTYPE html>
<html>

<head>

    <title>Error Dashboard</title>

    <style>
        body {

            font-family: Arial;

            background: #f4f6f9;

            margin: 0;

            padding: 40px;

        }

        h1 {

            text-align: center;

            margin-bottom: 40px;

        }

        .cards {

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 40px;

        }

        .card {

            background: white;

            padding: 25px;

            border-radius: 10px;

            text-align: center;

            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);

        }

        .number {

            font-size: 40px;

            font-weight: bold;

            margin-top: 10px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);

        }

        th {

            background: #343a40;

            color: white;

            padding: 15px;

        }

        td {

            padding: 12px;

            border-bottom: 1px solid #eee;

            text-align: center;

        }

        tr:hover {

            background: #fafafa;

        }
    </style>

</head>

<body>

    <h1>📊 Error Analytics Dashboard</h1>

    <div class="cards">

        <div class="card">

            <h3>Total Errors</h3>

            <div class="number">{{ $totalErrors }}</div>

        </div>

        <div class="card">

            <h3>404 Errors</h3>

            <div class="number">{{ $error404 }}</div>

        </div>

        <div class="card">

            <h3>403 Errors</h3>

            <div class="number">{{ $error403 }}</div>

        </div>

        <div class="card">

            <h3>500 Errors</h3>

            <div class="number">{{ $error500 }}</div>

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th>ID</th>

                <th>Status</th>

                <th>URL</th>

                <th>IP</th>

                <th>Visited At</th>

            </tr>

        </thead>

        <tbody>

            @foreach($latestErrors as $error)

            <tr>

                <td>{{ $error->id }}</td>

                <td>{{ $error->status_code }}</td>

                <td>{{ $error->url }}</td>

                <td>{{ $error->ip_address }}</td>

                <td>{{ $error->visited_at }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>