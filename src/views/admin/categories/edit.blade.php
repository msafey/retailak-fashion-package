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


    {{--Clock Picker--}}
    <link rel="stylesheet" type="text/css" href="{{url('public/clock/dist/bootstrap-clockpicker.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{url('public/clock/assets/css/github.min.css')}}">--}}
    <style type="text/css">

        .hljs-pre {
            background: #f8f8f8;
            padding: 3px;
        }

        .input-group {
            width: 110px;
            margin-bottom: 10px;
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
                        Edit Category
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Categories
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
        {!! Form::open(['url' => ['/admin/categories', $category->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">

                     <div style="margin-left: 5px" class="card-text">


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name(English)
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' required value="{{$category->name_en}}" name="name_en" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="float: right; text-align: right">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">الاسم
                                            </label>
                                        </div>
                                        <div class="col-lg-12" dir="rtl" style="margin-top: 0px; text-align: right">
                                            <div class='input-group date' id='datetimepicker1' style="display: inline;  text-align: right">
                                                <input type='text' required name="name" value="{{$category->name}}" class="form-control">
                                            </div>
                                        </div>


                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-sm-12">
                                            <label style="margin-bottom: 0;"  class="form-group" for="from">Parent Item Group
                                            </label>
                                        </div>
                                        <div class="col-sm-12" >
                                            <div class='input-group date' id='datetimepicker1' style="display: inline;">
                                                <select  name="parent_item_group" class="form-control" >
                                                    <option value="0"> Select Category</option>
                                        <option value="-1" disabled > Parent Categories</option>
                                        @foreach($parentCategories as $cat)
                                            <option value="{{$cat->id}}" @if($category->parent_item_group == $cat->id)selected @endif id="cat{{$cat->id}}">{{$cat->name_en}}</option>
                                        @endforeach
                                                  @foreach($categories as $key=> $categoryy)
                                        <optgroup label="{{$key}}">
                                            @foreach($categoryy as $cat)
                                            <option value="{{$cat->id}}" @if($category->parent_item_group == $cat->id)selected @endif id="cat{{$cat->id}}">{{$cat->name_en}}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>





                             <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
                                     <br>
                                     <div class="col-sm-3">
                                         <label for="image">Image : </label>
                                         <input type="file" name="image"  id="upload" multiple class="form-group" >
                                     </div>
                                     <div class="col-md-3 "">
                                         <br>
                                         @if(isset($category_image->image))
                                             <img style="height: 150px;width: 200px;object-fit: contain;" id="img" src="{{$category->imgThumbPath($category_image->image)}}?{{rand(1, 3000)}}" />
                                         @else
                                         <img style="height: 150px;width: 200px;" id="img" src="{{asset('public/imgs/default.jpg')}}" />

                                         @endif
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


<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/clock/dist/bootstrap-clockpicker.min.js')}}"></script>

<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>





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
