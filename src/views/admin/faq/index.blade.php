<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
  <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
  @include('layouts.admin.scriptname_desc')
  <script src="http://malsup.github.com/jquery.form.js">
  </script>
  <!-- App Favicon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />

  <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
  <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

  <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
  <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
  {{-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css" />

  <style>
    .ck-editor__editable {
      min-height: 200px;
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
            FAQ
          @endslot

          @slot('slot1')
            Home
          @endslot

          @slot('current')
            FAQ Page
          @endslot
          You are not allowed to access this resource!
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

          @if(Session::has('success'))
            <div class="alert alert-success">
              <ul>
                <li>{{ Session::get('success') }}</li>
              </ul>
            </div>
          @endif

        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="DeleteDrawing" tabindex="-1" role="dialog" aria-labelledby="DeleteDrawing"
             aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to FAQ</h5>
                <input type="hidden" value="" id="RemoveDrawing">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="DeleteDrawing()">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <div>
          <a class="btn btn-dark" href="{{ route('createFAQ') }}">Add New FAQ</a>
          <table class="table table-bordered " style="background-color: #fff;" id="faq-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>FAQ Title</th>
              <th>Num.Questions</th>
              <th>created_at</th>
              <th>Action</th>
            </tr>
            </thead>
          </table>
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
<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>

    table = $('#faq-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('faqAll') !!}',
            type: "GET",
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'count', name: 'count'},
            {data: 'created_at', name: 'created_at'},
            {
                data: 'id', render: function (data) {

                    edit = '<a href="faq/' + data + '/edit" class="btn btn-info"> <i class="fas fa-marker"></i> </a>';
                    remove = '<a  class="btn btn-danger"  data-toggle="modal" data-target="#DeleteDrawing" onclick="openModal(' + data + ')" > <i class="far fa-trash-alt"></i> </a>'
                    return remove + ' ' + edit;
                }
            },
        ]
    });

    function openModal(id) {
        $('#RemoveDrawing').val(id);
    }

    function DeleteDrawing() {
        var id = $('#RemoveDrawing').val();

        // ajax delete data to database
        $.ajax({
            url: '{{ route("deleteFAQ") }}',
            type: "post",
            data: {
                "_token": "{{ csrf_token()  }}",
                "id": id
            },
            success: function (response) {
                if (response == 'true') {
                    $('#faq-table').DataTable().ajax.reload(null, false);
                } else {
                    alert('Drawing not deleted !!  try again later');
                }
                $('#DeleteDrawing').modal('toggle');
            },
            error: function (err) {
                console.log(err);
            }
        });

    }
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>