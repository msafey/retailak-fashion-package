    <!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
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
                        Products Profit Report
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Product
                @endslot
                You are not allowed to access this resource!
                @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                      <div class="card-title">
                        <div class="row">
                        <div class="col-sm-3">
                            <label for="">Filter By Product Id</label>
                            <button type="button" class="btn btn-sm btn-primary" id="search_product_id"><i class="fa fa-search"></i></button>
                            <input type="number" value="" name="product_id" class="form-control" id="product_id">
                        </div>
                        </div>
                <!-- Add Company Button -->

            </div>

                        <div class="card-text">
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Cost</th>
                                    <th>Profit</th>
                                </tr>
                                </thead>


                            </table>


                            <!-- Table End-->
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

<script>

 // <?php $editurl = url('/admin/brands/');?>
 // <?php $deleteurl = url('/admin/brands/delete'); ?>
               var district_id= 0;


 var table = $('#items_datatable').DataTable({
     lengthMenu: [ 10, 25, 50, 75, 100, 500],
     processing: true,
        serverSide: true,
        ajax:{
        url:'{!! route('productsProfitList') !!}',
        type:"GET",
        data: function(d){
            d.product_id=document.getElementById('product_id').value
        }
        },
        columns: [
        {
            data: 'name_en', render: function (name1, name2, type, row) {
            if(name1.length > 40){
                name1 = name1.substr(0,38)+'...</span>';
            }
            if(type.name.length >40){
                type.name = type.name.substr( 0, 38 )+'...</span>';
            }
            return name1 ;

        }},
        {data: 'standard_rate',name:'standard_rate'},
        {data: 'cost',name:'cost'},
        {data: 'profit',name:'profit'},
        // {data: 'buying_price',name:'buying_price'},
        // {data: 'profit',name:'profit'},
        ]
        });

$('#search_product_id').on('click',function(event){
    table.ajax.reload();
});

    $('#product_id').on('input',function(e){
        if($(this).val() == ""){
            table.ajax.reload();
        }
    });
    $(document).ready(function () {


        $('#reloadTableButton').click(function(){
            table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

    function openModal(id) {
        $('#delItem').one('click',function (e) {
            e.preventDefault();
            delete_record(id);
        });
    }

    function delete_record(id) {

        // ajax delete data to database
        $.ajax({
            url: "{{$deleteurl}}/" + id,
            type: "GET",

            success: function (data) {
                //if success reload ajax table
                // console.log(data.success);
                $('#myModal').modal('hide');

                if(data =='true'){
                    $('#items_datatable').DataTable().draw(false)

                }
            //                reload_table();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

        $('#myModal').modal('toggle');

    //            setTimeout(function () {
    //                table.ajax.reload();
    //            }, 300);


            }




</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
