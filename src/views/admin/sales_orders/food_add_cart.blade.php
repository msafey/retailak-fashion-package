<!DOCTYPE html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
<script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<style>
  .page-title-box{
    margin-right: 80px !important;
    margin-left: -10px !important;
  }

 
</style>


       <!-- Latest compiled and minified bootstrap-select CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">


       <div class="modal fade bd-example-modal-lg" id="foodModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">


      </div>
         
  
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
                        Sales Orders
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Sales Orders
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
               

                {!! Form::open(['url' => '/admin/cart', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
               
                <div class="card card-block" style="width: 93%">

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
                  <div class="row">
                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">User
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class="col-sm-9">  
                            <div class='input-group date' id='selected' style="display: inline;" >
                                   <select required name="user_id" style="width:300px;" id="users" class="form-control  select2" style="">
                                    <option value="-1" disabled  data-foo="Select User" selected>Select User</option>
                                      @foreach ($users as $user)
                                    <option value="{{$user->id}}" @if(isset($sales_order_request) && $sales_order_request == $user->id)selected @endif data-foo="{{$user->phone}}">{{$user->name}}</option>
                                    @endforeach
                                    </select>
                            </div>
                            </div>
                            <div class="col-sm-3" style="display: inline;">
                              <?php $adduser = url('/admin/users/create'); ?>
                          <!-- sales_order_request -->
                              <a href="{{$adduser}}?sales_order_request=1"}
                                 class=" button btn btn-primary ">Create User
                              </a>
                            </div>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Address
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class="col-sm-8">
                                <div class='input-group date' id='' style="display: inline;">
                                  <select required name="address_id" id="address" class="form-control" >
                                      <option value="-1" disabled selected>Select Address</option>
                                    </select>
                                    <input type="text" hidden="hidden" id="district" name="district_id"/>
                                    <input type="text" hidden="hidden" id="token" name="token"/>
                                </div>
                              </div>
                                <div class="col-sm-4" style="display: inline;">
                              <?php $add_address = url('/admin/address/create'); ?>

                              <a href="#" 
                                 class=" button btn btn-primary " id="create_address">Create Address
                              </a>
                            </div>
                              
                            </div>
                        </div>
                    </div>

  <hr>
                      <div class="row" style="margin-left: 20px;"><span><b>Add Items</b></span></div>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="col-sm-12">  
                            <div class="col-sm-6">
                              <div class="col-sm-4">
                                <select required name="items[]" style="width:300px;" id="items1" class="form-control selectpickerr select2 item_data" style="" >
                                 <option value="" disabled  data-foo="Select Item" selected>Select Item</option>
                                   @foreach ($products as $product)
                                 <option value="{{$product->id}}"  data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                 @endforeach
                                 </select>
                              </div>
                              <div class="col-sm-2" style="float: right;right: 40px;">
                                <button style="margin-left: 0px;height: 28px;" class="btn btn-primary" type="button" id="newDate"><i class="fa fa-plus"></i></button> &nbsp;&nbsp;
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row" style="margin-left:20px;"> 
                          <div class="row row-horizon" id="cart_data" style="width: 100%">
                          </div>
                      </div>                     
                            <hr>

                     <div class="row">  
                      <div class="col-lg-6">
                                                 <div class="col-sm-12">
                                                   <label style="margin-bottom: 0;"  class="form-group" for="from">Time Section
                                                   </label>
                                                 </div>
                                                 <div class="col-sm-12" >
                                                   <div class='input-group date' id='' style="display: inline;">
                                                     <select required name="timesection_id" id="timesection_id" class="form-control" >
                                                         <option value="-1" disabled selected>Select Time Section</option>
                                                         <?php foreach ($time_sections as $time_section) { ?>
                                                         <option value="{{$time_section->id}}">{{$time_section->name}}</option>
                                                         <?php } ?>
                                                       </select>
                                                   </div>
                                                 </div>
                                             </div>
                            <div class="col-lg-6" >
                              <div class="col-sm-12">
                                <label style="margin-bottom: 0;"  class="form-group" for="from">Shipping Rate
                                </label>
                              </div>
                              <div class="col-sm-12">
                                <input type="number"  step="0.01" readonly="readonly" class="form-control" id="shipping_rate" name="shipping" value="{{$shipping_rate}}" >
                              </div>
                            </div>
                         
                     </div>

                     <div class="row">  

                        <div class="col-lg-6" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">PromoCode
                            </label>
                          </div>
                          <div class="col-sm-12">                           
                              <input type="text"   id="promocode" class="form-control promocode"  name="promocode" >
                              
                          </div>
                        </div>


                        <div class="col-lg-6" id="validate" style="display: none;" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">PromoCode Validate
                            </label>
                          </div>
                          <div class="col-sm-12">
                              <input type="text"   id="promocode_response" disabled="disabled" class="form-control promocode" id="shipping_rate" name="promocode" value="" >                           
                              
                          </div>
                        </div>
                     </div>

               

                     <input type="text" hidden="hidden" name="sales_order_request" value="@if(isset($sales_order_request)){{$sales_order_request}}@endif">

                     <div class="row">
                       <div class="col-lg-6" style="left:200px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total With Shipping Fees
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="0">

                                  </div>
                              </div>


                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-32"><button type="submit" id="save" style="margin-left: 12px" class="btn btn-primary">Save</button>
                          <div class="col-sm-32">
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
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>
@include('admin.sales_orders.food_scripts')

<!-- JAVASCRIPT AREA -->
</body>
</html>