<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- App Favicon -->
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

    <link rel="stylesheet" href="{{url('public/admin/components/cropper-master/dist/cropper.css')}}">

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link href url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css" />
    <link href="{{url('public/lou/css/multi-select.css')}}" media="screen" rel="stylesheet" type="text/css">


    <style type="text/css">


.required-red{
    color: red;
}
        .label-arabic{

        }

        #image {
            width: 100%;
        }

        .height {
            width: 826px;
            height: 450px;
            margin-left: 20px;
        }

        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 1024px;
        }

        .cropper-container .cropper-bg {
            height: auto;
        }

        .image-width {
           /*object-fit: cover;*/
               /*max-width: 200px;*/
               /* padding: 5px; */
               /*min-width: 220px;*/
               /*max-height: 137px;*/
               /*padding-top: 16px;*/
               /*min-height: 135px;*/
               /*object-fit: cover;*/
               /*object-position: center;*/
               width: 220px;
               height: 120px;
               margin-top: 14px;
               /* margin: 11px; */
               object-fit: contain;
               background-size: cover;
               background-repeat: no-repeat;
               background-position: 50% 50%;
        }

        #sortable li {
            margin: 5px 5px 5px 0;
            padding: 1px;
            float: left;
            width: 270px;
            height: 150px;
            font-size: 1.4em;
            text-align: center;
            overflow: hidden;
        }



    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {display:none;}

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }


        .hljs-pre {
            background: #f8f8f8;
            padding: 3px;
        }

        .input-group {
            width: 110px;
            margin-bottom: 10px;
        }

    
        .hljs-pre {
            background: #f8f8f8;
            padding: 3px;
        }

        .input-group {
            width: 110px;
            margin-bottom: 10px;
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
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
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
                </div>
               


                <div class="modal fade bs-example-modal-sm" id="variant_modal" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog"
                                            aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                               Variations
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                               </button>
                           </div>
                            @forelse($variations_data as $temp)
                           <div class="modal-body" >
                            <label for="">{{$temp->variation}}</label>
                            <select required name="variation" class="form-control selected_variation" >
                              <option value="">Choose Value</option>
                                @foreach($temp->variation_options as $dat)
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

                       <div class="modal fade bs-example-modal-sm" id="imgModal" tabindex="-1" role="dialog"
                            aria-labelledby="mySmallModalLabel">
                           <div class="modal-dialog modal-sm" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       Confirmation
                                   </div>
                                   <div class="modal-body">
                                       Are you Sure That You Want To Delete Image ?
                                   </div>
                                   <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                       <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                                   class="glyphicon glyphicon-trash"></i> Delete Item </a>

                                   </div>
                               </div>

                           </div>
                       </div>

                       <div class="modal fade bs-example-modal-sm" id="variantDeleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="mySmallModalLabel">
                           <div class="modal-dialog modal-sm" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       Confirmation
                                   </div>
                                   <div class="modal-body">
                                       Are you Sure That You Want To Delete This Variation ?
                                   </div>
                                   <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                       <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delVariation" title="Hapus"><i
                                                   class="glyphicon glyphicon-trash"></i> Delete Variation </a>

                                   </div>
                               </div>

                           </div>
                       </div>



                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                        aria-hidden="true" style="display: none;">
                       <div class="modal-dialog modal-lg">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                   <h4 class="modal-title" id="myLargeModalLabel">Manage Image</h4>
                               </div>

                               <div class="modal-body">
                                   <div class="row height">
                                       <img id="image" value="" src="">
                                   </div>
                                   <div class="row">
                                       <div class="btn-group btn-group-crop">
                                           <button type="button" class="btn btn-success" onclick="crop('products')">
                                               <span class="docs-tooltip" title="">
                                                 Get Cropped Image
                                               </span>
                                           </button>
                                       </div>

                                       <div class="btn-group">

                                           <button onclick="zoomIn()" type="button" class="btn btn-primary" title="Zoom In">
                                               <span class="docs-tooltip">
                                                 <span class="fa fa-search-plus"></span>
                                               </span>
                                           </button>

                                           <button onclick="zoomOut()" type="button" class="btn btn-primary" title="Zoom Out">
                                               <span class="docs-tooltip">
                                                 <span class="fa fa-search-minus"></span>
                                               </span>
                                           </button>

                                       </div>

                                       <div class="btn-group btn-group-crop">

                                           <button onclick="rotate90()" type="button" class="btn btn-primary" title="Rotate Left">
                                               <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="">
                                                 <span class="fa fa-rotate-left"></span>
                                               </span>
                                           </button>

                                           <button onclick="rotate_minus90()" type="button" class="btn btn-primary"
                                                   title="Rotate Right">
                                               <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="">
                                                 <span class="fa fa-rotate-right"></span>
                                               </span>
                                           </button>

                                       </div>

                                       <!-- <div class="btn-group">
                                           <button onclick="scaleHorizontal()" id="scaleHorizontal" value="1" type="button"   class="btn btn-primary" title="Flip Horizontal">
                                                   <span class="docs-tooltip">
                                                     <span class="fa fa-arrows-h"></span>
                                                   </span>
                                           </button>
                                           <button onclick="scaleVertical()" id="scaleVertical" value="1" type="button"
                                                   class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                                                   <span class="docs-tooltip">
                                                     <span class="fa fa-arrows-v"></span>
                                                   </span>
                                           </button>

                                       </div> -->

                                       <div class="btn-group">
                                           <button onclick="reset()" type="button" class="btn btn-primary" data-method="reset"
                                                   title="Reset">
                                               <span class="docs-tooltip">
                                                 <span class="fa fa-refresh"></span>
                                               </span>
                                           </button>

                                           <button onclick="clear()" type="button" class="btn btn-primary" data-method="clear"
                                                   title="clear">
                                                   <span class="docs-tooltip">
                                                     <span class="fa fa-power-off"></span>
                                                   </span>
                                           </button>
                                       </div>

                                   </div>

                               </div>
                           </div><!-- /.modal-content -->
                       </div><!-- /.modal-dialog -->
                   </div>


        {!! Form::open(['url' => ['/admin/products', $product->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}
             <div class="card card-block">          
                  <div style="margin-left: 5px" class="card-text">
                      <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Name(English)
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0px">
                                <div class='input-group date' style="display: inline;" id=''>
                                    <input type='text' required name="name_en" value="{{$product->name_en}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12" style="float: right; text-align: right">
                                <label style="margin-bottom: 0;" class="form-group" for="from">الاسم
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                <div class='input-group date' id='' style="display: inline;  text-align: right">
                                    <input type='text' required name="name" style="direction: rtl" value="{{$product->name}}"  class="form-control">
                                </div>
                            </div>
                        </div>                      
                      </div>
                                        
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Description (English)
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                  <textarea class="form-control"  id="" name="description_en" rows="3">{{$product->description_en}}</textarea>                                            </div>
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="col-lg-12" style="float: right; text-align: right">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">الوصف
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                  <div class='input-group date' id='' style="display: inline;  text-align: right">
                                      <textarea class="form-control" dir="rtl" id="" name="description" rows="3">{{$product->description}}</textarea>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-lg-6">
                              <div class="col-sm-12">
                                  <label style="margin-bottom: 0;"  class="form-group" for="from">Item Group
                                  </label>
                              </div>
                              <div class="col-sm-12" >
                                  <div class='input-group date' id='' style="display: inline;">

                                      <select required name="item_group" id="item_group" class="form-control select2" >
                                        <option  disabled >Select Item Group</option>
                                        @foreach($parent_category as $key=> $category)
                                        <optgroup label="{{$key}}">
                                            @foreach($category as $cat)
                                            <option value="{{$cat->id}}" @if($product->item_group == $cat->id )selected @endif id="cat{{$cat->id}}">{{$cat->name_en}}</option>

                                            @endforeach
                                        </optgroup>
                                        @endforeach

                                      </select>
                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="col-sm-12">
                                  <label style="margin-bottom: 0;"  class="form-group" for="from">Second Item Group
                                  </label>
                              </div>
                              <div class="col-sm-12" >
                                  <div class='input-group date' id='' style="display: inline;">
                                      <select  name="second_item_group" id="second_item_group" class="form-control select2" >
                                        <option value="All Item Groups" disabled >Second Item Group</option>
                                         @foreach($parent_category as $key=> $category)
                                        <optgroup label="{{$key}}">
                                            @foreach($category as $cat)
                                          @if($cat->id != $product->item_group)
                                            <option value="{{$cat->id}}" @if($product->second_item_group == $cat->id )selected @endif id="cat{{$cat->id}}">{{$cat->name_en}}</option>
                                            @endif
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
                               <label style="margin-bottom: 0;" class="form-group">UOM
                               </label>
                           </div>
                           <div class="col-lg-12" style="margin-top: 0px">
                               <div class='input-group' style="display: inline;" id=''>
                                    <select name="uom" id="uom" class="form-control" required="required">
                                      @foreach($query as $qu)
                                          <option value="{{$qu->id}}" @if($qu->id == $product->uom)selected @endif>{{$qu->type}}</option>
                                      @endforeach
                                    </select>
                                </div>
                           </div>
                        </div>
                        <div class="col-lg-3">
                           <div class="col-lg-12" style="">
                               <label style="margin-bottom: 0;" class="form-group" for="from">Weight
                               </label>
                           </div>
                           <div class="col-lg-12" style="margin-top: 0px;">
                               <div class='input-group date' id='' style="display: inline;  text-align: right">
                                 <input class="form-control" id="weight" name="weight" value="{{$product->weight}}" placeholder="" required="required" type="text" />
                               </div>
                           </div>
                        </div>


                        <div class="col-lg-3" style="display: none;">
                            <div class="col-lg-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">SKU
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0px">
                                <div class='input-group date' style="display: inline;" id=''>
                                <input class="form-control"  id="item_code" name="item_code"
                                    placeholder="" value="{{$product->item_code}}" disabled="disabled" type="text"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                             <div class="col-sm-12">
                                 <label style="margin-bottom: 0;"  class="form-group" for="from">Brand
                                 </label>
                             </div>
                             <div class="col-sm-12" >
                                 <div class='input-group date' id='' style="display: inline;">
                                     <select required name="brand_id" id="brand" class="form-control" >
                                         <option value="-1" @if($product->brand_id == -1)selected @endif disabled>Select Brand</option>
                                         <?php foreach ($brands as $brand) { ?>
                                             <option value="{{$brand->id}}" @if($product->brand_id == $brand->id )selected @endif >{{$brand->name_en}} ## {{$brand->name}}</option>
                                         <?php } ?>
                                     </select>
                                 </div>
                             </div>
                        </div>       
                      </div>
                      <div class="row">   
                          <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
                              <br>
                              <div class="col-lg-3">
                                  <div class="col-lg-12">
                                      <label style="margin-bottom: 0;" class="form-group" for="from">Is Bundle
                                      </label>
                                  </div>
                                  <div class="col-lg-12" style="margin-top: 0px">
                                       <label class="switch">
                                <input type="checkbox" value="1" @if(isset($product) && $product->is_bundle ==1)checked @endif  name="is_bundle">
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
                                 <?php $i=1; ?>
                                 @forelse($variants_values as $variant_data)

                                    <div class="col-md-12 card card-block " style="border: 1px solid" id="field{{$i}}">
                                        <div class="row">
                                            <button type="button" style="float: right;" class="btn btn-danger" data-toggle="modal"  data-target="#variantDeleteModal" onclick=deleteVariation({{$variant_data->variant_product_id}},0)>
                                            <span aria-hidden="true">&times;</span>
                                            <!-- href="javascript:void(0)"  --> 
                                          </button>
                                        </div>
                                        
                                        <div class="row">

                                          <div class="col-lg-12">
                                              <div class="col-lg-12">
                                                  <label style="margin-bottom: 0;" class="form-group" for="from">Name: <span style="color:red;">*</span>
                                                  </label>
                                              </div>
                                              <div class="col-lg-12" style="margin-top: 0px">
                                                @foreach($variant_data->variation as $key => $variant)
                                                <div class="col-lg-4">
                                                  <div class='input-group date' style="display: inline;" id=''>
                                                      <input type='text' required  disabled=""  value="{{$key}}:{{$variant}}"  class="form-control">
                                                    
                                                     
                                                  </div>
                                                </div>
                                                
                                                  @endforeach
                                                  @foreach($variant_data->variant_data_id as $k => $v)
                                                  <input type='text' required name="variation_item{{$i}}[]" id="variation_item{{$i}}" class="variation_item{{$i}}" value="{{$k}}:{{$v}}" hidden=""  class="form-control">
                                                  @endforeach
                                              </div>
                                          </div>
                                        </div>
                                        @if($variant_data->has_special_images==1)
                                          <div class="row">
                                               <div class="col-lg-6">
                                                     <p>Upload Images For This Variation Only</p>
                                                     <input type="file" multiple=""  name="variation_image{{$i}}[]" id="variation_image{{$i}}"  class="form-control variant_images">
                                               </div>
                                          </div>
                                          <div class="form-group  col-lg-12 col-sm-12 col-xs-9 col-md-12 col-xl-12">

                                               <ul id="sortable" class="sortable">
                                                   @forelse($variant_data->variant_images as $image)
                                                       <li class=" ui-state-default" id="{{$image->id}}">
                                                           <div class="col-md-1">
                                                               <a type="button" href="javascript:void(0)" data-toggle="modal" data-target="#imgModal"
                                                                  onclick=deleteImg('{{$image->id}}','products')>
                                                                   <i class="fa fa-trash" aria-hidden="true"></i>
                                                               </a>
                                                               <a onclick="showImage('{{$image->image}}' , '{{$product->imgThumbPath($image->image)}}')"
                                                                          type="button" href="javascript:void(0)"
                                                                  class=" waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">
                                                                   <i class="fa fa-edit" aria-hidden="true"></i>
                                                               </a>

                                                           </div>
                                                           <div class="col-md-1">
                                                               <img id="{{$image->image}}" class="image-width"
                                                                    src="{{$product->imgThumbPath($image->image)}}?{{rand(1, 3000)}}"/>
                                                           </div>
                                                       </li>
                                                   @empty
                                                   

                                                   @endforelse
                                                   

                                               </ul>
                                          </div> 
                                          @endif  

                                    </div>
                                    <?php $i++ ?>
                                 @empty

                                 @endforelse
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
                                    <input type="checkbox" value="1" id="has_attributes" @if($product->has_attributes == 1)checked @endif name="has_attributes">
                                    <span class="slider round"></span>
                                  </label>
                                      </div>
                                  </div>
                            </div>

                            <div id="display_attribute" @if($product->has_attributes == 0)style="display: none;" @endif>
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text" style="margin-bottom: 60px;">
                                        <div class="row" id="dates" class="DateRow">
                                          <?php $i =1; ?>
                                          @forelse($attribute_array as $attr)
                                            <div class="row DateRow" id="field{{$i}}" style="margin-left: 20px;margin-top: 10px">
                                                <div class="col-sm-4" >
                                                    <label for="contentType"> Attribute : <span class="required-red">*</span></label>
                                                    <select  name="attributes_keys[]" id="key{{$i}}" class="form-control attribute_keys" >
                                                    @foreach($attributes as $attribute_key)
                                                        <option value="{{$attribute_key->id}}" @if($attr->attribute->id == $attribute_key->id)selected @endif id="">{{$attribute_key->name_en}}</option>
                                                    @endforeach
                                                    </select>               
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="contentType">Value : <span class="required-red">*</span></label>
                                                    <select  name="attributes_value[]" id="value{{$i}}" class="form-control attributes_value" >
                                                      <option value="{{$attr->attribute_values->id}}" id="">{{$attr->attribute_values->variation_value_en}}</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-sm-1">
                                                    <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeDate"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                            <?php $i++;?>
                                            @empty
                                            <div class="row DateRow" id="field1" style="margin-left: 20px;margin-top: 10px">
                                                <div class="col-sm-4" >
                                                    <label for="contentType"> Attribute : <span class="required-red">*</span></label>
                                                    <select required name="attributes_keys[]" id="key1" class="form-control attribute_keys" >
                                                        <option value="">Choose Key</option>
                                                        @foreach($attributes as $attribute)
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
                                            @endforelse

                                        </div>

                                        <button style="margin-left: 20px;" class="btn btn-primary" type="button" id="newDate"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute 
                                    </div>           
                                </div>
                                 <hr> 
                            </div>
                          <hr>  
                      @endif
                
                      
                      <div class="row">  
                        <div class="col-sm-3">
                            @if(isset($product_configurations['multi_images']) && $product_configurations['multi_images'] == true)
                                <label for="image">Images : <span style="color:red;">*</span></label>
                                <input type="file" name="images[]"  id="upload_multi" multiple class="form-group" >
                            @else
                                
                                <label for="image">Image : <span style="color:red;">*</span></label>
                                <input type="file" name="images"  id="upload_single" class="form-group" >
                            @endif
                        </div>                                
                      </div>

                      <div class="form-group  col-lg-12 col-sm-12 col-xs-9 col-md-12 col-xl-12">
                                 <ul id="sortable" class="sortable">
                                    @if(count($product_images)>0)
                                     @forelse($product_images as $image)
                                         <li class=" ui-state-default" id="{{$image->id}}">
                                             <div class="col-md-1">
                                                 <a type="button" href="javascript:void(0)" data-toggle="modal" data-target="#imgModal"
                                                    onclick=deleteImg('{{$image->id}}','products')>
                                                     <i class="fa fa-trash" aria-hidden="true"></i>
                                                 </a>
                                                 <a onclick="showImage('{{$image->image}}' , '{{$product->imgThumbPath($image->image)}}')"
                                                            type="button" href="javascript:void(0)"
                                                    class=" waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">
                                                     <i class="fa fa-edit" aria-hidden="true"></i>
                                                 </a>

                                             </div>
                                             <div class="col-md-1">
                                                 <img id="{{$image->image}}" class="image-width"
                                                      src="{{$product->imgThumbPath($image->image)}}?{{rand(1, 3000)}}"/>
                                             </div>
                                         </li>
                                     @empty
                                     

                                     @endforelse
                                     @else

                                      <div class="col-md-1">

                                      <img style="height: 150px;width: 200px;object-fit: contain;" id="img" src="{{asset('public/imgs/default.jpg')}}" />
                                     </div>
                                     @endif

                                 </ul>
                      </div>    
                      <div class="row">
                        <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                      </div>                                                         
                  </div>
              </div>

        {!! Form::close() !!}
               </div>
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
    <script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>

    <script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script>
<script src="{{url('public/lou/js/jquery.multi-select.js')}}" type="text/javascript"></script>
  <script src="{{url('public/admin/components/cropper-master/dist/cropper.js')}}"></script>
    <script src="{{url('public/admin/components/cropper-master/docs/js/cropper.js')}}"></script>

<script type="text/javascript">
    

    var selected_variants = [];
    var variants = <?php echo json_encode($variations)?>;
    $('#add_variant').on('click',function(e){
        $.each(variants, function( index, value ) {
                 var optionExists = ($('#variation_select'+' option[value=' + value.id + ']').length > 0);
                if(!optionExists)
                {
                    $('#variation_select').append('<option value="'+value.id+'" id="variant'+value.id+'">'+value.name_en+'</option>'); 
                }  
        });           
    });
     

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
        console.log(attribute_values);
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
    $(document).ready(function(){
        // attribute_key
            // attributes_value
            if($('#has_attributes').is(':checked')) {
                $('.attribute_keys').each(function(){
                    $(this).attr('required',true);
                });
                $('.attributes_value').each(function(){
                  $(this).attr('required',true);
                })
            }else {
              $('.attribute_keys').each(function(){
                  $(this).attr('required',false);
              });
              $('.attributes_value').each(function(){
                $(this).attr('required',false);
              })
            }  
    })





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
                    var div_image_exist = ($('#variation_image'+cTypeIncrementNum).length > 0);
                    if(div_image_exist){
                    var field = document.getElementById("variation_image"+cTypeIncrementNum);
                    field.setAttribute("name", 'variation_image'+cTypeIncrementNum+'[]');
                    }
                }
               $('#variant_modal').modal('hide');
              },
              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                   console.log(JSON.stringify(jqXHR));
                   console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
              }
            });

        // }else{
        //     alert('Already Selected');
        // }
    });
        function getSecondPart(str) {
    return str.split('-')[1];
}


    $(document).on('change','.variant_value',function(){
        // console.log($(this).val());
        var id = $(this).attr('id');
        var select_id = $(this).attr('id').match(/\d+/); // 123456    
        var variant_value =   $(this).val().match(/\d+/)
        var variation_name = $('#variation_name'+select_id).val();
        var name = variation_name+'_'+variant_value+'[]';
        var field = document.getElementById("image"+select_id);
        field.setAttribute("name", name);  // using .setAttribute() method
    })



   $(function () {
            $(".sortable").sortable({
                stop: function () {
                    $.map($(this).find('li'), function (el) {
                        var item = "{{$product->id}}";
                        var imageID = el.id;
                        var imageIndex = $(el).index();
                        $.ajax({
                            url: '{{URL::to("admin/images/order")}}',
                            type: 'GET',
                            dataType: 'json',
                            data: {imageID: imageID, imageIndex: imageIndex, item: item, type: 'App\\Products'}
                        })
                    });
                }
            });
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

    $(".mydropdown2").select2();
        function preview(input) {
         if (input.files && input.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) { $('#img').attr('src', e.target.result);  }
           reader.readAsDataURL(input.files[0]);     }   }

       $("#upload_single").change(function(){
         $("#img").css({top: 0, left: 0});
           preview(this);
           $("#img").draggable({ containment: 'parent',scroll: false });
       });
    </script>


      <script>
        //Sortable => Reorder Image
        $(function () {
            $("#sortable").sortable({
                stop: function () {
                    $.map($(this).find('li'), function (el) {
                        var item = <?php echo $product->id; ?>;
                        var imageID = el.id;
                        var imageIndex = $(el).index();
                        $.ajax({
                            url: "{{URL::to('admin/images/order')}}",
                            type: 'GET',
                            dataType: 'json',
                            data: {imageID: imageID, imageIndex: imageIndex, item: item, type: "App\\Products"}
                        })
                    });
                }
            });
        });
    </script>

    

  <script>
    $image = $('#image');

    function showImage(filename, imgPath) {
        $image.attr('src', " ");
        $image.attr('value', " ");
        $image.cropper("destroy");
        $image.attr('src', imgPath+'?'+Math.random());
        $image.attr('value', filename);
        $image.cropper({
//            aspectRatio: 16 / 9
        });
    }


    function crop(type) {
        $image.cropper({
//            aspectRatio: 16 / 9,
            crop: function (e) {
            }
        });
        $image.cropper('getCroppedCanvas').toBlob(function (blob) {
            var filename = $image.attr('value');
            var formData = new FormData();

            formData.append('croppedImage', blob);
            formData.append('type', type);
            formData.append('filename', filename);


            $.ajax('{{URL::to('admin/images')}}', {
                headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    console.log(data);
                    document.getElementById(filename).src = " ";
                    document.getElementById(filename).src = data;
                    $('.bs-example-modal-lg').modal('hide');
                    $image.attr('src', " ");
                    $image.attr('value', " ");

                },
                error: function () {
                    alert('Error , Try Again Later');
                }
            });
        });
    }


    function rotate90() {
        $image.cropper('rotate', 90);

    }

    function rotate_minus90() {
        $image.cropper('rotate', -90);

    }

    // function scaleHorizontal() {
    //     inputValue = document.getElementById("scaleHorizontal").value;

    //     if (inputValue == 1) {
    //         $image.cropper('scaleX', -1);
    //         inputValue2 = document.getElementById("scaleHorizontal").value = "-1";
    //     } else {
    //         $image.cropper('scaleX', 1);

    //         document.getElementById("scaleHorizontal").value = -1;
    //         document.getElementById("scaleHorizontal").value = "1";
    //     }
    // }

    // function scaleVertical() {
    //     inputValue = document.getElementById("scaleVertical").value;
    //     if (inputValue == 1) {
    //         $image.cropper('scaleY', -1);
    //         inputValue2 = document.getElementById("scaleVertical").value = "-1";
    //     } else {
    //         $image.cropper('scaleY', 1);

    //         document.getElementById("scaleVertical").value = "-1";
    //         document.getElementById("scaleVertical").value = "1";
    //     }
    // }


    function reset() {
        $image.cropper("reset");
    }

    function clear() {
        $image.cropper("clear");

    }

    function zoomIn() {
        $image.cropper('zoom', 0.1);


    }

    function zoomOut() {
        $image.cropper('zoom', -0.1);

    }

</script>


<script>
    function deleteVariation(variationId,is_attribute) {
        $('#delVariation').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL::to('admin/products/variation/delete')}}" + '/' + variationId+'/'+is_attribute ,
                type: "GET",
                success: function (data) {
                    if(data == 'success'){
                        $('#variantDeleteModal').modal('hide');
                        location.reload(); // then reload the page.(3)
                    }else{
                        // alert('Something Went Wrong , Try Again Later ..');
                        location.reload(); // then reload the page.(3)   
                    }

                    // document.getElementById(imgId).style.display = "none";
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //alert('Error Deleting Image');
                }
            });
        });


    }function deleteImg(imgId, type) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL::to('admin/products/image/delete')}}" + '/' + imgId ,
                type: "GET",
                success: function (data) {
                    $('#imgModal').modal('hide');
                    location.reload(); // then reload the page.(3)

                    // document.getElementById(imgId).style.display = "none";
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //alert('Error Deleting Image');
                }
            });
        });
    }
    $(document).on('click','.delete_variation',function(){
        var id = $(this).attr('id');
        var deleted_div_id = $(this).attr('id').match(/\d+/); // 123456    
        $('#field'+deleted_div_id).remove();
    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>