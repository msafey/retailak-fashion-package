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
    <!-- Switchery css -->


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
                        Edit District
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Districts
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

                {!! Form::open(['url' => '/admin/districts/'.$district->id,
                'method'=>'PATCH',
                 'class'=>'form-hirozontal ',
                 'id'=>'demo-form','files' => true,
                 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="district_ar">District Name (Ar)<span style="color:red;">*</span>:</label>
                                <input required data-parsley-maxlength="70" name="district_ar" type="text"
                                       class="form-control" id="district_ar" value="{{$district->district_ar}}"
                                       placeholder="District Name (Ar)"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="district_name">District Name (En)<span style="color:red;">*</span>:</label>
                                <input required data-parsley-maxlength="70" name="district_en" type="text"
                                       class="form-control" id="district_en" value="{{$district->district_en}}"
                                       placeholder="District Name (En)"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="parent_district">Parent districts <span style="color:red;">*</span>:</label>
                                <select name="parent_id"  class="col-md-2 form-control">
                                    <option value="0">Choose parent district ...</option>
                                    @foreach($parentDistricts as $parentDistrict)
                                        <option value="{{$parentDistrict->id}}"
                                                @if ($district->parent_id == $parentDistrict->id) selected @endif>
                                            {{$parentDistrict->district_en	}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="shipping_role">Shipping Rule <span style="color:red;">*</span>:</label>
                                <select name="shipping_role" required class="col-md-2 form-control">
                                    <option value="">Choose Shipping Rule...</option>
                                    @foreach($shippingrules as $rule)
                                        <option value="{{$rule->id}}"
                                                @if ($district->shipping_role == $rule->id) selected @endif>
                                            {{$rule->shipping_rule_label}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="dynamic-rule-inputs" class="col-12">
                            @forelse($district->shipping as $shipping)
                                <div class="dynamic-rule input-group mb-3">

                                    <div class="form-group col-sm-3">
                                        <label for="from_weight">
                                            From weight <span style="color:red;">*</span>:</label>
                                        <input required name="from_weight[]" type="number"
                                               class="form-control" id="from_weight"
                                               value="{{$shipping->pivot->from_weight }}"/>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="to_weight">
                                            To weight<span style="color:red;">*</span>:</label>
                                        <input required name="to_weight[]" type="number"
                                               class="form-control" id="to_weight"
                                               value="{{ $shipping->pivot->to_weight }}"
                                        />
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="shipping_rule_id">Shipping Rule <span
                                                    style="color:red;">*</span>:</label>
                                        <select name="shipping_rule_id[]" id="shipping_rule_id"
                                                class="col-md-2 form-control">
                                            <option value="">Choose Shipping Rule...</option>
                                            @foreach($shippingrules as $rule)
                                                <option value="{{$rule->id}}"
                                                        @if ($shipping->pivot->shipping_rule_id == $rule->id) selected @endif>
                                                    {{$rule->key}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <a class="btn btn-danger remove-rule-div m-btn m-btn--icon btn-sm m-btn--icon-only align-self-center mt-3">
                                        <i class="fa fa-minus"></i>
                                    </a>

                                </div>
                            @empty
                                <div class="dynamic-rule input-group mb-3">

                                    <div class="form-group col-sm-3">
                                        <label for="from_weight">
                                            From weight <span style="color:red;">*</span>:</label>
                                        <input required name="from_weight[]" type="number"
                                               class="form-control" id="from_weight" value="{{ old('from_weight') }}"/>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="to_weight">
                                            To weight<span style="color:red;">*</span>:</label>
                                        <input required name="to_weight[]" type="number"
                                               class="form-control" id="from_weight" value="{{ old('to_weight') }}"
                                        />
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="shipping_role_id">Shipping Rule <span
                                                    style="color:red;">*</span>:</label>
                                        <select name="shipping_rule_id[]" required class="col-md-2 form-control">
                                            <option value="">Choose Shipping Rule...</option>
                                            @foreach($shippingrules as $rule)
                                                <option value="{{$rule->id}}"
                                                        @if (old('shipping_rule_id') == $rule->id) selected @endif>
                                                    {{$rule->key}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <a class="btn btn-danger remove-rule-div m-btn m-btn--icon btn-sm m-btn--icon-only align-self-center mt-3">
                                        <i class="fa fa-minus"></i>
                                    </a>

                                </div>

                            @endforelse
                        </div>


                        <a class="btn btn-primary add-rule m-btn m-btn--icon btn-sm">
                            <i class="la la-plus"></i> add more
                        </a>
                    </div>


                    <div class="row">
                        <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                    class="zmdi zmdi-plus-circle-o"></i>
                            Update District
                        </button>
                    </div>

                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>


</div>


</div>


@include('layouts.admin.footer')

<!-- End Footer Area-->
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>

<script>

    var template = $('#dynamic-rule-inputs .dynamic-rule:first').clone();

    var rulesCount = 1;
    $newId = 0;

    $('body').on('click', '.add-rule', function () {
        //increment
        rulesCount++;
        //loop through each input
        var section = template.clone().find(':input').each(function () {
            //set id to store the updated image number
            $newId = this.id + rulesCount;
            //update for label
            $(this).prev().attr('for', $newId);
            //update id
            this.id = $newId;

        }).end()
        //inject new rule
            .appendTo('#dynamic-rule-inputs');

        var idNumber = ($newId.replace(/[^0-9]/gi, ''));

        $('#from_weight' + idNumber).val('');
        $('#to_weight' + idNumber).val('');
        $('#shipping_rule_id' + idNumber).val('');

        return false;
    });
    //remove image
    $('#dynamic-rule-inputs').on('click', '.remove-rule-div', function () {
        //fade out image
        $(this).parent().fadeOut(300, function () {
            //remove parent element (main section)
            $(this).empty();
            return false;
        });
        return false;
    });
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>