<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link href="{{url('public/modal/css/bootstrap.css')}}" rel="stylesheet"/>

    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

<style>
      .image-width {
            object-fit: cover;
            max-width: 220px;
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
                      Edit Line Haul Batch
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Line Haul Batch
                @endslot
                You are not allowed to access this resource!
                @endcomponent            <!--End Bread Crumb And Title Section -->
            <div class="modal fade bs-example-modal-sm" id="imgModal" tabindex="-1" role="dialog"
                 aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    Confirmation
            </div>
            <div class="modal-body">
                Are You Sure You Want Delete This Image
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                class="glyphicon glyphicon-trash"></i>Delete</a>
            </div>
        </div>
    </div>
</div>

-                <div class="row">
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
        {!! Form::open(['url' => ['/admin/line-haul-batch', $line_haul_batch->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">


                               

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Driver Name
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{$line_haul_batch->driver_name}}" required name="driver_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>                                

                               
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Car Plate Number
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{$line_haul_batch->car_plate_number}}" required name="car_plate_number" class="form-control">
                                            </div>
                                        </div>
                                    </div>                                

                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Weight
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{$line_haul_batch->weight}}"  name="weight" class="form-control">
                                            </div>
                                        </div>
                                    </div>     

                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Purchase Order Number
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='Number' value="{{$line_haul_batch->purchase_order_number}}" required name="purchase_order_number" class="form-control">
                                            </div>
                                        </div>
                                    </div>                                

                                </div>

    <div class="row">
    <div class="col-lg-6">
            <label for="images">Images </label>
              <input type="file" name="images[]" id="upload" multiple class="form-group">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <ul id="" class="sortable">
                @forelse($lines_images as $image)
                <li class=" ui-state-default" id="{{$image->id}}">
                    <div class="col-sm-1">
                        <a type="button" href="javascript:void(0)" data-toggle="modal" data-target="#imgModal"
                            onclick=deleteImg('{{$image->id}}')>
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="col-sm-1">
                        <img id="{{$image->image}}" class="image-width img"
                        src="{{$line_haul_batch->imgThumbPath($image->image)}}?{{rand(1, 3000)}}"/>
                    </div>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
    

                                </div>

                                <div class="row">
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                                </div>
                            </div>

                {!! Form::close() !!}
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
<script src="{{url('/public/')}}/prasley/parsley.js"></script>


 

<script type="text/javascript">

    <?php $deleteurl = url('/admin/line-haul-batch/image/delete'); ?>
    function deleteImg(imgId) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ $deleteurl }}' + '/' + imgId,
                type: "GET",
                success: function (data) {
                    $('#imgModal').modal('hide');

                    setTimeout(function () {// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 100);

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //alert('Error Deleting Image');
                }
            });
        });
    }



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