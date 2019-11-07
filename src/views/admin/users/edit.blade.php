<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
    <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/>

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
                       Edit Customer
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Customers
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


                <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 card card-block">

        {!! Form::open(['url' => ['/admin/users', $user->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}
  <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
            <br>
                  <div class="col-sm-6" >
                    <label for="name" class="" >Name :<span style="color:red;">*</span></label>
                    <input name="name" value="{{$user->name}}"  type="text" style=""  placeholder="Customer Name" class="form-control" id="name" placeholder="" required="required" data-parsley-maxlength="150"/>
                </div>

                  <div class="col-sm-6" >
                    <label for="phone" class="" > Phone :<span style="color:red;">*</span></label>
                    <input name="phone" disabled="disabled" value="{{$user->phone}}"  type="text"  placeholder="phone" class="form-control" id="phone" placeholder="" title="" required="required" data-parsley-maxlength="150"/>
                </div>

      <div class="col-sm-6" >
          <label for="email" class="" > Email :<span style="color:red;">*</span></label>
          <input name="email"  value="{{$user->email}}"  type="email"  placeholder="E-mail" class="form-control" id="email"  required="required" type="email" data-parsley-trigger="change" />
      </div>

           

        </div>



<!--     <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
            <br>
                 <div class="col-sm-6" >
                   <label for="email" class="" >Email :<span style="color:red;">*</span></label>
                   <input name="email"  value="{{$user->email}}"  type="text"   placeholder="Email Address" class="form-control" id="name" placeholder="" title="البريد الألكتروني" required="required" data-parsley-maxlength="150"/>
               </div>
    
      </div> -->
<hr>
@if(isset($store_details))
      <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
        <hr>

              <br>
                  <div class="col-sm-6" >
                     <label for="store_name" class="" >Store Name :<span style="color:red;">*</span></label>
                     <input name="store_name"  value="{{$store_details->store_name}}"  type="text"   placeholder="Store Name" class="form-control" id="store_name" placeholder="" title="Store Name" required="required" data-parsley-maxlength="150"/>
                 </div>
                                 <div class="col-sm-6" >
                                      <label for="shop_type_id" class="" >Shop Type :<span style="color:red;">*</span></label>
                                    <select name="shop_type_id" id="shop_type_id" class="form-control" title="Shop Type">
                                      @foreach($shop_types as $type)
                                          <option value="{{$type->id}}" @if($type->id == $store_details->shop_type_id) selected @endif>{{$type->type}}</option>
                                      @endforeach
                                      </select>
                                 </div>
                                 
                  
        </div>


       


        <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
              <input type="text" hidden="" name="store_details" value="{{$store_details->id}}">
                <br>
                <div class="col-lg-6" >
                  <br>
                  <!-- <div class="col-sm-3 "> -->
                      <label for="tax_card">Taxs Card </label>
                      <input type="file" id="upload" name="tax_card"  class="form-group">
                  <!-- </div> -->
                 
                  <br>
                  <div class="col-lg-3 ">
                  <img style="height: 150px;width: 250px;" id="img" src="@if(isset($store_details->tax_card)){{$store_details->imgThumbPath($store_details->tax_card)}}@else {{url('public/imgs/default.jpg')}} @endif"/>

                  </div>
                </div>
                <div class="col-lg-6" >
                  <br>
                  <!-- <div class="col-sm-2 "> -->
                      <label for="commercial_register">Commercial Register </label>
                      <input type="file" id="imgInp" name="commercial_register"  class="form-group">
                  <!-- </div> -->
                  <br>
                  <div class="col-lg-3 u">
                   
                  <img style="height: 150px;width: 250px;" id="blah" src="@if(isset($store_details->commercial_register)){{$store_details->imgThumbPath($store_details->commercial_register)}}@else {{url('public/imgs/default.jpg')}} @endif"/>

                  </div>
                </div>

    
          </div>

          @endif

          <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
<br>
           
              <div class="col-sm-3">
                  <br>
                  <button type="submit" class="btn btn-primary"><i
                              class=""></i>
                    Save
                  </button>
                  <button id="back" onclick="history.go(-1);" type="button" class="btn btn-default"><i
                              class="zmdi "></i>
                    Back
                  </button>
              </div>
        </div>
                {!! Form::close() !!}
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



<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('public/admin/plugins/select2/js/select2.js')}}"></script>
<script src="{{url('/public/')}}/prasley/parsley.js"></script>

<script>
    var resizefunc = []
    $(document).ready(function(){
        $(".select2").select2({
            'width': '100%'
        });
    });



       $(document).ready(function() {
        $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
      });


</script>
<script type="text/javascript">
        function preview(input) {
         if (input.files && input.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) { $('#img').attr('src', e.target.result);  }
           reader.readAsDataURL(input.files[0]);     }   }

       $("#upload").change(function(){
         $("#img").css({top: 0, left: 0});
           preview(this);
       });



       function readURL(input) {

         if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function(e) {
             $('#blah').attr('src', e.target.result);
           }

           reader.readAsDataURL(input.files[0]);
         }
       }

       $("#imgInp").change(function() {
         readURL(this);
       });
    </script>

  

<!-- Laravel Javascript Validation -->


<!-- JAVASCRIPT AREA -->
</body>
</html>