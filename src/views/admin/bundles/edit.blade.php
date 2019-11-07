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
        <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
              rel="stylesheet">
        <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
        <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

        <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
        <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

        <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
          <!-- Scripts -->
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
                                Bundles
                        @endslot

                        @slot('slot1')
                                Home
                        @endslot

                        @slot('current')
                                Bundles
                        @endslot
                        You are not allowed to access this resource!
                        @endcomponent
                        <!--End Bread Crumb And Title Section -->
                        <div class="row">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger" style="background-color: #fff;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                              @if (Session::has('success'))
                                <div class="alert alert-success" style="background-color: #fff !important; ">
                                  <ul>
                                    <li>{!! Session::get('success') !!} <i style="float: right;" class="far fa-check-square"></i></li>
                                  </ul>
                                </div>
                              @endif
                        </div>

                       <div class="row" style="margin: auto 25px;">
                           <p style="background-color: #fff; padding: 8px;text-align: center;font-size: 13px;">Bundel for Product:  &nbsp;[ {{ $bundle->bundel_product->name_en }} ] &nbsp;<i class="fas fa-box-open"></i></p>
                           <table class="table " style="background-color: #fff;text-align: center !important;" id="bundelProducts">
                             <thead>
                               <tr>
                                 <th scope="col" style="text-align: center !important;">#</th>
                                 <th scope="col" style="text-align: center !important;">Product Name</th>
                                 <th scope="col" style="text-align: center !important;">Quantity</th>
                                 <th scope="col" style="text-align: center !important;">Action</th>
                               </tr>
                             </thead>
                           </table>

                       </div>

                      <div class="row" style="margin: 30px 25px;">
                        <h6>Avilabel Products <i class="fab fa-product-hunt"></i></h6> <br>

                          <table class="table">
                            <thead>
                              <th>Product Select</th>
                              <th>quantity</th>
                              <th></th>
                            </thead>
                            <tbody>
                              <tr>
                                <form id="FormAdded" action="{{ route('AddItems') }}" method="post" onsubmit="AddedBundelItems()">
                                  {{ csrf_field() }}
                                  <td>
                                    <select id="item_id" class="select2p_items" name="item_id" style="width: 100%;">
                                      @foreach($producst_avilabel as $product)
                                        <option value="{{$product->id}}">{{ $product->name_en }}</option>
                                      @endforeach
                                    </select>
                                  </td>
                                  <td>
                                    <input id="bunde_id" type="hidden" name="bundel_id" value="{{$bundle->bundel_id}}" >
                                    <input id="qty" type="number" name="quantity">
                                  </td>
                                  <td>
                                    <button  class="btn btn-secondary">Add bundel item</button>
                                  </td>
                                </form>
                              </tr>
                            </tbody>
                          </table>
                      </div>
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
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    function showSave(id)
    {
      $('#Edit'+id).hide();
      $('#Save'+id).show();
      $('#Input'+id).prop('disabled',false);
    }

    function updateQuantity(id)
    {
        $changing_value = $('#Input'+id).val();
        $('#change'+id).val($changing_value);
    }

    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('.select2p_items').select2();
    });

  // Added bundel item Ajax
  function AddedBundelItems(e)
  {
      //get data before request
      var item_id = $('#item_id').val();
      var bundel_id = $('#bunde_id').val();
      var qty = $('#qty').val();

      // send Ajax request
      $.ajax({
          type:'POST',
          url:'{{ route('AddItems') }}',
          data:{
              "_token": "{{ csrf_token() }}",
              'item_id': item_id ,
              'bundel_id': bundel_id ,
              'quantity': qty ,

          },
          success:function(data) {
              $('#qty').val('');
              $('.select2p_items').find('option:selected').remove();
              table.ajax.reload();
          } ,
          error:function(er)
          {
              console.log(er);
          }
      });
  }
    $(document).ready(function() {
        $('#FormAdded').submit(function(evt){
            evt.preventDefault();// to stop form submitting
        });
    });

  // Data Tabel
      bundel_id = {{$bundle->bundel_id}}
     table = $('#bundelProducts').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('getBundelItems') !!}',
            type: "POST",
            data:{
                "Bundel_id" :bundel_id ,
                "_token": "{{ csrf_token() }}",
            }
        },
        columns: [
            {data: 'item_id', name: 'item_id'},
            {data: 'product_name', name: 'product_name'},
            {
                data: 'quantity', render: function (data,data2 , type){
                  return '<input onkeyup="updateQuantity('+type.id+')" id="Input'+type.id+'" disabled="disabled" style="text-align: center;" type="text" name="quantity" value="'+data+'" >';
                }
            },
            {
                data: 'action', render: function (data , data2 , type) {

                var Form1 = '' +
                          '<form action="{{route('editbundelItem')}}" method="post" style="display: inline-block;">{{ csrf_field() }}'+
                            '<input type="hidden" name="id" value="'+type.id+'" >' +
                            '<input type="hidden" id="change'+type.id+'" name="quantity_budel" value="'+type.quantity+'">' +
                            '<button style="display: none;" id="Save'+type.id+'" class="btn btn-warning" type="submit">Save</button>'+
                          '</form>';
                var edit = '<a class="btn btn-info" id="Edit'+type.id+'" onclick="showSave('+type.id+')">Edit</a>' ;

                var form2 = '&nbsp;' +
                        '<form method="post" action="{{ route('deleteBundel_item') }}" style="display: inline-block;"> {{ csrf_field() }} ' +
                          '<input type="hidden" name="id" value="'+type.item_id+'">' +
                          '<button  class="btn btn-danger" type="submit">Delete</button>' +
                        '</form>';
                    return Form1 + edit + form2;
                }
            },
        ]
    });

</script>
@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
</body>
</html>
