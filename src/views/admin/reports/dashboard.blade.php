<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/chartist/dist/chartist.min.css')}}" rel="stylesheet"
          type="text/css"/>
          <!-- Font Awesome -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
          <!-- Bootstrap core CSS -->
          <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
          <!-- Material Design Bootstrap -->
          <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.11/css/mdb.min.css" rel="stylesheet">
          <!-- <link rel="stylesheet" href="assets/plugins/chartist/dist/chartist.min.css"> -->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <!-- DataTables -->
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
                        Couriers Reports
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Couriers
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
                <div class="row">


                        
                <!-- Add Company Button -->

            </div>



            <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            Confirmation
                        </div>
                        <div class="modal-body">
                            Are you Sure That You Want To Delete This Item ?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                        class="glyphicon glyphicon-trash"></i> Delete Item</a>

                        </div>
                    </div>

                </div>
            </div>
                       
                        <div class="card-text">
                            <div class="row">
                                <div class="col-lg-12"> 
                                     <div class="col-lg-6">
                                             <div style="width:100%;">
                                         <canvas id="line-chart1" width="800" height="450"></canvas>
                                                 </div>
                                     </div>
                                    <div class="col-lg-6" style="margin-top: 40px;">
                                            <div style="width: 100%"  class="col-lg-6">
                                                <div class="card-box tilebox-one">
                                                      <i class="ion-android-data pull-xs-right text-muted"></i>
                                                      <h6 class="text-muted text-uppercase m-b-20">Total Sales Orders Money</h6>
                                                      <h2 class="m-b-20" data-plugin="counterup" id="count_pending">{{array_sum($so_values)}}</h2>
                                                </div>
                                            </div>
                                            <div  class="col-lg-6">
                                                <div class="card-box tilebox-one">
                                            
                                                      <i class="ion-android-data pull-xs-right text-muted"></i>
                                                      <h6 class="text-muted text-uppercase m-b-20">Total Purchase Orders Money</h6>
                                                      <h2 class="m-b-20" data-plugin="counterup" id="count_pending">{{array_sum($po_values)}}</h2>
                                                </div>
                                            </div>
                                    </div>
                                                                        
                                </div>
                            </div>
                            <br>
                            <hr>    
                           
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6">
            <div style="width:100%;">
                <canvas id="line-chart" width="800" height="700"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div style="width:100%;">
                <canvas id="line-chart2" width="800" height="700"></canvas>
            </div>
        </div>
    </div>
    
</div>
<hr>
<div class="row">
  <canvas id="pie-chart" width="500" height="250"></canvas>
</div>
<hr>
<div class="row">
  <canvas id="pie-chart2" width="400" height="150"></canvas>
</div>
<hr>
<div class="row">
  <canvas id="pie-chart3" width="400" height="150"></canvas>
</div>
<hr>

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
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- JQuery -->

  
<script>

  var total_so_array_key = <?php echo json_encode($so_keys); ?>;
  var total_so_array = <?php echo json_encode($total); ?>;
  var total_so_array_values = <?php echo json_encode($so_values); ?>;

 var total_po_array_values = <?php echo json_encode($po_values); ?>;
// SO AND PO

           var colors_array = ['#3e95cd','#8e5ea2','#3cba9f','#c45850','#e8c3b9','#ff8c00','#0ddbd7','#848484'];
           var orders = [];
           pochart = {
             data: total_po_array_values,
              label: 'Purchase Order',
              borderColor:colors_array[2],
              fill: false
           }
           sochart = {
             data: total_so_array_values,
              label: 'Sales Order',
              borderColor:colors_array[1],
              fill: false

           }
                 orders.push(sochart);
                 orders.push(pochart);
              
           

      new Chart(document.getElementById("line-chart1"), {
        type: 'line',
        data: {
          labels: total_so_array_key,
          datasets: [...orders]
        },
        options: {
          title: {
            display: true,
            text: 'Sales And Purchase Orders Monthly Money'
          }
        }
      });



// Couriers

var courier_money = <?php echo json_encode($courier_money);?>;
var courier_orders = <?php echo json_encode($courier_orders);?>;
    var courier_money_array = [];
    var courier_orders_array = [];
      var j = 0;
      var colors = ['#3e95cd','#8e5ea2','#3cba9f','#c45850','#e8c3b9','#ff8c00','#0ddbd7','#848484'];
       $.each(courier_orders, function(key, value){ 
            chart = {
              data: value,
               label: key,
               borderColor:colors[j],
               fill: false
            }
            j = j+1;
                  courier_orders_array.push(chart);
            
      })
       var i = 0;

     $.each(courier_money, function(key, value){ 
          chart = {
            data: value,
             label: key,
             borderColor:colors[i],
             fill: false
          }
          i = i+1;
                courier_money_array.push(chart);
    })

     new Chart(document.getElementById("line-chart"), {
       type: 'line',
       data: {
         labels: total_so_array_key,
         datasets: [...courier_money_array]
       },
       options: {
         title: {
           display: true,
           text: 'Courier Monthly Orders Money'
         }
       }
     });

     new Chart(document.getElementById("line-chart2"), {
       type: 'line',
       data: {
         labels: total_so_array_key,
         datasets: [...courier_orders_array]
       },
       options: {
         title: {
           display: true,
           text: 'Courier Monthly Orders'
         }
       }
     });


var zones = [];
var zone_money = [];
var zone_orders = [];
@foreach($districts as $district)
<?php ?>
       zones.push("{{$district->district_en}} - [{{$district->count_of_orders}}]");
       zone_money.push("{{$district->total_money}}");
       zone_orders.push("{{$district->count_of_orders}}");

@endforeach
     // Zones Pie Chart

     new Chart(document.getElementById("pie-chart"), {
         type: 'pie',
         data: {
           labels: zones,
           datasets: [{
             label: "ZONES Total Money",
             backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850",'#3e95cd','#8e5ea2','#3cba9f','#c45850','#e8c3b9','#ff8c00','#0ddbd7','#848484'],
             data: zone_money
           }]
         },
         options: {
           title: {
             display: true,
             text: 'Districts Sales'
           }
         }
     });
     

  
var brand = [];
var brand_money = [];
@foreach($brands_total as $brand => $total)
<?php ?>
       brand.push("{{$brand}}");
       brand_money.push("{{$total}}");

@endforeach
     // Zones Pie Chart

     new Chart(document.getElementById("pie-chart2"), {
         type: 'pie',
         data: {
           labels: brand,
           datasets: [{
             label: "Brand Sales",
             backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850",'#3e95cd','#8e5ea2','#3cba9f','#c45850','#e8c3b9','#ff8c00','#0ddbd7','#848484'],
             data: brand_money
           }]
         },
         options: {
           title: {
             display: true,
             text: 'Brand Sales'
           }
         }
     });




     var company = [];
     var company_money = [];
     @foreach($companies_total as $company => $total)
     <?php ?>
            company.push("{{$company}}");
            company_money.push("{{$total}}");

     @endforeach
          // Zones Pie Chart

          new Chart(document.getElementById("pie-chart3"), {
              type: 'pie',
              data: {
                labels: company,
                datasets: [{
                  label: "Companies Sales",
                  backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850",'#3e95cd','#8e5ea2','#3cba9f','#c45850','#e8c3b9','#ff8c00','#0ddbd7','#848484'],
                  data: company_money
                }]
              },
              options: {
                title: {
                  display: true,
                  text: 'Companies Sales'
                }
              }
          });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>