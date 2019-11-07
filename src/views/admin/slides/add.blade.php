<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')

    <link rel="alternate" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">

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
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if( Session::has('success') )
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif

                        <div class="card card-block col-sm-12">
                            <form method="POST" class="form-inline"  enctype="multipart/form-data" >
                                    <h1></h1>

                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label for="type"> Type : </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <select name="type" id="typeSelector" class="form-control">
                                                <option value="0">Select Slide Type .. </option>
                                                <option @if(isset(old()['type']) && old()['type']=='type1') selected @endif value="type1">Image</option>
                                                <option @if(isset(old()['type']) && old()['type']=='type2') selected @endif value="type2">Image + Text</option>
                                                <option @if(isset(old()['type']) && old()['type']=='type3') selected @endif value="type3">Multiple Images + Text</option>
                                            </select>
                                        </div>
                                    </div>
                                    {!! csrf_field() !!}

                                    <div id="mainImgDiv" class="row" style="display:none; border-bottom: 1px solid #ddd;">
                                        <div class="col-sm-6">
                                            <label for="main_image"> Main Image </label>
                                            <input type="file" name="main_image" value="{{old('main_image')}}" class="form-control" placeholder="">
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-2">Add Url  <input type="checkbox" name="img_1_url_checkbox" id="img_1_url_checkbox"></div>
                                            <div class="col-sm-10" id="img_1_url_div" style="display:none;">
                                               <input type="url" name="img_1_url" value="{{old('img_1_url')}}" placeholder="" class="form-control" style="width:90%">
                                            </div>
                                        </div>


                                    </div>


                                    <div id="imgTxtDiv"  style="display:none">
                                        <div class="row" style="border-bottom: 1px solid #ddd;">
                                            <div class="col-sm-6">
                                                <label for="text_1"> Upper Text : </label>
                                                <input type="text" name="upper_text" value="{{old('upper_text')}}" class="form-control" placeholder="" style="width:80%" >
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-2"> Add Url <input type="checkbox" name="txt_1_url_checkbox" id="txt_1_url_checkbox"> </div>
                                                <div class="col-sm-10"><div id="txt_1_url_div" style="display:none;">
                                                   <input type="url" name="text_1_url" value="{{old('text_1_url')}}" placeholder="" class="form-control" style="width:90%"></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" style="border-bottom: 1px solid #ddd;" >
                                            <div class="col-sm-6">
                                                <label for="text_2"> Main Text : </label>
                                                <input type="text" name="main_text" value="{{old('main_text')}}" class="form-control" placeholder="" style="width:80%">
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-2">Add Url <input type="checkbox" id="txt_2_url_checkbox" name="txt_2_url_checkbox"></div>
                                                <div class="col-sm-10"><div id="txt_2_url_div" style="display:none;">
                                                   <input type="url" name="text_2_url" value="{{old('text_2_url')}}" placeholder="" class="form-control" style="width:90%"></div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="row" style="border-bottom: 1px solid #ddd;">
                                            <div class="col-sm-6">
                                                <label for="text_3"> Lower text : </label>
                                                <input type="text" name="lower_text" value="{{old('lower_text')}}" class="form-control" placeholder="" style="width:80%">
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-2">Add Url <input type="checkbox" name="txt_3_url_checkbox" id="txt_3_url_checkbox"></div>
                                                <div class="col-sm-10"><div id="txt_3_url_div" style="display:none;">
                                                   <input type="url" name="text_3_url" value="{{old('text_3_url')}}" placeholder="" class="form-control" style="width:90%"></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="img2Div" class="row" style="display:none; border-bottom: 1px solid #ddd;">
                                            <div class="col-sm-6">
                                                <label for="img_2"> Small image 1</label>
                                                <input type="file" name="small_image_1" value="{{old('small_image_1')}}" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-2">Add Url  <input type="checkbox" name="img_2_url_checkbox" id="img_2_url_checkbox"></div>
                                                <div class="col-sm-10" id="img_2_url_div" style="display:none;">
                                                   <input type="url" name="img_2_url" value="{{old('img_2_url')}}" placeholder="" class="form-control" style="width:90%">
                                                </div>
                                            </div>
                                        </div>


                                        <div id="img3Div" class="row" style="display:none; border-bottom: 1px solid #ddd;">
                                            <div class="col-sm-6">
                                                <label for="img_3"> Small image 2</label>
                                                <input type="file" name="small_image_2" value="{{old('small_image_2')}}" class="form-control" placeholder="">
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-2">Add Url  <input type="checkbox" name="img_3_url_checkbox" id="img_3_url_checkbox"></div>
                                                <div class="col-sm-10" id="img_3_url_div" style="display:none;">
                                                   <input type="url" name="img_3_url" value="{{old('img_3_url')}}" placeholder="" class="form-control" style="width:90%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <button type="submit" class="btn btn-primary"> Save </button>

                            </form>
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
    <?php if(isset(old()['type'])){

        if(old()['type']=='type2'){
            ?>
             $('#mainImgDiv').show();
             $('#imgTxtDiv').show();
            <?php
        }
        elseif(old()['type']=='type1'){
            ?>
            $('#mainImgDiv').show();
            <?php
        }
        elseif(old()['type']=='type3'){
            ?>
                $('#mainImgDiv').show();
                $('#img2Div').show();
                $('#img3Div').show();
                $('#imgTxtDiv').show();
            <?php
        }

     }?>
    $('#typeSelector').on('change',function(){
        var slideType = $(this).val();
        if(slideType == 0)
        {
            $('#mainImgDiv').hide();
            $('#img2Div').hide();
            $('#img3Div').hide();
            $('#imgTxtDiv').hide();
        }
        else
        {
            $('#mainImgDiv').show();
            $('#imgTxtDiv').hide();
            $('#img2Div').hide();
            $('#img3Div').hide();
            if(slideType == 'type2' || slideType == 'type3')
            {
                $('#imgTxtDiv').show();
            }
            if(slideType == 'type3')
            {
                $('#img2Div').show();
                $('#img3Div').show();
            }
        }
    });

    $('#img_1_url_checkbox').on('change',function(){
        $('#img_1_url_div').toggle();
    });

    $('#img_2_url_checkbox').on('change',function(){
        $('#img_2_url_div').toggle();
    });

    $('#img_3_url_checkbox').on('change',function(){
        $('#img_3_url_div').toggle();
    });
    $('#txt_1_url_checkbox').on('change',function(){
        $('#txt_1_url_div').toggle();
    });
    $('#txt_2_url_checkbox').on('change',function(){
        $('#txt_2_url_div').toggle();
    });

    $('#txt_3_url_checkbox').on('change',function(){
        $('#txt_3_url_div').toggle();
    });


    </script>

    <!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
<!-- JAVASCRIPT AREA -->
</body>

</html>
