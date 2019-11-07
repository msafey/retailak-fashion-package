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
      <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/css/bootstrap-colorpicker.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.js"></script>



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
                       Add Variation
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Variation
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
                {!! Form::open(['url' => '/admin/variations', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" >


                     <div style="margin-left: 5px" class="card-text">


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from"> Name (English)
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{ old('name_en') }}" required name="name_en" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name (Arabic):<span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                            <div class='input-group date' id='datetimepicker1' style="display: inline; direction:rtl; text-align: right;">
                                                <input type='text' required name="name" value="{{ old('name') }}" class="form-control">
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from"> Key
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                            <input type='text' value="{{ old('key') }}" required name="key" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Is Color?
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <label class="switch">
                                          <input type="checkbox" id="is_color" value="1" name="is_color">
                                          <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Is Size?
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <label class="switch">
                                          <input type="checkbox" id="is_size" value="1" name="is_size">
                                          <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                        <label for="name">Variation Values<span style="color:red;">*</span>:</label>
                                            </label>
                                        </div>
                                    </div>

                                </div>

                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text" style="margin-bottom: 60px;">

                                <div class="row" id="dates" class="DateRow">
                                    <div class="row DateRow" id="field1" style="margin-left: 20px;margin-top: 10px">

                                        <div class="col-sm-3" >
                                            <label for="contentType"> English : <span class="required-red">*</span></label>
                                           <input id="key" type="text" name="variation_value_en[]"  class="form-control" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="contentType">Arabic : <span class="required-red">*</span></label>
                                            <input id="value" name="variation_value[]" type="text" style=" direction:rtl; text-align: right;"  class="form-control" required>
                                        </div>
                                                                                <div class="col-sm-3">
                                            <label for="contentType">variation code : <span class="required-red">*</span></label>
                                            <input id="value" name="variation_codes[]" type="text" style=" direction:rtl; text-align: right;"  class="form-control" required>
                                        </div>
                                        <div class="col-sm-3 color_code_class" id="color_code1" style="display:none;">
                                            <label for="contentType">Color Code : <span class="required-red">*</span></label>
                                              <div id="cp1" style="margin-right: 0;" class="input-group colorpicker-component">
                                                <input type="text" value="#ffffff" name="color_codes[]" class="form-control" id="color_value" /><span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-1">
                                            <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeDate"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <button style="margin-left: 20px;" class="btn btn-primary" type="button" id="newDate"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute
                            </div>

                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-left: 90px;">

                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Is Affecting Stocks?
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                     <label class="switch">
                              <input type="checkbox" value="1" name="affecting_stock">
                              <span class="slider round"></span>
                            </label>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Has Special Images?
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                     <label class="switch">
                              <input type="checkbox" value="1" name="has_special_images">
                              <span class="slider round"></span>
                            </label>
                                </div>
                            </div>

                        </div>


                                <div class="row" >
                                    <div class="col-sm-32"><button type="submit"  style="margin-left: 12px" class="btn btn-primary">Save</button></div>
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
    $('#is_color').on('change',function(){
        var id_num=0;
        if ($(this).is(':checked')) {
            $(".color_code_class").each(function(){
               id_num= $(this).attr('id').match(/\d+/);
               $('#color_code'+id_num).css('display','block');
            });
            $('#is_size').attr('checked',false);
        } else {
            $(".color_code_class").each(function(){
               id_num= $(this).attr('id').match(/\d+/);
               $('#color_code'+id_num).css('display','none');
            });
        }
    });

    $('#is_size').on('change',function(){
        if ($(this).is(':checked')) {
             $(".color_code_class").each(function(){
           id_num= $(this).attr('id').match(/\d+/);
           $('#color_code'+id_num).css('display','none');
        });
            $('#is_color').attr('checked',false);
        }

    });
    $(document).ready(function () {
       $('#cp1').colorpicker();
        $(document).on('click','#removeDate', function(){
            var numItems = $('.DateRow').length;
            if(numItems > 1){
                //$('#removeDate').prop('disabled',false);
                $(this).parent().parent().remove();
            }
        });
        $('#newDate').click(function () {
            // getLength();
            var id = $('.DateRow:last').attr('id');
            if($('#'+id+' #key').val() == null || $('#'+id+' #value').val() == null){
                // $('#alert_no_variants').show();
            }else{
                // $('#alert_no_variants').css('display','none');
                var newClone = $('.DateRow:last-of-type').clone();
                newClone.appendTo('#dates');
                var contentTypeInput = $('.DateRow:last');
                var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
                contentTypeInput.attr('id', 'field' + cTypeIncrementNum);
                $('.DateRow:last #color_code1').attr('id','color_code' + cTypeIncrementNum);
                $('.DateRow:last #color_code'+cTypeIncrementNum).attr('class','col-sm-3 color_code_class');
                $('.DateRow:last #cp1').attr('id','cp' + cTypeIncrementNum);
                // contentTypeInput.attr('id', 'cp' + cTypeIncrementNum);
                $('#cp'+cTypeIncrementNum).colorpicker();
                $('#cp'+cTypeIncrementNum).val('#ffffff');

                $('.DateRow:last-of-type #key').val('');
                $('.DateRow:last-of-type #value').val('');

                // $("#value").tagsinput('removeAll');


            }

        });
    });



</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
