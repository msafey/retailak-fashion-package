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
            create FAQ
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

        <div style="padding: 25px;">
          <form method="post" action="{{ route('storeFAQ') }}">

            {{ csrf_field() }}
            <div class="col-12">
              <label class="col-xs-3">FAQ Title</label>
              <input required name="title" class="col-xs-9 form-control" type="text" placeholder="FAQ Header.... " class="form-control">
            </div>

            <div class="clearfix" style="margin-bottom: 50px;"></div>

            <div class="details">
                <p style="text-align: center;padding: 10px;" class="bg-inverse">Question & Answers <i onclick="add_detail()" class="fas fa-lg fa-plus-square" style="float: right;color: #fff;font-size: 30px;cursor: pointer;"></i> </p>

                <div class="detail">
                  <div class="col-12 question">
                    <label class="col-xs-12">Question ? <i onclick="hideDetail(this)" class="fas fa-lg fa-minus-square" style="float: right;color: #06060638;font-size: 30px;cursor: pointer;"></i> </label>
                    <input required name="question[]" class="col-xs-9 form-control" type="text" placeholder="Question.... " class="form-control">
                  </div>
                  <div class="clearfix" style="margin-bottom: 3px;"></div>
                  <div class="col-12 answer" >
                    <textarea required name="Answer[]" class="col-xs-9 form-control" type="text" placeholder="Answer.... " class="form-control"></textarea>
                  </div>
                </div>
              <div class="clearfix"></div>
              <br>
            </div><br>
            <button class="btn btn-dark" type="submit">Add FAQ</button>
          </form>
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
@include('layouts.admin.javascript')
<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- JAVASCRIPT AREA -->
<script>
  function add_detail()
  {
      $('.details').append(' <div class="detail">\n' +
          '<div class="col-12 question">\n' +
          '      <label class="col-xs-12">Question ?  <i onclick="hideDetail(this)" class="fas fa-lg fa-minus-square" style="float: right;color: #06060638;font-size: 30px;cursor: pointer;"></i> </label>\n' +
          '      <input required name="question[]" class="col-xs-9 form-control" type="text" placeholder="Question.... " class="form-control">\n' +
          '     </div>\n' +
          '     <div class="clearfix" style="margin-bottom: 3px;"></div>\n' +
          '  ' +
          '  <div class="col-12 answer" >\n' +
          '   <textarea required name="Answer[]" class="col-xs-9 form-control" type="text" placeholder="Answer.... " class="form-control"></textarea>\n' +
          '  </div>\n' +
          '  <div class="clearfix"></div>' +
          '</div><br>');
  }
  function hideDetail(x)
  {
      $(x).parent().parent().parent().hide(1000);
  }
</script>
</body>
</html>