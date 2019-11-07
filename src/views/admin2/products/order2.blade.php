<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
  <link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">
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
            @include('layouts.admin.breadcrumb')
<div class="col-sm-6">
            <select id="item_group" class="form-control" >
                <option disabled selected></option>
                <?php foreach ($categories as $cateory) { ?>
                    <option value="{{$cateory->id}}">{{$cateory->name_en}}###{{$cateory->name}}</option>
                <?php } ?>
            </select>
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
                    
                    <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Order</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Duration</th>
                                </tr>
                            </tfoot>
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



  var editor; // use a global for the submit and return data rendering in the examples
   
  $(document).ready(function() {
      editor = new $.fn.dataTable.Editor( {
          ajax:  '../php/sequence.php',
          table: '#example',
          fields: [ {
                  label: 'Order:',
                  name: 'readingOrder',
                  fieldInfo: 'This field can only be edited via click and drag row reordering.'
              }, {
                  label: 'Title:',
                  name:  'title'
              }, {
                  label: 'Author:',
                  name:  'author'
              }, {
                  label: 'Duration (seconds):',
                  name:  'duration'
              }
          ]
      } );
   
      var table = $('#example').DataTable( {
          dom: 'Bfrtip',
          ajax: url:'{!! route('productlist') !!}',

          columns: [
              { data: 'readingOrder', className: 'reorder' },
              { data: 'name' },
              { data: 'name_en' },
            
          ],
          columnDefs: [
              { orderable: false, targets: [ 1,2,3 ] }
          ],
          rowReorder: {
              dataSrc: 'readingOrder',
              editor:  editor
          },
          select: true,
          buttons: [
              { extend: 'create', editor: editor },
              { extend: 'edit',   editor: editor },
              { extend: 'remove', editor: editor }
          ]
      } );
   
      editor
          .on( 'postCreate postRemove', function () {
              // After create or edit, a number of other rows might have been effected -
              // so we need to reload the table, keeping the paging in the current position
              table.ajax.reload( null, false );
          } )
          .on( 'initCreate', function () {
              // Enable order for create
              editor.field( 'readingOrder' ).enable();
          } )
          .on( 'initEdit', function () {
              // Disable for edit (re-ordering is performed by click and drag)
              editor.field( 'readingOrder' ).disable();
          } );
  } );



      //Sortable => Reorder Image
    //   var item_group = 0;

    //     $(function () {
    //      // $('#mytable').DataTable();
    //       var mytable_db=  $('#mytable').DataTable({
    //             "paging":   false
    //         });


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

    // $("#sortable").sortable({
    //     stop: function () {
    //         $.map($(this).find('tr'), function (el) {
    //             var rowID = el.id;
    //             var rowIndex = $(el).index();
    //             var parent = document.getElementById('item_group').value
    //             $.ajax({
    //                 url: '{{URL::to("/admin/product/reorder")}}',
    //                 type: 'GET',
    //                 dataType: 'json',
    //                 data: {rowID: rowID, rowIndex: rowIndex,parent: parent},
    //                 success: function (data) {
    //                     // console.log(data)
    //                     mytable_db.clear().draw();
    //                     $('.odd').remove();
    //                     // mytable_db.ajax.reload();
    //                 data.forEach(function(item){
    //                     // console.log($('tr').find('#order_'+item.id));
    //                     // $('tr').find('#order_'+item.id).text(item.sorting_no) ;
    //                     $('#mytable').append('<tr><td id=order_'+item.id+'>'+item.sorting_no+'</td><td id=pro_name_'+item.id+'>'+item.name_en+'</td></tr>');
    //                 });
    //                 // alert(data);
    //                 },
    //                 error: function (data) {
    //                     alert('Error Updating data');
    //                 }
    //             });
    //         });
    //     }
    // });
// });




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