<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

        <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
      <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>

      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/css/bootstrap-colorpicker.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.js"></script>


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
                       Edit Variant
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Variants
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
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
                <div class="modal fade bs-example-modal-sm" id="variantDeleteModal" tabindex="-1" role="dialog"
                     aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirmation
                            </div>
                            <div class="modal-body">
                                Are you Sure That You Want To Delete This Variation Value ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delVariation" title="Hapus"><i
                                            class="glyphicon glyphicon-trash"></i> Delete Variation </a>

                            </div>
                        </div>

                    </div>
                </div>

        {!! Form::open(['url' => ['/admin/variations', $variation->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                    <div class="card card-block">
                        <div style="margin-left: 5px" class="card-text">
                            <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from"> Name (English)
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{ $variation->name_en }}" required name="name_en" class="form-control">
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
                                                <input type='text' required name="name" value="{{ $variation->name }}" class="form-control">
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
                                            <input type='text' value="{{ $variation->key }}" required name="key" class="form-control">
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
                                          <input type="checkbox" @if($variation->is_color == 1)checked @endif id="is_color" value="1" name="is_color">
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
                                          <input type="checkbox" @if($variation->is_size == 1)checked @endif id="is_size" value="1" name="is_size">
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
                                <?php $i = 1;?>
                                @forelse($variation_meta as $meta)
                                <div class="row DateRow" id="field{{$i}}" style="margin-left: 20px;margin-top: 10px">

                                    <input type="text" hidden="" value="{{$meta->id}}" name="variant_metas[]" class="variant_meta"
                                    id="var{{$meta->id}}">

                                   <div class="col-sm-3" >
                                       <label for="contentType"> English : <span class="required-red">*</span></label>
                                       <input id="key" type="text" name="variation_value_en[]"  value="{{$meta->variation_value_en}}"  class="form-control" required>
                                   </div>
                                   <div class="col-sm-3">
                                       <label for="contentType">Arabic : <span class="required-red">*</span></label>
                                       <input id="value" name="variation_value[]" value="{{$meta->variation_value}}" type="text" style=" direction:rtl; text-align: right;"  class="form-control" required>
                                   </div>
                                   <div class="col-sm-2 color_code_class" id="color_code{{$i}}" @if($variation->is_color ==0) style="display:none;" @endif>
                                       <label for="contentType">Color Code : <span class="required-red">*</span></label>
                                         <div id="cp{{$i}}" style="margin-right: 0;" class="input-group colorpicker-component">
                                           <input type="text" @if(isset($meta->code) && !is_null($meta->code)) value="{{$meta->code}}" @else value="#ffffff" @endif name="color_codes[]" class="form-control" id="color_value" /><span class="input-group-addon"><i></i></span>
                                       </div>
                                   </div>
                                   <div class="col-sm-3">
                                       <label for="variantion_code"> Variation Code : <span class="required-red">*</span></label>
                                       <input type="text" @if(isset($meta->variant_code) && !is_null($meta->variant_code)) value="{{$meta->variant_code}}"  @endif name="variant_code[]" class="form-control" id="variation_value" />
                                   </div>


                                    <div class="col-sm-1">
                                        <button class="btn btn-danger removeDate" style="margin-top:24px;" type="button" id="removeDate{{$meta->id}}"  data-toggle="modal"  data-target="#variantDeleteModal" ><i class="fa fa-minus" aria-hidden="true"></i></button>
                                    </div>

                                </div>
                                <?php $i++;?>
                            @empty
                             <div class="row DateRow" id="field1" style="margin-left: 20px;margin-top: 10px">
                                        <div class="col-sm-4" >
                                            <label for="contentType"> English : <span class="required-red">*</span></label>
                                           <input id="key" type="text" name="variation_value_en[]"  class="form-control" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="contentType">Arabic : <span class="required-red">*</span></label>
                                            <input id="value" name="variation_value[]" type="text" style=" direction:rtl; text-align: right;"  class="form-control" required>
                                        </div>
                                        <div class="col-sm-3 color_code_class" id="color_code1" style="display:none;">
                                            <label for="contentType">Color Code : <span class="required-red">*</span></label>
                                              <div id="cp1" style="margin-right: 0;" class="input-group colorpicker-component">
                                                <input type="text" value="#ffffff" name="color_codes[]" class="form-control" id="color_value" /><span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                       <label for="variantion_code"> Variation Code : <span class="required-red">*</span></label>
                                       <input type="text"   name="variant_code[]" class="form-control" id="variation_value" />
                                   </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeDate"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                @endforelse
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
                              <input type="checkbox" value="1" @if($variation->affecting_stock ==1)checked @endif name="affecting_stock">
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
                              <input type="checkbox" value="1" @if($variation->has_special_images ==1)checked @endif name="has_special_images">
                              <span class="slider round"></span>
                            </label>
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
    // $('#cp1').colorpicker();myCheck
    // var checkBox = document.getElementById("myCheck");
    // var checkBox1 = document.getElementById("myCheck1");

    $(document).ready(function () {

        if($('#is_color').is(':checked')){
            $('.colorpicker-component').each(function(){
                var color_pickerid = $(this).attr('id');
                $('#'+color_pickerid).colorpicker();
            });
            $('#is_size').attr('checked',false);
            $(".color_code_class").each(function(){
               id_num= $(this).attr('id').match(/\d+/);
               $('#cp'+id_num).colorpicker();
            });
            $("#color_code1").css('display','block');
        }

        $('#alert_no_variants').css('display','none');
        function getLength() {
            var numItems = $('.DateRow').length;
            if(numItems > 1){
                $('#removeDate').prop('disabled',false);
            }else{
                $('#removeDate').prop('disabled',true);
            }
        }

        $(document).on('click','#removeDate', function(){
            var numItems = $('.DateRow').length;
            if(numItems > 1){
                //$('#removeDate').prop('disabled',false);
                $(this).parent().parent().remove();
            }
        });


        $(document).on('click','.removeDate', function(e){

                var id = $(this).attr('id');
                var variationId = $(this).attr('id').match(/\d+/)
                removeVariation(variationId);

        });
function removeVariation(variationId){
    $('#delVariation').one('click', function (e) {
            var numItems = $('.DateRow').length;
        if(numItems > 1){
        }else{
            alert('variation must have at least one value');
            $('#variantDeleteModal').modal('hide');
            return false;
        }
        e.preventDefault();
        $.ajax({
            url: "{{URL::to('admin/variations/variant/delete')}}" + '/' + variationId ,
            type: "GET",
            success: function (data) {
                if(data == 'success'){
                    $('#variantDeleteModal').modal('hide');
                    $(this).parent().parent().remove();
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //alert('Error Deleting Image');
            }
        });
    });
}

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

        $('#newDate').click(function () {
            var id = $('.DateRow:last').attr('id');
            if($('#'+id+' #key').val() == null || $('#'+id+' #value').val() == null){
            }else{
                var newClone = $('.DateRow:last-of-type').clone();
                newClone.appendTo('#dates');
                var contentTypeInput = $('.DateRow:last');
                var contentTypeInput_before = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) ;
                var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;

                contentTypeInput.attr('id', 'field' + cTypeIncrementNum);
                $('.DateRow:last #color_code'+contentTypeInput_before).attr('id','color_code' + cTypeIncrementNum);
                $('.DateRow:last #color_code'+cTypeIncrementNum).attr('class','col-sm-3 color_code_class');
                $('.DateRow:last #cp'+contentTypeInput_before).attr('id','cp' + cTypeIncrementNum);
                // var idd = $('.removeDate:last').attr('id').replace(/[0-9]/g, '');
                // $('.removeDate:last').
                // $('.removeDate:last').attr('id',idd);
                // $('.DateRow:last #'+idd).removeClass("removeDate");
                // $('#'+idd).removeAttr("data-target");

                $('.variant_meta:last').attr('id','new');
                $('.variant_meta:last').attr('value',0);
                // variant_meta
                $('#cp'+cTypeIncrementNum).colorpicker();
                $('#cp'+cTypeIncrementNum).val('#ffffff');

                $('.DateRow:last-of-type #key').val('');
                $('.DateRow:last-of-type #value').val('');
            }

        });
    });



</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
