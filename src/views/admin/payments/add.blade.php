<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
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
                        Add Payment
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Payments
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
                {!! Form::open(['url' => '/admin/payments/create', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                  <div class="row">


                        <div class="col-lg-5">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <input type="text" class="form-control " disabled="disabled"  value="{{$selected_company->name_en}}">

                              </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Warehouse
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <input type="text" class="form-control " disabled="disabled"  value="{{$selected_warehouse->name}}">

                              </div>
                            </div>
                        </div>
</div>
<div class="row"> 
                        
                        <div class="col-lg-5">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Posting Date:
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="posting_date" value="{{ old('date_created',Carbon\Carbon::today()->format('Y-m-d')) }}" class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="zmdi zmdi-calendar"></span>
                                </span>
                            </div>
                          </div>
                        </div>
 
                        <div class="col-lg-5">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Mode Of Payment:
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                 <select required name="payment_mode_id" class="form-control" >
                                    <option value="-1" disabled selected>Select Mode Of Payment</option>
                                    <?php foreach ($payment_methods as $method) { ?>
                                    <option value="{{$method->id}}" >{{$method->name}}</option>
                                    <?php } ?>
                                  </select>
                            </div>
                          </div>
                        </div>
                  </div>

                      <div class="row">
                            <div class="col-lg-12">
                              <div class="col-sm-12">

                            <table id="myTable"  class=" table order-list" style="width: 1000px;">
                            <thead>
                                <tr>
                                    <td>Items</td>
                                    <td>Quantity</td>
                                    <td>rate</td>
                                    <td>Total Amount</td>
                                  
                                </tr>
                            </thead>
                            <tbody id="tblrownew0" >
                                <?php $i=1; ?>
                            @foreach($item_details as $order_item)
                                <tr id="field{{$i}}">
                                    <td class="col-sm-3">
                                      <div class='input-group date' id='' style="display: inline;">
                                          <input type="text" disabled="disabled" class="form-control" value="{{$order_item->product_name}}">
                                      </div>                                    
                                    </td>

                                    <td class="col-sm-2">
                                        <input type="number" disabled="disabled"  name="" value="{{$order_item->qty}}" id="qty"  class="form-control    qty"/>

                                    </td>
                                   
                                    <td class="col-sm-2">
                                        <input  disabled="disabled"   value="{{$order_item->rate}}"    name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"   name="total_amount[]"  value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                    </td>
                                 </tr>
                                <?php $i++; ?>


                            @endforeach
                            
                            @foreach($parent_array as $key => $array)
                            <tr>
                              <td ><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>
                            </tr>
                            @foreach($array as $item)
                                <tr id="field{{$i}}">
                                    <td class="col-sm-3">
                                      <div class='input-group date' id='' style="display: inline;">
                                          <input type="text" disabled="disabled" class="form-control" value="{{$item->product_name}}">
                                      </div>                                    
                                    </td>

                                    <td class="col-sm-2">
                                        <input type="number" disabled="disabled"  name="" value="{{$item->qty}}" id="qty"  class="form-control    qty"/>

                                    </td>
                                   
                                    <td class="col-sm-2">
                                        <input  disabled="disabled"   value="{{$item->rate}}"    name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"   name="total_amount[]"  value="{{$item->total_amount}}"    id="total_amount" class="form-control total"/>
                                    </td>
                                 </tr>
                                <?php $i++; ?>


                            @endforeach
                            @endforeach

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

                      <div class="row">
                          <div class="col-lg-5">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                      <input type="text"  disabled="disabled" class="form-control " value="@if(isset($selected_tax)){{$selected_tax->title}}@endif">

                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-5">
                              <div class="col-lg-12" style="">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rule
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                              <div class='input-group date' style="display: inline;" id=''>
                                                      <input type="text" class="form-control "  disabled="disabled" value="@if(isset($selected_shipping_rule)){{$selected_shipping_rule->shipping_rule_label}}@endif">

                                              </div>
                                          </div>
                          </div> 

                      </div>

                      <input type="hidden" value="{{$_GET['invoice']}}" name="invoice_id">
                      <input type="hidden" value="{{$selected_warehouse->id}}" name="warehouse_id">


                     <div class="row">
                       <div class="col-lg-6" style=" left:250px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 50px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$grand_total_amount}}">

                                  </div>
                              </div>


                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-32"><button id="save" type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
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
     useCurrent: true;

 });

</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>