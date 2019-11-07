<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')
    <script src="http://malsup.github.com/jquery.form.js">
    </script>
    <!-- App Favicon -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css"/>
    <link href="{{url('public/lou/css/multi-select.css')}}" media="screen" rel="stylesheet" type="text/css">

    <style type="text/css">
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            display: none;
        }

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
                        Add  Address
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Add Address
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

                <div class="card card-block">

                    <div style="margin-left: 5px" class="card-text">
                        <hr>

                        <div class="row" style="margin-left: 10px;">
                            <h2><b><u>Address</u></b></h2>
                        </div>


                        <div class="row">
                        <!--       <div class="col-lg-3">
                                            <div class="col-lg-12">
                                                <label style="margin-bottom: 0;" class="form-group" for="from">Title
                                                </label>
                                            </div>
                                            <div class="col-lg-12" style="margin-top: 0px">
                                                <div class='input-group date' style="display: inline;" id=''>
                                                <input class="form-control"  id="address_title" name="address_title"
                                                    placeholder="" required="required" type="text"   value="{{ old('address_title') }}" />
                                                </div>
                                            </div>
                                        </div>
 -->
                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Street
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" id="street" name="street"
                                               placeholder="" required="required" type="text"
                                               value="{{ old('street') }}"/>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="col-lg-12" style="">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Building Number
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px;">
                                    <div class='input-group date' id='' style="display: inline;  text-align: right">
                                        <input class="form-control" pattern="^[0-9]+$" id="building_number"
                                               name="building_number" min="0" placeholder="" required="required"
                                               type="number" value="{{ old('building_number') }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Floor Number
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" min="0" id="floor_number" step=".01"
                                               name="floor_number"
                                               placeholder="" required="required" type="number"
                                               value="{{ old('floor_number') }}"/>

                                    <!--   <select name="floor_number" id="floor_number" class="form-control" title="الطابق">
                                                                @for($i=0 ; $i<50 ; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                        </select> -->
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                            @if(isset($sales_order_request))
                                <input type='text' value="{{$sales_order_request}}" name="sales_order_request"
                                       hidden="hidden" class="form-control">
                            @endif
                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Apartment Number
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" min="0" id="apartment_number"
                                               name="apartment_number"
                                               placeholder="" required="required" type="number"
                                               value="{{ old('apartment_number') }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Nearest Land Mark
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" id="nearest_landmark" name="nearest_landmark"
                                               placeholder="" required="required" type="text"
                                               value="{{ old('nearest_landmark') }}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">City
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" id="city" name="city"
                                               placeholder="" required="required" type="text"
                                               value="{{ old('city') }}"/>
                                    </div>
                                </div>
                            </div>
                        <!--  <div class="col-lg-3">
                                            <div class="col-lg-12" style="">
                                                <label style="margin-bottom: 0;" class="form-group" for="from"> Address Phone
                                                </label>
                                            </div>
                                            <div class="col-lg-12" style="margin-top: 0px;">
                                                <div class='input-group date' id='' style="display: inline;  text-align: right">
                                                  <input class="form-control" pattern="^[0-9]+$" id="address_phone" name="address_phone" min="0" placeholder="" required="required" type="number"   value="{{ old('address_phone') }}" />
                                                </div>
                                            </div>
                                        </div>        -->
                            <!-- </div> -->
                            <div class="row">


                                <div class="col-lg-6">
                                    <div class="col-lg-12">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Districts
                                        </label>
                                    </div>
                                    <div class="col-lg-12" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <select required name="region" id="region" class="form-control">
                                                <option disabled selected>Select District</option>
                                                @foreach($districts as $district)
                                                    <option
                                                        value="{{$district->id}}">{{$district->district_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <input type="text" hidden="" name="token" id="token" value="{{$token}}">
                            <input type="text" hidden="" name="create" id="create" value="{{$create}}">
                        </div>
                        <div class="row">

                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Latitude
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <!-- <div class='input-group date' style="display: inline;" id=''> -->
                                    <input class="form-control" id="lat" name="lat"
                                           placeholder="" required="required" type="text" value="{{ old('lat') }}"/>
                                    <!-- </div> -->
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Longitude
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input class="form-control" id="longitude" name="lng"
                                               placeholder="" required="required" type="text" value="{{ old('lng') }}"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-32">
                                <button type="button" id="save" style="margin-left: 12px" class="btn btn-primary">Save
                                </button>
                            </div>
                        </div>
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

<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script>
<script src="{{url('public/lou/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script>

    $('#save').on('click', function () {

// var title = $('#address_title').val();
        var street = $('#street').val();
        var building_number = $('#building_number').val();
        var floor_number = $('#floor_number').val();
        var apartment_number = $('#apartment_number').val();
        var nearest_landmark = $('#nearest_landmark').val();
        var city = $('#city').val();
// var address_phone = $('#address_phone').val();
        var region = $('#region').val();
        var lng = $('#lng').val();
        var lat = $('#lat').val();
        var token = $('#token').val();
// console.log(lat);
        if (region === null || token === "") {
            alert('Please Complete Required Form Inputs');
            return false;
        }

        <?php $url = url('/admin/sales-orders/create'); ?>
        <?php $user_details = url('/admin/user/details/' . $user->id); ?>

        // var myJSON = JSON.stringify(items_array_object);
        $.ajax({
            headers: {
                'lang': 'en',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'token': token

            },
            url: "{{URL::to('api/address')}}",
            type: "POST",
            data: {
                // 'address_title':title,
                'street': street,
                'building_number': building_number,
                'floor_number': floor_number,
                'apartment_number': apartment_number,
                'nearest_landmark': nearest_landmark,
                'city': city,
                // 'address_phone':address_phone,
                'district_id': region,
                'lng': lng,
                'lat': lat

            },
            success: function (data) {
                if (create == 1) {
                    window.location = '<?php echo $url ?>';

                } else {
                    window.location = '<?php echo $user_details ?>';
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>