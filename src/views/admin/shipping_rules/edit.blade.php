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

    </style>
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
                       Edit Shipping Rule
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Shipping Rules
                @endslot
                You are not allowed to access this resource!
                @endcomponent              <!--End Bread Crumb And Title Section -->
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
        {!! Form::open(['url' => ['/admin/shipping-rules', $shipping_rule->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rule Label
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                            <input type='text' value="{{$shipping_rule->shipping_rule_label}}" required name="shipping_rule_label" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Key
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                            <input type='text' value="{{$shipping_rule->key}}" required name="key" class="form-control">
                                        </div>
                                    </div>
                                </div>                              
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Rate
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                            <input type='number' min="0" step="0.01" value="{{$shipping_rule->rate}}" required name="rate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Calculate Based On
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id='datetimepicker1'>
                                                <select required name="calculate_based_on" class="form-control" >
                                                <option  value="Fixed" @if($shipping_rule->calculate_based_on == 'Fixed')selected @endif>Fixed</option>

                                                <option  value="Net total" @if($shipping_rule->calculate_based_on == 'Net total')selected @endif>Net total</option>

                                                <option  value="Net Weight" @if($shipping_rule->calculate_based_on == 'Net Weight')selected @endif>Net Weight</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-3">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Disabled?
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                         <label class="switch">
                                  <input type="checkbox" value="1"  @if($shipping_rule->disabled ==1)checked @endif name="disabled">
                                  <span class="slider round"></span>
                                </label>
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

<!-- JAVASCRIPT AREA -->
</body>
</html>