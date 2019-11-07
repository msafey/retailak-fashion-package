<h1>Sales Report</h1>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Product</th>
        <th>Category</th>
        <th>Barcode</th>
        <th>Price</th>
        <th>Cost</th>
        <th>Qty</th>
    </tr>
    </thead>
    <tbody>
    @foreach(request()->sales as $one)
        <tr>
            <td>{{ $one->date}}</td>
            <td>{{ $one->produce_name }}</td>
            <td>{{ $one->category_name }}</td>
            <td>{{ $one->barcode }}</td>
            <td>{{ $one->price }}</td>
            <td>{{ $one->cost}}</td>
            <td>{{ $one->qty }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
