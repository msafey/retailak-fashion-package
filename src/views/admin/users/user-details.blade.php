<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>

<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


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

                <div class="modal fade bs-example-modal-sm" id="statusModal" tabindex="-1" role="dialog"
                     aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirmation
                            </div>
                            <div class="modal-body">
                                Are you Sure That You Want To Activate This User ?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="statusItem" title="Hapus"><i
                                            class="glyphicon glyphicon-ok"></i>Activate User</a>

                            </div>
                        </div>

                    </div>
                </div>
                        



                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                        Users
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Users
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
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

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        <div class="row">
                            <div class="col-sm-12">
                                @if($user_activation == 0)
                                <div class="row">
                                    @if($user->active == 0)
                                    <button data-toggle='modal' data-target='#statusModal' onclick='statusItem("{{$user->id}}")' type='button' class='btn btn-primary' title='Activate User'><i class='fa fa-ok' data-toggle='tooltip' data-placement='top' title='' ></i>Activate</button>                        
                                    @else
                                    <button disabled="disabled" class="btn btn-success">Active User</button>        
                                    @endif
                                    
                                </div>
                                <hr>
                                @endif

                                <div class="row">
                                    <label>   {{ count($user->orders) }} : </label> عدد الطلبات
                                </div>

                                <hr>

                                <div class="row">
                                    <label>  {{$user->name}} : </label> اسم العميل
                                </div>

                                    <div class="row">
                                        <label>  {{$user->email}} : </label> البريد الألكترونى
                                    </div>

                                <hr>

                                <div class="row">
                                    <label>  {{$user_phone}} : </label> رقم التليفون
                                </div>
                                <hr>
                                <div class="row">
                                    <label >{{$ordersSum}} : </label>  المبلغ الكلى
                                </div>

                                                    @if(isset($store_details))
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label> المحل :</label><br>
                                                                    <?php
                                                                    $i = 1;
                                                                    ?>
                                                                        @if(isset($store_details->commercial_register))
                                                                        <label> : السجل التجاري  </label>
                                                                        <br>
                                                                              <img  style="width:133px;height: 120px;margin-bottom:0;" src="{{url('public/imgs/store_details/thumb/'.$store_details->commercial_register)}}" alt="">
                                                                              <br>
                                                                              <br>
                                                                        @endif
                                                                        
                                                                        @if(isset($store_details->tax_card))
                                                                        <label> : البطاقة الضريبية</label> <br>
                                                                             <img style="width:133px;height: 120px;" src="{{url('public/imgs/store_details/'.$store_details->tax_card)}}" alt=""> 
                                                                             <br>
                                                                             <br>
                                                                        @endif

                                                                            @if(isset($store_details->store_name))
                                                                             {{$store_details->store_name}}    <label> : اسم المحل</label> <br>
                                                                               <br>
                                                                            @endif     
                                                                            @if(isset($store_details->store_address))
                                                                             {{$store_details->store_address}}    <label> : عنوان المحل</label> <br>
                                                                               <br>
                                                                            @endif                                          
                                                                        @if(isset($store_type_name))
                                                                           
                                                                            {{$store_type_name}}<label> : عنوان المحل</label>  <br>
                                                                       @endif 


                                                                        
                                                                </div>
                                                            </div>
                                <hr>
                                @endif

                                <div class="row">
                                    <a href="{{url('/admin/users/'.$user->id.'/edit')}}" style="margin-left: 15px;"  class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit User' ></i>  Edit User</a>&nbsp;
                                </div>
                                <hr>
                              <!--   @if(isset($_GET['territory']))
                                <div class="row">
                                    <label >{{$_GET['territory']}} : </label>المنطقة الأكثر أستخداما
                                </div>
                                <hr>
                                @endif -->

                                <div class="row">
                                    <div class="col-sm-12">
                                        <label> العنوان :</label><br>
                                        <div style="margin-bottom: 10px;">
                                        <a href="{{url('/admin/address/create?id='.$user->id)}}" style="margin-left: 10px;" class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Address' ></i>  Add Address</a>&nbsp;
                                            
                                        </div>

                                        <?php
                                        $i = 1;
                                        $count = count($user->address);
                                        ?>
                                        <table class="table table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                <!-- <th>اسم العنوان</th> -->
                                                <!-- <th> رقم المبني</th> -->
                                                <th>الشارع</th>
                                                <!-- <th>الهاتف</th> -->
                                                <th>المنطقه</th>
                                                <!-- <th>المدينه</th> -->
                                                <!-- <th>رقم الشقه</th> -->
                                                <!-- <th>رقم الطابق</th> -->
                                                <th>تعديل</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->address as $address)

                                                <tr>

                                                   <!--  <td>@if(isset($address->title)){{$address->title}} @endif</td>
                                                 <td>@if(isset($address->building_no)){{$address->building_no}} @endif</td>              -->        
                                                 <td>@if(isset($address->street)){{$address->street}} @endif</td> 
<!--                                                  <td>@if(isset($address->address_phone)){{$address->address_phone}} @endif</td>                                                    
 -->                                                 <td>@if(isset($address->district_id) &&  $address->district_id !=0 && $districts[$address->district_id]){{$districts[$address->district_id]}} @endif</td>           
<!--                                                  <td>@if(isset($address->city)){{$address->city}} @endif</td>                                                    
 -->                                       
   <!-- <td>@if(isset($address->apartment_no)){{$address->apartment_no}} @endif</td>  -->
                                                 <!-- <td>@if(isset($address->floor_no)){{$address->floor_no}} @endif</td>  -->
                                                 <td><a href="{{url('/admin/address/'.$address->id.'/edit')}}" style="margin-left: 10px;" class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Address' ></i></a>&nbsp;</td>                                              
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                       <!--  @foreach($user->address as $address)
                                        @if(isset($address->title))
                                              : اسم العنوان <label>{{$address->title}}</label>
                                            <br>
                                        @endif
                                        @if(isset($address->building_no))
                                            : رقم المبني <label> {{$address->building_no}}</label> 
                                            <br>
                                        @endif
                                        @if(isset($address->street))
                                             : الشارع <label>{{$address->street}}</label>
                                            <br>
                                            @endif
                                            @if(isset($address->address_phone))
                                             : الهاتف <label>{{$address->address_phone}}</label>
                                            <br>
                                            @endif
                                            @if(isset($address->district_id) && $address->district_id !=0 && $districts[$address->district_id])

                                             :  المنطقه <label> {{$districts[$address->district_id]}} </label>
                                            <br>
                                            @endif
                                            @if(isset($address->city))

                                            :   المدينه <label>{{$address->city}}</label>
                                            <br>
                                            @endif
                                            @if(isset($address->apartment_no))
                                            :  رقم الشقه <label>{{$address->apartment_no}}</label>
                                            <br>
                                            @endif
                                            @if(isset($address->apartment_no))
                                             :  الطابق <label>{{$address->apartment_no}}</label> 
                                            @endif
                                            <br>
                                            @if(isset($address->id))
                                        <a href="{{url('/admin/address/'.$address->id.'/edit')}}" style="margin-left: 10px;" class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Address' ></i>  Edit Address</a>&nbsp;
                                            @endif
                                            @if($i <$count )
                                                <hr>
                                                <?php $i++ ?>
                                            @endif

                                        @endforeach

                                    </div>
                                    <hr> -->
                                    <div class="row">
                                       <!--  @if(isset($address->id))
                                        <a href="{{url('/admin/address/'.$address->id.'/edit')}}" style="margin-left: 10px;" class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Address' ></i>  Edit Address</a>&nbsp;
                                        @else
                                        <a href="{{url('/admin/address/create?email='.$user->email)}}" style="margin-left: 10px;" class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Address' ></i>  Add Address</a>&nbsp;
                                        @endif -->

                                    </div>

                                   
                                </div>
                            </div>
                        </div>
                        <hr>
         
                        <?php  $i = 0; ?>
                        @if(isset($user->orders))
                            @foreach($user->orders as $order)


                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive">
                                            <h4 class="m-t-0 header-title"><b>رقم الفاتوره </b>
                                                : {{str_replace('SO-', '', $order->id)}}</h4>
                                            <p class="">
                                                @if(isset($price[$i])){{$price[$i]}} @endif : السعر الكلي
                                            </p>
                                            @if(isset($order->time))
                                            <p class="">@if( isset($order->time->name))
                                                    {{$order->time->name}}
                                                            @endif
                                            وقت التسليم :
                                            </p>
                                            <p class="">
                                                @if( isset($order->time->from))
                                                {{$order->time->from}}  : من
                                                    @endif

                                            </p>
                                            <p class="">
                                                @if( isset($order->time->to))
                                                {{$order->time->to}}  : الي
                                                    @endif
                                            </p>
                                            @endif
                                            <table id="datatable" class="datatable table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>الاسم</th>
                                                    <th>الكميه</th>
                                                    <th>السعر</th>
                                                    <th>الأجمالي</th>
                                                </tr>
                                                </thead>


                                                <tbody>
                                                @if(isset($products[$i]))
                                                    @foreach($products[$i]  as $product)
                                                        <tr>
                                                            <td>@if(isset($product['name'])){{$product['name']}} @endif</td>
                                                            <td>@if(isset($product['qty'])){{$product['qty']}} @endif</td>
                                                            <td>@if(isset($product['standard_rate'])){{$product['standard_rate']}} @endif</td>
                                                            <td>@if(isset($product['total_price'])){{$product['total_price']}} @endif</td>
                                                        </tr>


                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div> <!-- end row -->
                                <?php $i++ ?>
                            @endforeach
                        @endif

                        <hr>


                        {{--User--}}


                    </div>
                </div>


            </div>
        </div>


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


<!-- jQuery  -->
<script src="{{url('public/admin/js/jquery.min.js')}}"></script>
<script src="{{url('public/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{url('public/admin/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/admin/js/detect.js')}}"></script>
<script src="{{url('public/admin/js/fastclick.js')}}"></script>
<script src="{{url('public/admin/js/jquery.blockUI.js')}}"></script>
<script src="{{url('public/admin/js/waves.js')}}"></script>
<script src="{{url('public/admin/js/jquery.nicescroll.js')}}"></script>
<script src="{{url('public/admin/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{url('public/admin/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('public/admin/plugins/switchery/switchery.min.js')}}"></script>

<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.datatable').DataTable();

        //Buttons examples
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        });

        table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    });

    <?php $statusurl = url('/admin/users/status'); ?>

    function statusItem(id) {

    $('#statusItem').one('click', function (e) {
                e.preventDefault();
                $.ajax({
            url: "{{$statusurl}}/" + id,
                    type: "GET",

                    success: function (data) {
                        if (data == 'true') {
                            $('#statusItem').modal('hide');
                            setTimeout(function () {// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                            }, 200);
                        } else {
                            alert('Error deleting data');
                        }
                    }
                           
                    
                    // error: function (jqXHR, textStatus, errorThrown) {
                        // alert('Error deleting data');
                    // }
                // });
            });
                return 'false';
            });
}
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
