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
                       Edit Slab
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Slabs
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
        {!! Form::open(['url' => ['/admin/slabs', $slab->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">


                
                        <div style="margin-left: 5px" class="card-text">


                            <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Slab Name :<span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <input type='text' value="{{ $slab->slab_name }} " required name="slab_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Minimum Money<span style="color:red;">*</span> 
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px; float:right; text-align: right">
                                            <div class='input-group date' id='datetimepicker1' style="display: inline;">
                                                <input type='text' required min="0" name="min_amount_money" value="{{ $slab->min_amount_money }}" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                            </div>
                            
                          <div class="row">
                            <!--   <div class="col-sm-2 form-group" style="margin-left: 20px;">
                                  <b>Slab Discount Type</b>
                                  <input type="checkbox"  id="discountEnabler" checked="" data-plugin="switchery" data-color="#ff5d48"/>

                              </div> -->
                              <div class="col-sm-6" id="discountBox" >
                                  <div class="row">
                                      <div class="col-sm-6" style="margin:15px">
                                          <label for="percentage" class="c-input c-radio">
                                              <input type="radio" @if(isset($slab->discount_type) && $slab->discount_type == "percentage")checked @endif name="discount_type" id="percentage" value="percentage" >
                                              <span class="c-indicator"></span>Percentage </label>
                                          <label for="amount"  class="c-input c-radio">
                                              <input type="radio" @if(isset($slab->discount_type) && $slab->discount_type == "amount"  || $slab->discount_rate == 0)checked @endif name="discount_type"  id="amount" value="amount" >
                                              <span class="c-indicator"></span>Amount</label>

                                      </div>
                                      <div class="col-sm-2">
                                          <input type="number" step="any" required="" min="0" id="discount_rate"  value="{{$slab->discount_rate}}" name="discount_rate" style="width:80px;margin: 15px;">
                                      </div>
                                      <div class="col-sm-2" id="typeSympole" style="margin:18px">
                                        @if($slab->discount_type == 'amount' || $slab->discount_rate == 0)
                                            <h5> Pound(s)</h5>
                                        @else
                                            <h5> %</h5>
                                        @endif
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
        $(document).ready(function() {
              $('input[type=radio][name=discount_type]').change(function() {
                    if (this.value == 'percentage') {
                        $('#typeSympole').html('<h5> %</h5>');
                    }
                    else if (this.value == 'amount') {
                        $('#typeSympole').html('<h5> Pound(s)</h5>');
                    }

                });
                // $('#discountEnabler').change(function(){
                //     if(!$(this).is(":checked")){
                //         // $('#discount_rate').val(0);
                //     }
                //     $('#discountBox').toggle();

                // });
               $(document).on('keyup','#discount_rate',function () {

                        if($('input[name=discount_type]:checked').val() == 'percentage')
                        {
                            if ( (parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 0) || $(this).val() == '' ){
                              ;
                            }
                            else
                            {

                                $(this).val($(this).data("old"));
                            }

                        }else if($('input[name=discount_type]:checked').val() == 'amount'){
                            
                        }

               });
        });

    </script>

<!-- JAVASCRIPT AREA -->
</body>
</html>