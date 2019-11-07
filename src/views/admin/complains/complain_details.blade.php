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

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


  

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
                        Complains
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Complains
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

                <?php $deliverdturl = url('/admin/delivery/orders/status');?>

                <?php $changestatus = url('/admin/delivery/order/status'); ?>
                <?php $urlshow = url('/admin/delivery/orders/'); ?>

        {!! Form::open(['url' => ['/admin/complain/details', $complain->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px;" class="card-text">

                    

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">User Name
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' value="{{$user->name}}" disabled="disabled" class="form-control">
                                        </div>
                                    </div>



                                </div>

                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">User Email
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' value="{{$user->email}}" disabled="disabled"  class="form-control">
                                        </div>
                                    </div>



                                </div>



                            </div>
                                <hr> 

                                <div class="row">   

                                    
                                    <div class="col-lg-6">
                                        <div class="col-sm-3">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Phone
                                            </label>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' value="{{$user->phone}}" disabled="disabled"  class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="col-sm-3">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Complain Ticket Id
                                            </label>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' value="{{$complain->id}}" disabled="disabled"  class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                               
                           <hr> 

                           <div class="row">   

                               
                               <div class="col-lg-12">
                                   <div class="col-sm-3">
                                       <label style="margin-bottom: 0;" class="form-group" for="from">Message
                                       </label>
                                   </div>
                                   <div class="col-sm-6" style="">
                                       <div class='input-group date' style="display: inline;" id=''>
                                           <textarea disabled="disabled" class="form-control">{{$complain->message}}</textarea> 
                                       </div>
                                   </div>
                               </div>

                              
                           </div>
                           <hr>

                           <div class="row">   

                               
                               <div class="col-lg-12">
                                   <div class="col-sm-3">
                                       <label style="margin-bottom: 0;" class="form-group" for="from">Answer
                                       </label>
                                   </div>
                                   <div class="col-sm-6" style="">
                                       <div class='input-group date' style="display: inline;" id=''>
                                           <textarea name="admin_answer" class="form-control">@if(isset($complain->admin_answer)){{$complain->admin_answer}}@endif</textarea> 
                                       </div>
                                   </div>
                               </div>

                              
                           </div>
<hr>

                           <div class="row">                                  
                               <div class="col-lg-12">
                                   <div class="col-sm-3">
                                       <label style="margin-bottom: 0;" class="form-group" for="from">Resolved
                                       </label>
                                   </div>
                                   <div class="col-sm-6" style="">
                                       <div class='input-group date' style="display: inline;" id=''>
                                    <input type="checkbox" name="resolved" @if(isset($complain->resolved) && $complain->resolved == 1)checked @endif value="1" data-plugin="switchery" data-color="#ff5d48"/>
                                       </div>
                                   </div>
                               </div>

                              
                           </div>

                          <hr>
                           
<br>
<br>

                                 <div class="row">
                                     <div class="col-sm-32"><button type="submit" id="save" style="margin-left: 12px" class="btn btn-primary">Save</button>
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


<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}

<!-- JAVASCRIPT AREA -->
</body>
</html>