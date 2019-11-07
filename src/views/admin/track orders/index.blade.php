<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->

  <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css"/>
  <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css"/>
  <link rel="stylesheet" type="text/css" href="{{ url('public/css/lightslider.css') }}"/>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <style>
    body{
      counter-reset: step;
    }
    #progressbar {
      margin-bottom: 30px;
      /*CSS counters to number the steps*/
      overflow: hidden;
    }
    #progressbar li {
      list-style-type: none;
      color: #2b3d51;
      text-transform: uppercase;
      font-size: 14px;
      margin-top: 15px;
      line-height: 3;
      text-align: center;
      width: 25%;
      float: left;
      position: relative;
    }
    #progressbar li:before {
      counter-increment: step;
      content: counter(step) ;
      width: 50px;
      height: 50px;
      line-height: 3;
      display: block;
      font-size: 16px;
      color: #333;
      background: white;
      border-radius: 50%;
      margin: -15px auto 5px auto;
    }
    /*progressbar connectors*/
    #progressbar li:after {
      content: '';
      width: 100%;
      height: 2px;
      background: white;
      position: absolute;
      left: -50%;
      top: 9px;
      z-index: -1; /*put it behind the numbers*/
    }
    #progressbar li:first-child:after {
      /*connector not needed before the first step*/
      content: none;
    }
    /*marking active/completed steps green*/
    /*The number of the step and the connector before it = green*/
    #progressbar li.Delivered:before,  #progressbar li.Delivered:after{
      background: #81C784;
      color: white;
    }
    #progressbar li.Wait:before,  #progressbar li.Wait:after{
      background: #FFB74D;
      color: white;
    }

    #progressbar li.Canceled:before,  #progressbar li.Canceled:after{
      background: #F06292;
      color: white;
    }

    #progressbar li.Added:before,  #progressbar li.Added:after{
      background: #C5E1A5;
      color: white;
    }

    #progressbar li.ReadyToShip:before,  #progressbar li.ReadyToShip:after{
      background: #80DEEA;
      color: white;
    }

    #progressbar li.OnHold:before,  #progressbar li.OnHold:after{
      background: #90A4AE;
      color: white;
    }
    #progressbar li.Confirmed:before,  #progressbar li.Confirmed:after{
      background: #4CAF50;
      color: white;
    }
    .lSNext , .lSPrev
    {
      border: 1px solid #e6e6e6;
      opacity: 1 !important;
      content: "";
      background-image: none;
      background-color: #fff;
    }

    .lSPrev:after
    {
      content: "<";
      position: absolute;
      font-size: 34px;
      top: -18px;
      padding: 7px;
      color: #2b3d516b;
    }
    .lSNext:after
    {
      content: ">";
      position: absolute;
      font-size: 34px;
      top: -18px;
      padding: 7px;
      color: #2b3d516b;
    }
    .lSPager
    {
      display: none;
    }
  </style>
</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
  <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
  <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')
<!-- Left Sidebar End -->


  <!-- Start right Content here -->
  <div class="content-page">
    <!-- Start content -->
    <div class="content">
      <div class="container">
        <!-- Bread Crumb And Title Section -->
        @component('layouts.admin.breadcrumb')
          @slot('title')
            Tracking Orders
          @endslot

          @slot('slot1')
            Home
          @endslot

          @slot('current')
            track orders
          @endslot
          You are not allowed to access this resource!
      @endcomponent            <!--End Bread Crumb And Title Section -->
        <div class="row">
          {{--Search Order Bar--}}
            <div class="col-md-12 col-lg-8">
              <div class="col-xs-3"><p class="label" style="color: #383636;font-weight: bold;">Order ID</p></div>
              <div class="col-xs-6">
                <select type="text" name="order"  class="form-control order-select2">
                  <option value="null" selected disabled>select ID of order</option>
                  @foreach($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->id }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-dark" onclick="order_status()">Submit</button>
              </div>
            </div>
        </div>
        <hr style="margin-top: 25px; border-color: #2b3d510f; width: 70%;">

        <div class="row">
          <ul style="list-style-type: none;">
            <li class="list-group-item" style="margin-bottom: 2px;">Order ID: <span class="order_id_info">...... </span><a class="order_link" href="" style="float: right; text-decoration: underline;">View Order Details</a></li>
            <li class="list-group-item" style="margin-bottom: 2px;">Customer: <span class="user_name_info"> ... </span></li>
            <li class="list-group-item" style="margin-bottom: 2px;">Order Creating Date: <span class="order_date_info"> .... </span></li>
            <input type="hidden" value="{{ url('/admin/sales-details/') }}" class="order_details">
          </ul>
        </div>

        <hr style="margin-top: 25px; border-color: #2b3d510f; width: 70%;">
        {{--Tracking Orders--}}
        <div class="row">
          <!-- progressbar -->
          <ul id="progressbar" style="width: 100%;" >
          </ul>
        </div>


      </div> <!-- container -->
    </div> <!-- content -->
  </div>
  <!-- End content-page -->
  <!-- Footer Area -->
@include('layouts.admin.footer')
<!-- End Footer Area-->
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="{{ url('public/js/lightslider.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<!-- JAVASCRIPT AREA -->
<script type="text/javascript">
    $(document).ready(function() {
        // select 2
        $('.order-select2').select2();

    });
    // get order status
    function order_status()
    {
        // get select 2 value
        id = $('.order-select2').val();
        if(id != null)
        {
            $.ajax({
                url: '{{ route("order_status") }}',
                type: "POST",
                data: {
                    "_token": "{{ csrf_token()  }}",
                    "id": id
                },
                success: function (response) {
                    $("#progressbar").html('');
                    if(response)
                    {
                        $('.order_id_info').html(''+response['id']);
                        $('.order_date_info').html(''+response['date']);
                        $('.user_name_info').html(''+response['name']);

                        $('.order_link').attr("href", $('.order_details').val() + '/' +id);

                        $("#progressbar").html('');
                        data = '';
                        for (i = 0; i < response[0].length; ++i) {
                            // do something with `substr[i]`
                            data = data +  "<li class='activetab "+response[0][i]['status']+"'>"+response[0][i]['status']+"<br>"+response[0][i]['created_at']+"</li>";
                        }
                        $("#progressbar").prepend(data);
                        $('#progressbar').lightSlider({
                            item: 4 ,
                            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                            speed:2200,
                            pauseOnHover: true,

                        });
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });

        }
    }

</script>
</body>
</html>