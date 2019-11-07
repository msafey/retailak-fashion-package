<!DOCTYPE html>
<html>
    <head>
        @include('layouts.admin.head')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js">
        </script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
            <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
            <style media="screen" type="text/css">
                td {
        font-size: 11px;
        padding: 2px;
    }
    .page-item {
    display: inline;
    font-size: 12px;
    padding: 1px;
}
            </style>
        </link>
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
                        <!--End Bread Crumb And Title Section -->
                        <div class="row">
                            <div class="col-sm-12 ">
                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                      @if( Session::has('success') )
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif
                        @if(!isset($collectionItems) || isset($collection))
                        <a href="{{ url('admin/collections') }}" class="btn btn-warning"><i class="zmdi zmdi-list"></i> Collections list </a>
                        <div class="row" id="colNameInput">
                            <div class="card card-block col-sm-6">

                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <label for="name"> Name </label>
                                        <input type="text" class="form-control" id="colname" name="name" @if(isset($collection)) value="{{ $collection->name }}" @endif placeholder="">
                                        <input type="hidden" class="form-control" id="sortno" name="sortno" @if(isset($collection)) value="{{ $collection->sortno }}"@else value="{{$SortNo}}" @endif  placeholder="">


                                        {{ csrf_field() }}
                                    </div>

                                    <div class="form-group">
                                        <label for="name_ar"> Name (Ar) </label>
                                        <input type="text" class="form-control" id="colname_ar" name="name_ar" @if(isset($collection)) value="{{ $collection->name_ar }}" @endif placeholder="">

                                    </div>

                                    @if(!isset($collection))
                                    <div class="form-group">
                                        <label for="main"> Main Collection </label>
                                        <input type="checkbox" @if(isset($collection) && $collection->main_col == true) checked="checked" @endif name="mainCol" id="mainCol" >

                                    </div>
                                    @endif

{{-- fvdfv --}}

                                    @if(isset($collection))
                                        <button type="button" id="updateColBtn" class="btn btn-info"> Update </button>
                                    @else
                                        <button type="button" id="addColBtn" class="btn btn-primary"> Save </button>
                                    @endif


                            </div>
                        </div>

                        @endif
                        <div class="row" id="colItemsDivs" @if(!isset($collectionItems)) style="display:none;" @endif >

                                 <input type="hidden" class="form-control" id="colId" name="colId" @if(isset($id)) value="{{ $id }}" @else value="" @endif  placeholder="">
                            <div class="card card-block col-sm-8">

                                 <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    {{-- ll --}}
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Code</th>
                                    <th>Add</th>
                                </tr>
                                </thead>


                                </table>
                            </div>


                             <div class="card card-block col-sm-4">
                                <div class="col-sm-12" style="min-height:90px;">
                                    <h2>Collection Items</h2>
                                </div>
                                 <table id="colItemsTable" class="display" cellspacing="0" border="0" width="100%">
                                <thead><tr style=""><th>Name</th><th>Brand</th><th>Code</th><th>Remove</th></tr></thead>
                                <tbody id="sortable" class="sortable">

                                    @if(isset($collectionItems) && $collectionItems->count() > 0)
                                        @foreach($collectionItems as $collectionItem)
                                            <tr id="{{ $collectionItem->item_code }}" ><td>{{ $collectionItem->name }}</td><td>{{ $collectionItem->brand }}</td><td>{{ $collectionItem->item_code }}</td><td><button  class="btn deleteBtn btn-danger"><i class="zmdi zmdi-delete"></i></button></td></tr>
                                        @endforeach
                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- container -->
                </div>
                <!-- content -->
            </div>
            <!-- End content-page -->
            <!-- Footer Area -->
            @include('layouts.admin.footer')
            <!-- End Footer Area-->
        </div>
        <!-- END wrapper -->
        <script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}">
        </script>
        <script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}">
        </script>
        <script>
            $(document).ready(function(){

                var table = $('#items_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('itList') !!}',
                columns: [

                    {data: 'name', name: 'name'},
                    {data: 'brand', name: 'brand'},
                    {data: 'item_code', name: 'item_code'},
                    {data: 'item_code', searchable: false, render: function (data,data2, row) {
                        var itemcodes = [];
                         $('#sortable tr').each(function() {
                           itemcodes.push(this.id)
                          });
                         // console.log(itemcodes);
                         // console.log(row.item_code);

                         if($.inArray(row.item_code,itemcodes) == -1)
                         {
                             return '<button  id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToCollection(\''+ row.item_code+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                         }
                         else
                         {
                           return '<button  disabled=disabled id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToCollection(\''+ row.item_code+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                         }

                        }
                    }
                ]
            });


        });

        function addToCollection(item_code,btnId)
        {
            // $('#'+btnId).attr("disabled", "disabled");
            // console.log(btnId);
            // document.getElementById(btnId).disabled = true;
            var collectionId = $('#colId').val();
            $.ajax({
                    type: 'POST',
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url:"{{ url('admin/collection/item/add/') }}"+"/"+collectionId,
                    data: {"item_code" : item_code},
                    success: function(result){
                        // $('#colId').val(result.productBrand);
                        $('#sortable').append('<tr id="'+item_code+'"><td>'+result.productName+'</td><td>'+result.productBrand+'</td><td>'+item_code+'</td><td><button value="'+btnId+'" class="btn deleteBtn btn-danger"><i class="zmdi zmdi-delete"></i></button></td></tr>');

                    },
                });




        }
         $(document).on('click','.addBtn',function(){
            $(this).attr('disabled','disabled');
        });


         @if(isset($id))
         $(document).on('click','#updateColBtn',function(){
            var colname = $('#colname').val();
            var colname_ar = $('#colname_ar').val();
            var main_col = $('#mainCol').val();
            if(colname.trim() =="")
            {
                alert('Please enter Collection name first');
            }
            else
            {
                 $.ajax({
                    type: 'POST',
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url:"{{ url('admin/collection/update').'/'.$id }}",
                    data: {"name" : colname,"name_ar" : colname_ar,"maincol" : main_col},
                    success: function(result){
                        // $('#colId').val(result.colId);
                        // $('#colNameInput').html( 'Collection ('+result.colName +') Was Added Successfully , Now Please Choose Products Blew To be added to the collection');
                        if(result =='done')
                        {
                             window.location.replace('{{ url('admin/collections') }}');
                        }

                    },
                });
            }


         });

         @endif
         $(document).on('click','.deleteBtn',function(){
             var addBtnId = $(this).val();
             var itemId = $(this).closest('tr').attr('id');
             var colId = $('#colId').val();

            $.ajax({
                    url:"{{ url('admin/collection/item/delete/') }}/"+colId+"/"+itemId,
                    success: function(data){
                        console.log(data);

            $("#"+data).removeAttr('disabled');
                    },
                });
            $(this).closest('tr').remove();
        });

        $(document).on('click','#addColBtn',function(){
            var colname = $('#colname').val();
            var colname_ar = $('#colname_ar').val();

            // var _token = $('input[name="_token"]').val();
            var sortno = $('#sortno').val();
            var data = [];

            data['name'] = colname;
            data['name_ar'] = colname_ar;
            data['sortno'] = sortno;
            data['maincol'] = $('#mainCol').val();
            // alert($('#mainCol').val());
            // data['_token'] = _token;
            if(colname.trim() =="")
            {
                alert('Please enter Collection name first');
            }
            else
            {
                $.ajax({
                    type: 'POST',
                    headers:
                    {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url:"{{ url('admin/collection/store') }}",
                    data: {"name" : colname,"name_ar" : colname_ar,"sortno" : sortno,"maincol" : data['maincol']},
                    success: function(result){
                        $('#colId').val(result.colId);
                        $('#colNameInput').html( 'Collection ('+result.colName +') Was Added Successfully , Now Please Choose Products Blew To be added to the collection');
                        $('#colItemsDivs').show();

                    },
                });
            }

        });

        var colId = $('#colId').val();
        $("#sortable").sortable({
                stop: function ()
                {
                    $.map($(this).find('tr'), function (el){
                        var rowID = el.id; var rowIndex = $(el).index();
                        // var colId = $('#colId').val();
                        var colId = $('#colId').val();
                        $.ajax({
                            url: '{{URL::to("/admin/collection/item/reorder/")}}/'+colId,
                            type: 'GET', dataType: 'json',
                            data: {rowID: rowID, rowIndex: rowIndex},
                            success: function (data){
                                //$.each(data , function(item){ $('#sortable tr').find(item.id).text(item.sort_no) ;
                                //datatable.ajax.reload();
                                //var datatable = $('#mytable').DataTable({"paging":   false ,destroy: true });
                                //});
                            },
                            error: function (data) { alert('Error Updating data'); }
                        });
                    });
                }
            });
        </script>
        <!-- JAVASCRIPT AREA -->
        @include('layouts.admin.javascript')
        <!-- JAVASCRIPT AREA -->
    </body>
</html>
