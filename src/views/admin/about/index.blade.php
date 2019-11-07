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
           About US
          @endslot

          @slot('slot1')
            Home
          @endslot

          @slot('current')
            About us
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

        <div>
          <form action="{{ route('about_us') }}" method="post">
            {{ csrf_field() }}
            <textarea class="form-control" name="content" id="editor">
              @if(isset($content))
              {{ $content  }}
              @endif
            </textarea>
            <button type="submit" class="btn btn-dark btn-block">Update Content</button>
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
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) , {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>