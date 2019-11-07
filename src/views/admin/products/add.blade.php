<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
<script src="http://malsup.github.com/jquery.form.js">
</script>
<link href="{{url('public/multi-images/dist/styles.imageuploader.css')}}" rel="stylesheet">
<script src="{{url('public/multi-images/dist/jquery.imageuploader.js')}}"></script>
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
    <link href="{{url('public/lou/css/multi-select.css')}}" media="screen" rel="stylesheet" type="text/css">

    <style type="text/css">

    body{
        font-family: 'Segoe UI';
        font-size: 12pt;
    }

    header h1{
        font-size:12pt;
        color: #fff;
        background-color: #1BA1E2;
        padding: 20px;

    }
    article
    {
        width: 80%;
        margin:auto;
        margin-top:10px;
    }




    /* The switch - the box around the slider */

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
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
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



                <div class="modal fade bs-example-modal-sm" id="variant_modal" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog"
                                            aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                               Variations
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                               </button>
                           </div>
                            @forelse($variation_data as $data)
                           <div class="modal-body" >
                            <label for="">{{$data->variation}}</label>
                            <select required name="variation" class="form-control selected_variation" >
                                <option value="">Choose Values</option>
                                @foreach($data->variation_options as $dat)
                                    <option value="{{$dat->id}}">{{$dat->variation_value_en}}</option>
                                @endforeach
                            </select>
                           </div>
                           @empty
                           @endforelse
                           <div class="modal-footer">
                               <a class="btn btn-sm btn-primary"  id="variant_added" title="Add Variation"><i
                                           class="glyphicon glyphicon-trash"></i> Add Variation</a>
                           </div>
                        </div>

                    </div>
                </div>





                {!! Form::open(['url' => '/admin/products', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">

                     <div style="margin-left: 5px" class="card-text">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name(English): <span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' required name="name_en" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="float: right; text-align: right">
                                            <label style="margin-bottom: 0;" class="form-group" for="from"><span style="color:red;">*</span>:الاسم
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px;">
                                            <div class='input-group date' id='' style="display: inline;">
                                                <input type='text' required style="direction: rtl;" name="name" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Description (English): <span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                            <textarea class="form-control"  id="" name="description_en" rows="3">{{ old('description_en') }}</textarea>                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-lg-12" style="float: right; text-align: right">
                                            <label style="margin-bottom: 0;" class="form-group" for="from"><span style="color:red;">*</span>:الوصف
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                            <div class='input-group date' id='' style="display: inline;  text-align: right">
                                            <textarea class="form-control" dir="rtl" id="" name="description" rows="3">{{ old('description') }}</textarea>                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-sm-12">
                                            <label style="margin-bottom: 0;"  class="form-group" for="from">Main Category:<span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-12" >
                                            <div class='input-group date' id='' style="display: inline;">
                                                <select required name="item_group" id="item_group" class="form-control select2" >
                                                    <option  disabled selected>Select Main Category</option>
                                                    @foreach($parent_category as $key=> $category)
                                                    <optgroup label="{{$key}}">
                                                        @foreach($category as $cat)
                                                        <option value="{{$cat->id}}" id="cat{{$cat->id}}">{{$cat->name_en}}</option>

                                                        @endforeach
                                                    </optgroup>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                        <div class="col-lg-6">
                                            <div class="col-sm-12">
                                                <label style="margin-bottom: 0;"  class="form-group" for="from">2nd Category
                                                </label>
                                            </div>
                                            <div class="col-sm-12" >
                                                <div class='input-group date' id='' style="display: inline;">
                                                    <select  name="second_item_group" id="second_item_group" class="form-control select2" >
                                                        <!-- <option></option> -->
                                                    <option  disabled selected>Select Main Category</option>
                                                    @foreach($parent_category as $key=> $category)
                                                    <optgroup label="{{$key}}">
                                                        @foreach($category as $cat)
                                                        <option value="{{$cat->id}}" id="cat{{$cat->id}}">{{$cat->name_en}}</option>

                                                        @endforeach
                                                    </optgroup>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </div>


                         <div class="row">
                             <div class="col-lg-3">
                                 <div class="col-lg-12">
                                     <label style="margin-bottom: 0;" class="form-group">UOM:<span style="color:red;">*</span>
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px">
                                     <div class='input-group' style="display: inline;" id=''>
                                        <select name="uom" id="uom" class="form-control" required="required">
                                            @foreach($query as $qu)
                                                <option value="{{$qu->id}}">{{$qu->type}}</option>
                                            @endforeach
                                        </select>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-lg-3">
                                 <div class="col-lg-12" style="">
                                     <label style="margin-bottom: 0;" class="form-group" for="from">Weight:<span style="color:red;">*</span>
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px;">
                                     <div class='input-group date' id='' style="display: inline;  text-align: right">
                                       <input class="form-control" id="weight" name="weight" placeholder="" required="required" type="text" value="{{ old('weight') }}" />
                                     </div>
                                 </div>
                             </div>
                             <div class="col-lg-3" style="display: none;">
                                 <div class="col-lg-12">
                                     <label style="margin-bottom: 0;" class="form-group" for="from">SKU:<span style="color:red;">*</span>
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0px">
                                     <div class='input-group date' style="display: inline;" id=''>
                                     <input class="form-control"  id="item_code" name="item_code"
                                         placeholder="" required="required" type="text"   value="{{ old('item_code') }}" />
                                     </div>
                                 </div>
                             </div>
                             <div class="col-lg-3">
                               <div class="col-sm-12">
                                 <label style="margin-bottom: 0;"  class="form-group" for="from">Brand:<span style="color:red;">*</span>
                                 </label>
                               </div>
                               <div class="col-sm-12" >
                                 <div class='input-group date' id='' style="display: inline;">
                                   <select required name="brand_id" id="brand" class="form-control" >
                                     <option value="-1" disabled selected>Select Brand</option>
                                     <?php foreach ($brands as $brand) { ?>
                                     <option value="{{$brand->id}}">{{$brand->name_en}}##{{$brand->name}}</option>
                                     <?php } ?>
                                   </select>
                                 </div>
                               </div>
                             </div>
                         </div>
                         <div class="row">

                               <div class=" col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-top: 10px;">
                                   <div class="col-lg-3">
                                       <div class="col-lg-12">
                                           <label style="margin-bottom: 0;" class="form-group" for="from">Is Bundle
                                           </label>
                                       </div>
                                       <div class="col-lg-12" style="margin-top: 0px">
                                            <label class="switch">
                                     <input type="checkbox" value="1" name="is_bundle">
                                     <span class="slider round"></span>
                                   </label>
                                       </div>
                                   </div>
                             </div>
                         </div>



                             @if(isset($product_configurations['variations']) && $product_configurations['variations'] == true)

                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-bottom: 30px;">
                                           <br>
                                           <br>
                                            <span style="margin-right: 35px;"><b>Variations:</b></span>
                                            <button class="btn btn-primary" data-target="#variant_modal" type="button" data-toggle="modal" id="add_variant">Add Variation</button>

                                           <br>

                                </div>

                                <div id="variations_data">

                                </div>
                            </div>
                            <hr>
                            <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-top: 10px;">
                                  <div class="col-lg-3">
                                      <div class="col-lg-12">
                                          <label style="margin-bottom: 0;" class="form-group" for="from">Has Attributes?
                                          </label>
                                      </div>
                                      <div class="col-lg-12" style="margin-top: 0px">
                                           <label class="switch">
                                    <input type="checkbox" value="1" id="has_attributes" name="has_attributes">
                                    <span class="slider round"></span>
                                  </label>
                                      </div>
                                  </div>
                            </div>

                            <div id="display_attribute" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text" style="margin-bottom: 60px;">
                                        <div class="row" id="dates" class="DateRow">
                                            <div class="row DateRow" id="field1" style="margin-left: 20px;margin-top: 10px">
                                                <div class="col-sm-4" >
                                                    <label for="contentType"> Attribute : <span class="required-red">*</span></label>
                                                    <select required name="attributes_keys[]" id="key1" class="form-control attribute_keys" >
                                                        <option value="">Choose Key</option>
                                                        @foreach($var_attributes as $attribute)
                                                            <option value="{{$attribute->id}}" id="">{{$attribute->name_en}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="contentType">Value : <span class="required-red">*</span></label>
                                                    <select required name="attributes_value[]" id="value1" class="form-control attributes_value" >
                                                        <option value="">Choose Values</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-1">
                                                    <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeDate"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <button style="margin-left: 20px;" class="btn btn-primary" type="button" id="newDate"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute
                                    </div>
                                </div>
                                 <hr>
                            </div>
                            @endif

                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
                                     <br>
                                            <label for="image">Image : <span style="color:red;">*</span></label>
                                       @if(isset($product_configurations['multi_images']) && $product_configurations['multi_images'] == true)
                                            <input type="file" name="images[]"  id="upload_multi" multiple="" class="form-group" >
                                       @else
                                       <input type="file" name="images"  id="upload" class="form-group" >

                                       @endif

                                         <div id="image_upload" style="">

                                     <div class="col-md-3" >
                                        <output id="result" />

                                         <br>
                                         <img style="height: 150px;width: 200px;" id="img" src="{{asset('public/imgs/default.jpg')}}" />
                                     </div>
                                     <div id="multi_images" style="display: none;">
                                        <div class="uploader__box js-uploader__box l-center-box" >

                                          <div class="uploader__contents">
                                              <label class="button button--secondary" for="fileinput">Select Files</label>
                                              <input id="fileinput" name="images[]" class="uploader__file-input" type="file" value="Select Files">
                                          </div>

                                        </div>
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


-</div>


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
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>

<!-- <script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script>
<script src="{{url('public/lou/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script>

    $('#has_attributes').on('change',function(){
        if ($(this).is(':checked')) {
           $('#display_attribute').css('display','block');
        } else {
            $('#display_attribute').css('display','none');
        }
    });


    function getAllValues(){
        var values = [];
        $('.attributes_value').each(function(){
            values.push($(this).val());
        });
        return values;
    }
    $(document).on('change','.attribute_keys',function(e){
        var attr_id = $(this).attr('id');
        var key = $(this).val();
        var attr_digit = $(this).attr('id').match(/\d+/);
        var attribute_values = getAllValues();
        $.ajax({
            method: 'GET',
            url: '{!! route('attributes') !!}',
            data: {'key' : key,'values':attribute_values},
            success: function(response){
                $('#value'+attr_digit).empty();
                var options = '';
                $('#value'+attr_digit).append('<option value="">Choose Values</option>');

                $.each(response.attributes_values, function( index, value ) {
                    options +='<option value="' + value.id + '">' + value.variation_value_en + '</option>';
                });
                    $('#value'+attr_digit).append(options);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
             }
        });
    });



    $(document).ready(function () {
        $(document).on('click','#removeDate', function(){
            var numItems = $('.DateRow').length;
            if(numItems > 1){
                //$('#removeDate').prop('disabled',false);
                $(this).parent().parent().remove();
            }
        });
        $('#newDate').click(function () {
            var id = $('.DateRow:last').attr('id');
            var id_num = $('.DateRow:last').attr('id').match(/\d+/);
            // console.log($('#'+id+' #value'+id_num).val())
            var attr_key = $('#'+id+' #key'+id_num).val();
            var attr_value = $('#'+id+' #value'+id_num).val();
            if(attr_key == null || attr_key =='' || attr_value == null || attr_value == ''){
            }else{
                // $('#alert_no_variants').css('display','none');
                var newClone = $('.DateRow:last-of-type').clone();
                newClone.appendTo('#dates');
                var contentTypeInput = $('.DateRow:last');
                var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
                contentTypeInput.attr('id', 'field' + cTypeIncrementNum);
                $('.DateRow:last #key'+id_num).attr('id','key' + cTypeIncrementNum);
                $('.DateRow:last #value'+id_num).attr('id','value' + cTypeIncrementNum);

                // contentTypeInput.attr('id', 'cp' + cTypeIncrementNum);
                $('#value'+cTypeIncrementNum).empty();
                $('#value'+cTypeIncrementNum).append('<option value="">Choose Values</option>');
            }

        });
    });






    $(document).on('click','#variant_added',function(e){
     var selected_variations = [];
     $(".selected_variation").each(function(){
         selected_variations[i++] =  $(this).val(); //this.id
     });
         selected_variations.clean(undefined);
            $.ajax({
              method: 'GET',
              url: '{!! route('addVariationView') !!}',
              data: {'selected_variations' : selected_variations},
              success: function(response){
                // console.log(response.data);
                if(response =='false'){
                    alert('No Variation Selected');
                    return false;
                }
                var div_exist = ($('#variations_data .card-block:last').length > 0);
                if(!div_exist){
                    $('#variations_data').append(response.data);
                    var id = $('#variations_data .card-block:last').attr('id');
                    var contentTypeInput = $('#variations_data .card-block:last').prop('id');
                }else{
                    var id = $('#variations_data .card-block:last').attr('id');
                    var contentTypeInput = $('#variations_data .card-block:last').prop('id');
                    $('#variations_data').append(response.data);
                    var cTypeIncrementNum = parseInt(id.match(/\d+/g), 10) + 1;
                    $('#variations_data .card-block:last').attr('id', 'field' + cTypeIncrementNum);
                    $('#variations_data #field'+cTypeIncrementNum +' .variation_item1').each(function(){
                        $(this).attr("name", 'variation_item'+cTypeIncrementNum+'[]');
                        $(this).attr('class','variation_item' + cTypeIncrementNum);
                    });
                    $('#variations_data .card-block:last #variation_item1').attr('id','variation_item' + cTypeIncrementNum);
                    $('#variations_data .card-block:last #variation_image1').attr('id','variation_image' + cTypeIncrementNum);
                    $('#variations_data .card-block:last #del1').attr('id','del' + cTypeIncrementNum);
                    var field = document.getElementById("variation_image"+cTypeIncrementNum);
                    field.setAttribute("name", 'variation_image'+cTypeIncrementNum+'[]');

                }
               $('#variant_modal').modal('hide');
              },
              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                   console.log(JSON.stringify(jqXHR));
                   console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
              }
            });
    });
    function getSecondPart(str) {
        return str.split('-')[1];
    }

    $(document).on('click','.delete_variation',function(){
    var id = $(this).attr('id');
    var deleted_div_id = $(this).attr('id').match(/\d+/); // 123456
    $('#field'+deleted_div_id).remove();
    });
    $(document).on('change','.variant_value',function(){
        // console.log($(this).val());
        var id = $(this).attr('id');
        var select_id = $(this).attr('id').match(/\d+/); // 123456
        var variant_value =   $(this).val().match(/\d+/)
        var variation_name = $('#variation_name'+select_id).val();
        var name = variation_name+'_'+variant_value+'[]';
        var field = document.getElementById("image"+select_id);
        field.setAttribute("name", name);  // using .setAttribute() method
    });

    $("#item_group").change(function(e){
       var item_group= e.target.value;
           $.ajax({
               method: 'GET',
               url: '{!! route('item_group') !!}',
               data: {'item_group' : item_group},
               success: function(response){
                   // console.log(response[0].id);
                   $('#second_item_group').empty();
                   $('#second_item_group').attr('class','form-control select2');
                   $('#second_item_group').append('<option value="All Item Groups" disabled selected>Second Item Group</option>');

                   $.each(response, function( index, value ) {
                        var options = '';
                       $.each(value,function(index,value){
                        options +='<option value="' + value.id + '">' + value.name_en + '</option>';
                       });
                       $('#second_item_group').append('<optgroup label="'+index+'">'+options+'</optgroup');

                   });

               },
               error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                   console.log(JSON.stringify(jqXHR));
                   console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
               }
           });
       });


            $(".mydropdown2").select2();

            $(function() {
                $('#datetimepicker1').datetimepicker({
                    icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
                },
                });
                $('#datetimepicker2').datetimepicker({
                    icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            },
                });




            $('#datetimepicker3').datetimepicker({
                icons: {
            time: 'zmdi zmdi-time',
            date: 'zmdi zmdi-calendar',
            up: 'zmdi zmdi--up',
            down: 'zmdi zmdi--down',
            //previous: 'glyphicon glyphicon-chevron-left',
            previous: 'zmdi zmdi-backward',
            next: 'zmdi zmdi-right',
            today: 'zmdi zmdi-screenshot',
            clear: 'zmdi zmdi-trash',
            close: 'zmdi zmdi-remove'
        },
            });



                $('#datetimepicker4').datetimepicker({
                    icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            },
                });
    });
    var options = {};
    $('.js-uploader__box').uploader(options);
        var options = {
            instructionsCopy: 'Drag and Drop, or',
            furtherInstructionsCopy: 'Your can also drop more files, or',
            selectButtonCopy: 'Select Files',
            secondarySelectButtonCopy: 'Select More Files',
            dropZone: $(this),
            fileTypeWhiteList: ['jpg', 'png', 'jpeg', 'gif', 'pdf'],
            badFileTypeMessage: 'Sorry, we\'re unable to accept this type of file.',
            ajaxUrl: '/ajax/upload',
            testMode: false
        };
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
