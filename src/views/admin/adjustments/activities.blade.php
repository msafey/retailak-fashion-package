<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

<style>


   

    .th .sorting:after {
    content: "\f0dc";
    color: #2b3d51;
    font-size: 1em;
    padding-top: 0.12em;
    padding-right: 15px;
     
    }
    </style>
    <!--[if lt IE 9]>
    <script src="{{url('public/clock/assets/js/html5shiv.js')}}"></script>
    <script src="{{url('public/clock/assets/js/respond.min.js')}}"></script>
    <![endif]-->


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
                       Add Brand
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Brands
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
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
    <!--End Bread Crumb And Title Section -->
    <div class="row">

        <div class="card card-block">

            <div class="card-text">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if( Session::has('success') )
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                        </button>{{Session::get('success')}}</div>
            @endif
            <!-- Table Start-->


                <table id="actions_datatable" class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>

                        <th>User</th>
                        <th>Content Name</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Content Id</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>User</th>
                        <th>Content Name</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Content Id</th>
                        <th>Time</th>
                      </tr>
                  </tfoot>


                </table>


                <!-- Table End-->
            </div>
        </div>

    </div>


@include('layouts.admin.footer')
<script>
    var resizefunc = [];
</script>
@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>

<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>

    <!-- Required datatable js -->
    @include('layouts.admin.datatable')


    <script>



            $('thead th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
        var table = $('#actions_datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [3, 'desc'],
                ajax: '{!! route('activitiesList') !!}',
                columns: [
                    {data: 'user', name: 'user'},
                    {data: 'content_name', name: 'content_name'},
                    {data: 'action', name: 'action'},
                    {data: 'type', name: 'type'},
                    {data: 'id', name: 'id'},
                    {data: 'time', name: 'time'}
                ]
            });


        $(function () {

            table.ajax.reload();

        });
        $(document).ready(function () {
            $("[data-toggle='tooltip']").tooltip();



            table.columns().every( function () {
        var that = this;

        $( 'input', this.header() ).on( 'keyup change', function () {
                  if ( that.search() !== this.value ) {
                      that
                          .search( this.value )
                          .draw();
                  }
              } );
          } );

      });






    </script>
