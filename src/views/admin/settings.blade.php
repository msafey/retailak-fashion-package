<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
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
                        Settings
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Settings
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if( Session::has('success') )
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif

                        {!! Form::model($settings) !!}

                        <div class="form-group">
                            {!! Form::label('min_amount','Minimum Selling Amount') !!}
                            {!! Form::text('min_amount',null,['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('max_amount','Maximum Selling Amount') !!}
                            {!! Form::text('max_amount',null,['class'=>'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('note_en','Note En') !!}
                            {!! Form::text('note_en',null,['class'=>'form-control']) !!}
                        </div>

                            <div class="form-group">
                                {!! Form::label('note_ar','Note Ar ') !!}
                                {!! Form::text('note_ar',null,['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group row"  style="margin-left: 5px;">
                                <label for="">Expirtaion Days</label>
                                <input type="text" class="form-control" name="expiration_days" value="{{$settings->expiration_days}}">
                            </div>

                            <div class="form-group row"  style="margin-left: 5px;">
                                <label for="">Free Shipping</label>
                                <input type="checkbox" id="freeshipping" value="1" @if($settings->free_shipping == 1) checked @endif name="freeshipping">
                            </div>
                            <div class="form-group row" @if($settings->free_shipping != 1)style="display:none;margin-left:5px"@else style="margin-left: 5px;" @endif id="applied_amount" >
                                <label for="">Applied Amount</label>
                                <input type="text" class="form-control" @if($settings->free_shipping == 1 && isset($settings->applied_amount)) value="{{$settings->applied_amount}}" @endif name="applied_amount">
                            </div>


                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {!! Form::submit('Update',['class'=>'btn btn-success']) !!}

                        {!! Form::close() !!}
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
<script type="text/javascript">
    $('#freeshipping').on('click',function(){
        if ($(this).is(':checked')) {
            $('#applied_amount').css('display', 'block');
        } else {
            $('#applied_amount').css('display', 'none');
        }
    });
</script>
<!-- JAVASCRIPT AREA -->
</body>

</html>