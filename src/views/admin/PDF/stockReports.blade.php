<h1>Sales Report</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Code</th>
        <th>Standard Rate</th>
        <th>Warehouse</th>
        <th>Stock Qty</th>
        <th>Color</th>
        <th>Size</th>
        <th>Category Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach(request()->productArray as $one)
        <tr>
            <td>{{ $one['id']}}</td>
            <td>{{ $one['name'] }}</td>
            <td>{{ $one['code'] }}</td>
            <td>{{ $one['rate'] }}</td>
            <td>{{ $one['warehouse'] }}</td>
            <td>{{ $one['qty'] }}</td>
            <td>{{ $one['color']}}</td>
            <td>{{ $one['size'] }}</td>
            <td>{{ $one['category'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
