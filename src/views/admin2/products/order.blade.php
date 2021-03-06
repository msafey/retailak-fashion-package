<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
<style type="text/css">
</style>    
    <!--[if lt IE 9]>
    <script src="{{url('public/clock/assets/js/html5shiv.js')}}"></script>
    <script src="{{url('public/clock/assets/js/respond.min.js')}}"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script src="{{url('public/admin/plugins/datatable2/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{url('public/admin/plugins/datatable2/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/jszip.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/pdfmake.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/vfs_fonts.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/buttons.html5.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/buttons.print.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatable2/buttons.colVis.min.js')}}"></script>


    <!-- Responsive examples -->
    <script src="{{url('public/admin/plugins/datatable2/dataTables.responsive.min.js')}}"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatable2/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatable2/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatable2/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
          <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <meta name="csrf-token" item="{{ csrf_token() }}"/>

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
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
                @endslot
                You are not allowed to access this resource!
                @endcomponent        <div class="col-sm-6">
            <input type="text" hidden="hidden" value="{{$category_id}}" id="item_group">
            
        </div>
            <!--End Bread Crumb And Title Section -->
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
                    
     <!--                <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                        </tr>
                        </thead>


                    </table>

 -->

                    <table id="mytable" class="display" cellspacing="0" border="0" width="100%">
                            <thead>
                                <tr style="">
                                     <th>Order</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
            
                            <tbody id="sortable" class="sortable">
                                @foreach($products as $product)
                                <tr class="myTableRow" id="{{$product->id}}" >
                                    <td id="order_{{$product->id}}">{{$product->sorting_no}}</td>
                                    <td id="pro_name_{{$product->id}}">{{$product->name_en}}</td>
                                   
                                </tr>
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
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>


<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/clock/dist/bootstrap-clockpicker.min.js')}}"></script>

<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>

<script>
      //Sortable => Reorder Image
      var item_group = 0;

      // var item_group= 0;
      // var ckbox = $('#item_group');

      // $('select').change(function () {
      //       if (ckbox.is(':selected')) {
      //          item_group= document.getElementById("item_group").value;
      //       } 
      //    table.ajax.reload();

      //   });




        $(function () {
         // $('#mytable').DataTable();
          var mytable_db=  $('#mytable').DataTable({
                "paging":   false
            });


    //       $('#item_group').change(function(){
    //           item_group=document.getElementById('item_group').value;
    //           // $('#mytable').html('');
    //               mytable_db.clear().draw();
    //           $.ajax({
    //                   url: '{{URL::to("/admin/product/category/data")}}',
    //                   type: 'GET',
    //                   dataType: 'json',
    //                   data: {item_group: item_group},
              
                      
    //                   success: function (data) {
    //                       data.forEach(function(item){
    //                         $('.odd').remove();
    //                         $('#mytable').append('<tr><td id=order_'+item.id+'>'+item.sorting_no+'</td><td id=pro_name_'+item.id+'>'+item.name_en+'</td></tr>');
    //                       });
    //                   },
                      
    //                   error: function (data) {
    //                           alert('Error Updating data');
    //                   }
    //           });

    //       });

    $("#sortable").sortable({
        stop: function () {
            $.map($(this).find('tr'), function (el) {
                var rowID = el.id;
                var rowIndex = $(el).index();
                var parent = document.getElementById('item_group').value
                $.ajax({
                    url: '{{URL::to("/admin/product/reorder")}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {rowID: rowID, rowIndex: rowIndex,parent: parent},
                    success: function (data) {
                        // console.log(data)
                        // mytable_db.clear().draw();
                        // $('.odd').remove();
                        // mytable_db.ajax.reload();
                    data.forEach(function(item){
                        // console.log($('tr').find('#order_'+item.id));
                        $('tr').find('#order_'+item.id).text(item.sorting_no) ;
                        // $('#mytable').append('<tr><td id=order_'+item.id+'>'+item.sorting_no+'</td><td id=pro_name_'+item.id+'>'+item.name_en+'</td></tr>');
                    });
                    // alert(data);
                    },
                    error: function (data) {
                        alert('Error Updating data');
                    }
                });
            });
        }
    });
});




    </script>







      

 

<script type="text/javascript">
        function preview(input) {
         if (input.files && input.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) { $('#img').attr('src', e.target.result);  }
           reader.readAsDataURL(input.files[0]);     }   }

       $("#upload").change(function(){
         $("#img").css({top: 0, left: 0});
           preview(this);
           $("#img").draggable({ containment: 'parent',scroll: false });
       });
    </script>

<!-- JAVASCRIPT AREA -->
</body>
</html>