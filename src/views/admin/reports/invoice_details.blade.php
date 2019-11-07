<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
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
                        Order No. {{$items[0]->order_id}} Invoice
                @endslot

            @endcomponent            <!--End Bread Crumb And Title Section -->
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="row m-0">

                    <table class="table" style="background: white;">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Item</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">rate</th>
                            <th scope="col">Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php $i = 1;?>
                        @foreach($items as $item)

                            <tr id="field{{$i}}">
                                <th scope="row">{{$item->id}}</th>

                                <td>
                                    {{$item->item_name}}
                                </td>
                                <td>
                                    {{$item->qty}}
                                </td>
                                <td>
                                    {{$item->rate}}
                                </td>
                                <td>
                                    {{$item->qty * $item->rate}}
                                </td>
                            </tr>
                            <?php $i++;?>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>


</div>


<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

<!-- End Footer Area-->
<!-- END wrapper -->


<!-- JAVASCRIPT AREA -->

@include('layouts.admin.javascript')

<!-- JAVASCRIPT AREA -->
</body>
</html>
