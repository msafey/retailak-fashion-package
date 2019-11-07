<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>
<style>
  .col-xs-12 {
      width: 90% !important;
      margin-left: 15px;
      /*margin-bottom: 0;*/
  }
</style>
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-horizon/0.1.1/bootstrap-horizon.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />



       <!-- Latest compiled and minified bootstrap-select CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    
  
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
                        Checkout Order
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Sales Orders
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


               {!! Form::open(['url' => '/admin/checkout', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" style="width: 92%">

            @if(checkProductConfig('foods') == true)
             <div class="row" style="margin-left:20px;"> 
                          <div class="row row-horizon" id="cart_data" style="width: 100%">
                  @if(isset($food_data) && count($food_data)>0)
                    @foreach($food_data as $cart)
                      {!! $cart !!}
                    @endforeach
                  @endif
                </div>
            </div> 

            @else
                  <div class="row">
                        <div class="col-lg-12">
                          <div class="col-sm-12">

                        <table id="myTable"  class=" table order-list">
                        <thead>
                            <tr>
                                <td>Items</td>
                                <td>Quantity</td>
                                <td>rate</td>
                                <td>Total Amount</td>
                                
                            </tr>
                        </thead>
                        <tbody id="tblrownew0" >
                          @foreach($cartsdata as $data)
                            <tr id="field1">
                                <td class="col-sm-3">
                                  <div class='input-group date' id='' style="display: inline;">
                                       
                                  <input type="text" disabled="disabled" value="{{$data->product_name}}" id="qty"  class="form-control qty"/>
                                  </div>                                    
                                </td>
                                <td class="col-sm-2">

                                    <input type="number"  min="1"  disabled="disabled" value="{{$data->qty}}" id="qty"  class="form-control qty"/>
                                </td>
                                <td class="col-sm-2">
                                    <input  disabled="disabled"     value="{{$data->rate}}" id="rate"   class="form-control"/>
                                </td>
                                <td class="col-sm-2">
                                    <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$data->total_amount}}"  id="total_amount" class="form-control total"/>
                                </td>
                            </tr>
                            @endforeach

                           
                            @if(count($cartsparentdata)>0)
                             @foreach($cartsparentdata as $key => $order_item)
                                <tr>
                                  <td class="col-sm-4"><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>
                                </tr>
                            @foreach ($order_item as $product)
                            <tr id="field1">
                                <td class="col-sm-3">
                                  <div class='input-group date' id='' style="display: inline;">
                                       
                                  <input type="text" disabled="disabled" value="{{$product->product_name}}" id="qty"  class="form-control qty"/>
                                  </div>                                    
                                </td>
                                <td class="col-sm-2">

                                    <input type="number"  min="1"  disabled="disabled" value="{{$product->qty}}" id="qty"  class="form-control qty"/>
                                </td>
                                <td class="col-sm-2">
                                    <input  disabled="disabled"     value="{{$product->rate}}" id="rate"   class="form-control"/>
                                </td>
                                <td class="col-sm-2">
                                    <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$product->total_amount}}"  id="total_amount" class="form-control total"/>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endif
                            <hr>
                        </tbody>
                        <tfoot>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <br>      
                            </tr>
                            <tr>
                            </tr>
                        </tfoot>
                        </table>
                        <hr>

                        </div>
                        </div>
                  </div>
            @endif

            @if(isset($checkoutdata->note))

                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-sm-12"> <label for="note">Note</label></div>
                        <div class="col-sm-12">
                            <textarea name="note"  cols="30" rows="10">{{$checkoutdata->note}}</textarea>
                        </div>
                    </div>
                </div>
            @endif

@if(isset($checkoutdata->promocode_valid) && $checkoutdata->promocode_valid != '0')


                     <div class="row">  

                        <div class="col-lg-6" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">PromoCode Discount Rate
                            </label>
                          </div>
                          <div class="col-sm-12">                           
                              <input type="text" disabled="disabled"   class="form-control" value="{{$checkoutdata->discount_rate}} L.E" >
                          </div>
                        </div>

                        <div class="col-lg-6" id="validate" style="" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Shipping Rate
                            </label>
                          </div>
                          <div class="col-sm-12">
                            <input type="text"   id="promocode_response" disabled="disabled" class="form-control"   value='{{$checkoutdata->shipping_rate}} L.E' > 
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6" id="validate" style="" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Promocode Free Shipping? 
                            </label>
                          </div>
                          <div class="col-sm-12">
                            @if($checkoutdata->shipping == 0)
                            <input type="text"   id="promocode_response" disabled="disabled" class="form-control"   value='Yes' > 
                            @else
                            <input type="text"   id="promocode_response" disabled="disabled" class="form-control"  value='No' > 
                            @endif                        
                              
                          </div>
                        </div>
                        <div class="col-lg-6" id="validate" style="" >
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Total Promocode Discount 
                            </label>
                          </div>
                          <div class="col-sm-12">
                            <input type="text"   id="promocode_response" disabled="disabled" class="form-control"   value='{{$checkoutdata->shipping_rate+$checkoutdata->discount_rate}} L.E' > 
                                                  
                              
                          </div>
                        </div>
                     </div>

                     <div class="row">
                       <div class="col-lg-6" style="">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total Before Discount
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text"  style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$checkoutdata->grand_total_amount}}">

                                  </div>
                              </div>


                          </div>

                          <div class="col-lg-6" style="">

                                 <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                     <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total After Discount
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0">
                                     <div class='input-group' id='' style="display: inline;  text-align: right">
                                       <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$checkoutdata->total_amount_after_discount}}">

                                     </div>
                                 </div>


                             </div>
                      </div>




@else


                     <div class="row">
                       <div class="col-lg-6" style="left:200px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total 
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" value="{{$checkoutdata->grand_total_amount}}" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="0">

                                  </div>
                              </div>


                          </div>
                      </div>
@endif
                <input type="text" hidden="hidden" name="shipping" value="{{$checkoutdata->shipping}}">
                <input type="text" hidden="hidden" name="address_id" value="{{$checkoutdata->address_id}}">
                <input type="text" hidden="hidden" name="time_section_id" value="{{$checkoutdata->timesection_id}}">
                <input type="text" hidden="hidden" name="token" value="{{$checkoutdata->token}}">
                <input type="text" hidden="hidden" name="promocode" value="{{$checkoutdata->promocode_valid}}">


                      <div class="row">
                          <div class="col-sm-32"><button type="submit" id="save" style="margin-left: 12px" class="btn btn-primary">Checkout</button></div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>