<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>


    <style>

        .table td:nth-of-type(3) {
            width: 87px;

        }

        .table td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;

        }

        .table tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }

        .table2 td:nth-of-type(2) {
            width: 200px;
        }

        .table2 td:nth-of-type(3) {
            width: 170px;

        }

        .table2 td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;


        }

        .table2 tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
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
                        Run Sheets
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Run Sheets
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/runsheet', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        {{--Orders--}}
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Choose Courier:<span style="color:red;">*</span>
                                </label>

                                <select name="delivery_man" class="select2 form-control select2-multiple"
                                        id="dropdown" id="multiple"
                                        data-placeholder="Choose Orders ...">

                                    @foreach($delivery_man as $delivery)
                                        <option value="{{$delivery->id}}">{{$delivery->name}}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                        </div>

                        {{--Choose Time--}}
                        <div class="row" >

                            <label style="margin-left: 12px" class="form-group" for="gender">Time Section
                            </label> <br>
                            <div class="col-sm-4">
                                <div class="radio radio-info radio-inline">
                                    <input data-parsley-group="block1" type="radio" id="inlineRadio1" value="1"
                                           name="time" >
                                    <label for="inlineRadio1"> Now </label>
                                </div>
                                <div class="radio radio-inline">
                                    <input hidden data-parsley-group="block2" type="radio" id="inlineRadio2"
                                           name="time" value="2">
                                    <label for="inlineRadio2"> Choose Time </label>
                                </div>
                            </div>
                        </div>

                        {{--Hidden Time Section--}}
                        <div id="demo" class="row" style="margin-left: 10px;">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="time_section">Time Section:<span style="color:red;">*</span>
                                    </label>

                                    <select name="time_section" class="select2 form-control select2-multiple"
                                            id="dropdown3" id="multiple"
                                            data-placeholder="Choose Time ...">
                                            <option value="" disabled></option>
                                        @foreach($timesection as $time)
                                            <option value="{{$time->id}}">From {{$time->from}} To {{$time->to}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <div class="input-group">
                                        <input  type="text" autocomplete="off" name="date" class="form-control"
                                               placeholder="mm/dd/yyyy"
                                               id="datepicker-autoclose">
                                        <span class="input-group-addon bg-custom b-0"><i
                                                    class="icon-calender"></i></span>
                                    </div><!-- input-group -->
                                </div>
                            </div>
                        </div>
                        <hr>    
                        <div class="row">   
        <label for="" style="margin-left: 10px;"><b><u>Filter Orders By:</u></b></label>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-4"><label for="crewdateofbirth">From Date <span style="color:red;"></span>:</label>
                            <input  class="form-control" type="text" name="date_from" autocomplete="off" placeholder="dd/mm/yyyy" id="crewdateofbirth" />
                        </div>
                            <div class="form-group col-sm-4">
                                <label for="crewdateofjoining">To Date<span style="color:red;"></span>:</label>
                            <input   class="form-control" type="text" autocomplete="off" name="date_to" placeholder="dd/mm/yyyy" id="crewdateofjoining" />
                        </div>
                        </div>

                      




                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Order Id<span style="color:red;">*</span>:
                                </label>
                                    <input type="number" name="order_id" id="order_id" class="form-control order_filter">

                                      
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Choose District<span style="color:red;">*</span>:
                                </label>
                                <select  name="district_id"  id="district_id" class="form-control  " style="">
                                 <option value="0" disabled data-foo="Select Email" selected>Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{$district->id}}">{{$district->district_en}}</option>
                                        @endforeach
                                 
                                 </select>
                            </div>
                        </div>


                      

<hr>    
<input type="text" hidden="" name="orders[]" id="orders_id">
                        <div class="row">
                           
                        </div>




                     


                        <div class="row">


                        </div>

                        <div class="row">

                            <div class="col-md-8">
                                <input type="hidden" id="delivery_time_left" value="@if(isset($_GET['delivery_time_left'])){{$_GET['delivery_time_left']}}@else 0 @endif" name="delivery_time_left">


                                <label for="time_section"> Orders<span style="color:red;">*</span>:
                                </label>
                                 <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Address</th>
                                    <th>Count Of Products</th>
                                    <th>Select</th>
                                </tr>
                                </thead>


                                </table>

                     
                            </script>

                         

                        </div>
                        <div class="col-md-4">


                            <label for="time_section"> Selected Orders:<span style="color:red;">*</span>
                            </label>

                                 <table id="colItemsTable" class="display" cellspacing="0" border="0" width="100%">
                                <thead><tr style=""><th>Order Id</th><th>Remove</th></tr></thead>
                                <tbody id="sortable" class="sortable">

                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="row">
                            <button style="margin-left: 10px" type="submit" class="btn btn-primary save"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Add Order
                            </button>
                        </div>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>


    </div>


</div>


</div>


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

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>

<script src="{{url('/public/')}}/prasley/parsley.js"></script>


<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

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


<script type="text/javascript">
    $("#crewgender").select2();
    $('#crewdateofbirth').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $('#crewdateofjoining').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>


<script type="text/javascript">
    // Date Picker

    $(document).ready(function(){
// console.log(district_id);
       var district_id= $('#district_id').val();
       if (district_id == null){
            $('#district_id').val(0);
       }
     
 var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
            url:'{!! route('DelOrdersList') !!}',
            type:"GET",
            data: function(d){
                d.order_id=document.getElementById('order_id').value,
                d.delivery_time_left=document.getElementById('delivery_time_left').value,
                d.district_id = document.getElementById('district_id').value;
            }
            
            },
            columns: [

                {data: 'id', name: 'id'},
                {data: 'address', name: 'address'},
                {data: 'count_of_orders', name: 'count_of_orders'},
                {data: 'id', searchable: false, render: function (data,data2, row) {
                    var itemcodes = [];
                     $('#sortable tr').each(function() {
                       itemcodes.push(this.id)
                      });

                     if($.inArray(row.id,itemcodes) == -1)
                     {
                         return '<button  id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToSelected(\''+ row.id+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                     }
                     else
                     {
                       return '<button  disabled=disabled id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToSelected(\''+ row.id+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                     }

                    }
                }

                
            ]
        });

 $(document).on('input', '#order_id', function(e){
  //7 seconds in milliseconds
      if($('#order_id').val() == ''){
             table.ajax.reload();
      }
      table.ajax.reload();

  });

 $('select').change(function () {
    district_id= document.getElementById("district_id").value;

    table.ajax.reload();

   });



    });

    
function addToSelected(order_id,btnId){
    $('#sortable').append('<tr  style="border: 1px solid #dddddd;" id="'+order_id+'"><td>'+order_id+'</td><td><button value="'+btnId+'" class="btn deleteBtn btn-sm btn-danger"><i class="zmdi zmdi-delete"></i></button></td></tr>');
}






 $(document).on('click','.deleteBtn',function(e){
     var addBtnId = $(this).val();
      var itemId = $(this).closest('tr').attr('id');
      $("#"+addBtnId).removeAttr('disabled');
      $(this).closest('tr').remove();
      e.preventDefault();
});

$(document).on('click','.save',function(e){
   var orders = [];
     $('#sortable tr').each(function() {
       orders.push(this.id)
     });

        $('input:hidden[name=orders\\[\\]]').val(orders);
});


    $(document).on('click','.addBtn',function(){
        $(this).attr('disabled','disabled');
    });


    jQuery('#datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>


<!-- Laravel Javascript Validation -->
<script>

      
</script>

<script type="text/javascript">
    $("#dropdown").select2();
</script>

<script type="text/javascript">
    $(".dropdown2").select2();
</script>


<script type="text/javascript">
    $(".dropdown3").select2();
</script>

<script>
    $(document).ready(function () {
        $('#inlineRadio2').attr('checked','true');
        $("#demo").show();

        $("#inlineRadio2").click(function () {
            $("#demo").show();
        });
        $("#inlineRadio1").click(function () {
            $("#demo").hide();
        });

    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>