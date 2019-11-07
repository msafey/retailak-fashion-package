@extends('layouts.admin.app')

@section('head')
    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
          <style>
          thead input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
          </style>


 @if(App::isLocale('ar'))

    <link href="{{url('public/admin/css/rtl.css')}}" rel="stylesheet"/>
  
@endif

@endsection


@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- Bread Crumb And Title Section -->
    <div class="row">
        <div class="col-xs-12">
            <div class="page-title-box">
                <h4 class="page-title">{{__('main.users_activities')}} </h4>
                <ol class="breadcrumb p-0">
                    <li>
                        <a href="{{url('/')}}"></a>
                    </li>
                    <li class="active">
                    </li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
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

                        <th>{{__('main.user')}}</th>
                        <th>{{__('main.content_name')}}</th>
                        <th>{{__('main.action')}}</th>
                        <th>{{__('main.type')}}</th>
                        <th>{{__('main.content_id')}}</th>
                        <th>{{__('main.time')}}</th>


                    </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>{{__('main.user')}}</th>
                        <th>{{__('main.content_name')}}</th>
                        <th>{{__('main.action')}}</th>
                        <th>{{__('main.type')}}</th>
                        <th>{{__('main.content_id')}}</th>
                        <th>{{__('main.time')}}</th>
                      </tr>
                  </tfoot>


                </table>


                <!-- Table End-->
            </div>
        </div>

    </div>


@endsection

@section('javascript')
    <!-- Required datatable js -->
    @include('layouts.admin.datatable')


    <script>



            $('thead th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="{{__('main.search')}} '+title+'" />' );
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
@endsection
