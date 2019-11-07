<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Retailak Email</title>
  <style type="text/css" media="screen">

    /* Force Hotmail to display emails at full width */
    .ExternalClass {
      display: block !important;
      width: 100%;
    }

    /* Force Hotmail to display normal line spacing */
    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
      line-height: 100%;
    }

    body,
    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      margin: 0;
      padding: 0;
    }

    body,
    p,
    td {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 15px;
      color: #333333;
      line-height: 1.5em;
    }

    h1 {
      font-size: 24px;
      font-weight: normal;
      line-height: 24px;
    }

    body,
    p {
      margin-bottom: 0;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
    }

    img {
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    a img {
      border: none;
    }

    .background {
      background-color: #333333;
    }

    table.background {
      margin: 0;
      padding: 0;
      width: 100% !important;
    }

    .block-img {
      display: block;
      line-height: 0;
    }

    a {
      color: white;
      text-decoration: none;
    }

    a,
    a:link {
      color: #2A5DB0;
      text-decoration: underline;
    }

    table td {
    }

    td {
      vertical-align: top;
      text-align: left;
    }

    .wrap {
      width: 600px;
    }

    .wrap-cell {
      padding-top: 30px;
      padding-bottom: 30px;
    }

    .header-cell,
    .body-cell,
    .footer-cell {
      padding-left: 20px;
      padding-right: 20px;
    }

    .header-cell {
      background-color: #eeeeee;
      font-size: 24px;
      color: #ffffff;
    }

    .body-cell {
      background-color: #ffffff;
      padding-top: 30px;
      padding-bottom: 34px;
    }

    .footer-cell {
      background-color: #eeeeee;
      text-align: center;
      font-size: 13px;
      padding-top: 30px;
      padding-bottom: 30px;
    }

    .card {
      width: 400px;
      margin: 0 auto;
    }

    .data-heading {
      text-align: right;
      padding: 10px;
      background-color: #ffffff;
      font-weight: bold;
    }

    .data-value {
      text-align: left;
      padding: 10px;
      background-color: #ffffff;
    }

    .force-full-width {
      width: 100% !important;
    }
    table {
      border-collapse: collapse;
    }
    table td, table th {
      border: 1px solid black;
    }
    table tr:first-child th {
      border-top: 0;
    }
    table tr:last-child td {
      border-bottom: 0;
    }
    table tr td:first-child,
    table tr th:first-child {
      border-left: 0;
    }
    table tr td:last-child,
    table tr th:last-child {
      border-right: 0;
    }

  </style>
  <style type="text/css" media="only screen and (max-width: 600px)">
    @media only screen and (max-width: 600px) {
      body[class*="background"],
      table[class*="background"],
      td[class*="background"] {
        background: #eeeeee !important;
      }

      table[class="card"] {
        width: auto !important;
      }

      td[class="data-heading"],
      td[class="data-value"] {
        display: block !important;
      }

      td[class="data-heading"] {
        text-align: left !important;
        padding: 10px 10px 0;
      }

      table[class="wrap"] {
        width: 100% !important;
      }

      td[class="wrap-cell"] {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
      }
    }
    table {
      border-collapse: collapse;
    }
    table td, table th {
      border: 1px solid black;
      text-align: center;
    }
    table tr:first-child th {
      border-top: 0;
    }
    table tr:last-child td {
      border-bottom: 0;
    }
    table tr td:first-child,
    table tr th:first-child {
      border-left: 0;
    }
    table tr td:last-child,
    table tr th:last-child {
      border-right: 0;
    }
  </style>
</head>
<body>
  <div style="width: 100%;">
    <img src="http://khotwh.com/assets/img/logo-last2.png" style="margin: 50px 0px 100px 350px; padding: 20px;">
    <p>
      @if(isset($body['name']))
      <b>Hello   {{$body['name']}} ,</b>
      @endif
    </p>

    <p style="margin-top: 10px; color: #9f9b9a !important;" >
      @if(isset($body['number']))
      Your order number <b style="margin-left: 5px; margin-right: 5px; color: #000;font-weight: 600;">{{$body['number']  }}</b>
      @endif
        @if(isset($body['status']))
      has been changed to <b style="margin-left: 5px; margin-right: 5px; color: #000;font-weight: 600;">{{ $body['status']  }}</b> ,
        @endif
      to view your order click on the Button Below
    </p>
  </div>
  <br/>

  <table style="border: 1px solid #000;width: 100%;text-align: center;">
    <tr>
      <th>Product ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Quantity</th>
    </tr>
    @foreach($items as $item)
      <tr>
        <td>{{ $item->item_id }}</td>
        @if(isset($item->image[0]))<td> <img src="{{ $item->image[0] }}" style="width: 50px" height="50px"></td>@endif
        <td>{{ $item->item_name }}</td>
        <td>{{ $item->qty }}</td>
      </tr>
    @endforeach
  </table>

  <br>
  <p>
    <a style="background-color: #2b3d51;
    margin: 25px auto;
    color: #fff !important;
    padding: 8px;
    text-decoration: none;" href="http://khotwh.com/dashboard/my-orders/{{$body['number']}}">View Order Details</a>
  </p>
</body>
</html>
