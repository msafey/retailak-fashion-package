<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<style> 
#sortable1{
    margin-left: 10px;
}


#sortable1 li, #sortable2 li,#sortable3 li ,#sortable4 li {
  margin: 0 5px 5px 5px;
      padding: 5px 5px;
      font-size: 1.2em;
      height: 55px;
}

#sortable1 {height:300px; width:18%;}
#sortable1 {overflow:hidden; overflow-y:scroll;}
#sortable1, #sortable2 , #sortable3,#sortable4{
  border: 1px solid #eee;
  width: 142px;
  min-height: 20px;
  list-style-type: none;
  margin: 0;
  padding: 5px 0 0 0;
  float: left;
  margin-right: 10px;
}</style>

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

                <div class="modal fade bs-example-modal-sm" id="statusModal" tabindex="-1" role="dialog"
                     aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirmation
                            </div>
                            <div class="modal-body">
                                Are you Sure That You Want To Activate This User ?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="statusItem" title="Hapus"><i
                                            class="glyphicon glyphicon-ok"></i>Activate User</a>

                            </div>
                        </div>

                    </div>
                </div>
                        



                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                        Users
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Users
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>AAA</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row"> 
                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                                  <div class="card-box tilebox-one">
                        
                                        <i class="ion-android-data pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Total</h6>
                                        <h2 class="m-b-20"  id="count_pending">{{$total}}</h2>
                                  </div>
                              </div>

                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                                  <div class="card-box tilebox-one">
                                    
                                        <i class="ion-social-foursquare pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Imported Successfully</h6>
                                        <h2 class="m-b-20"  id="count_delivered">{{$imported}}</h2>
                                  </div>
                              </div>

                                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
                                  <div class="card-box tilebox-one">
                                        <i class="ion-close-circled pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Failed To Import</h6>
                                        <h2 class="m-b-20" id="count_cancelled">{{$failed}}</h2>
                                  </div>
                              </div>
                            
                             <ul style="width: 100%;margin-bottom: 50px;" id="sortable1" class="connectedSortable">
                              <li style="background-color:white">  
                                <div class="col-sm-4">  
                                  <b> Index </b>
                                </div>
                                <div class="col-sm-4">
                                 <b>   Phone </b>
                                </div>
                                <div class="col-sm-4" >  
                                  <b>Error</b>
                                </div>
                               
                              </li>
                               @foreach($insufficient as $k => $error) 
                                  @foreach($error as $key => $value) 
                                      <li style="background-color: lightgrey;">
                                        <div class="col-sm-4" >  
                                         <b>   {{$key}} </b>
                                        </div>
                                        <div class="col-sm-4">
                                            <b> {{$value['phone']}}</b>
                                        </div>
                                        <div class="col-sm-4"  >  
                                           <p style="color: red;"><b>  {{$value['error']}}</b></p>
                                        </div>
                                        
                                      </li>                                     
                                  @endforeach
                               @endforeach



                            </ul>

                        </div> 


                    </div>


                    <!-- <label for="  ">Failed</label>
                    <table> 
                        <thead>
                          <th> Ahmed</th> 
                          <th> Ahmed</th> 
                          <th> Ahmed</th> 
                          <th> Ahmed</th> 
                        </thead>
                        <tbody>
                       
                        </tbody>

                    </table> -->
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

<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>

<script type="text/javascript">
  
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
