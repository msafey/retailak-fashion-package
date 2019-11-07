<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>

</head>

<body>


<h1>Sales Report</h1>
<table>
    <thead>
    <tr>
        <th>Order No.</th>
        <th style="direction: rtl; text-align: right;">User Name</th>

    </tr>
    </thead>
    <tbody>
    @foreach(request()->invoice as $one)
        <tr>
            <td>{{ $one['order_id']}}</td>
            <td>{{ $one['user_name'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


</body>

</html>
