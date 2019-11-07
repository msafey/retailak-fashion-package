<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
    <style>
        .color{
            color: #901a1d;
        }
    </style>
</head>


<body>

<div style="background: #901a1d;" class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="account-bg">
        <div style="border: #901a1d" class="card-box m-b-0">
            <div class="text-xs-center m-t-20">
                <a href="#" class="color logo">
                    {{-- <i class="color zmdi zmdi-group-work icon-c-logo"></i> --}}
                    <img src="{{ url('/public/imgs/redlogo.png') }}" height="60" alt="">
                    {{-- <span  class="color"> Retailak</span> --}}
                </a>
            </div>
            <div class="m-t-10 p-20">

                <form class="m-t-20" method="POST" action="{{ url('/reset/password') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                        <div class="col-xs-12">
                            <input class="form-control" id="password" type="password" name="password" required="" placeholder="Password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group row">
                        <div  class="color col-md-12">
                            <input id="password-confirm" type="password" class="form-control" placeholder="confirm password" name="password_confirmation" required>
                        </div>
                    </div>


                    <div class="form-group text-center row m-t-10">
                        <div class="col-xs-12">
                          </div>
                    </div>


                    <div class="form-group text-center row m-t-10">
                        <div class="col-xs-12">
                            <button style="background-color:#901a1d; color:#fff;" class=" btn btn-success btn-block waves-effect waves-light" type="submit">Submit</button>
                            <input type="hidden" name="token" value="{{$user->token}}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-xs-12">
                            <div  class="color col-sm-12">

                                <label for="">
                                Enter Your New Password And Press Submit
                                </label>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end card-box-->

</div>
<!-- end wrapper page -->


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{url('public/admin/js/jquery.min.js')}}"></script>
<script src="{{url('public/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{url('public/admin/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/admin/js/detect.js')}}"></script>
<script src="{{url('public/admin/js/fastclick.js')}}"></script>
<script src="{{url('public/admin/js/jquery.blockUI.js')}}"></script>
<script src="{{url('public/admin/js/waves.js')}}"></script>
<script src="{{url('public/admin/js/jquery.nicescroll.js')}}"></script>
<script src="{{url('public/admin/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{url('public/admin/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('public/admin/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>

</body>
</html>