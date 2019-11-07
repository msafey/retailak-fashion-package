<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>


    <style>

        .table td:nth-of-type(3) {
            width: 87px;

        }

        .table td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;

        }

        .table tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }

        .table2 td:nth-of-type(2) {
            width: 200px;
        }

        .table2 td:nth-of-type(3) {
            width: 170px;

        }

        .table2 td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;


        }

        .table2 tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }
    </style>

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
                        Run Sheets
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Run Sheets
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
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
                    <div class="card-title">

                <!-- Add Company Button -->
                <a href="{{url('/admin/products/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Product</a>
                <div class="col-sm-3">
                    <select id="item_group" class="form-control" >
                        <option disabled selected value="0">Select Category</option>
                        <option value="-1">All Categories</option>
                        @foreach($categories as $key=> $category)
                        <optgroup label="{{$key}}">
                            @foreach($category as $cat)
                            <option value="{{$cat->id}}">{{$cat->name_en}}-{{$cat->name}}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>

                <a href="#" id="order2" class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i>Order Products According To Category</a>
                <a href="{{url('admin/product/reordering')}}" id="order3" class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i>  Products Reorder</a>

<div class="row" style="margin-left:8px;margin-top: 10px; ">
  

                        <div class="col-sm-3"><input type="checkbox" name="active" value="1" checked  id="active" >Show Active Products</div>
                        <div class="col-sm-3">  <input type="checkbox" name="inactive" value="" id="inactive" >Show Inactive Products</div>
</div>
            </div>

                      

<hr>    
<input type="text" hidden="" name="orders[]" id="orders_id">
                        <div class="row">
                           
                        </div>




                     


                        <div class="row">


                        </div>

                        <div class="row">

                            <div class="col-md-8">

                                <label for="time_section"> Orders<span style="color:red;">*</span>:
                                </label>
                                <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Select</th>
                                    </tr>
                                </thead>
                            </table>
                     
                            </script>

                         

                        </div>
                        <div class="col-md-4">


                            <label for="time_section"> Selected Barcodes To Print:<span style="color:red;">*</span>
                            </label>

                                 <table id="colItemsTable" class="display" cellspacing="0" border="0" width="100%">
                                <thead><tr style=""><th>Product</th><th>Remove</th></tr></thead>
                                <tbody id="sortable" class="sortable">

                                
                                </tbody>
                            </table>
                        </div>
                    </div>







            <div class="row" >
                <div class="col-lg-12" id="print_images">
                    <!-- <table> 
                        <tbody> 
            
                        </tbody>

                    </table> -->
                    <!-- <div class="col-md-6" style="margin-left: 0px;">  
                        <div class="col-md-4">  
                            <span for="" style="margin-bottom: 0">V-neck Pullover (M)&nbsp;</span>
                        </div>
                        <div class="col-md-3">  
                                <span style="margin-left: 20px;">#1258</span>
                        </div>
                        
                    </div>
                    <div class="col-md-6" style="margin-left: 30px;">
                        <img src="{{url('public/imgs/products/barcodes/010000000022.png')}}">
                    </div>
                    <div class="col-md-6" style="margin-left: 80px">  
                        <span for="">2000.00 LE&nbsp;</span>
                    </div> -->
                </div>
            </div>




                        <div class="row">
                            <button style="margin-left: 10px" type="button" class="btn btn-primary save"><i
                                        class="fa fa-print"></i>
                                Print 
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

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>

<script src="{{url('/public/')}}/prasley/parsley.js"></script>


<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

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

<script type="text/javascript">
    // Date Picker


$('.save').on('click',function(){
    var item_codes = [];
    $('#sortable tr').each(function(){
        var attr_id = $(this).attr('id');
        item_codes.push(attr_id);
        // var url = '<?php echo url('public/imgs/products/barcodes/')?>';
        // var source = url+'/'+attr_id+'.png';
    });
    var url = "<?php echo url('public/imgs/products/barcodes/')?>";
    $.ajax({
      method: 'GET',
      url: '{!! route('getBarCodes') !!}',
      data: {'item_codes' : item_codes},
      success: function(response){
        var div = '';
            if(response.data.length >0){
                $.each(response.data,function(index,value){
                    var image = url+'/'+value.data.item_code+'.png';
                    // var source = url+'/'+attr_id+'.png';
                    div +='<div class="col-md-6" style="margin-top:20px;margin-left: 20px;"><div class="col-md-4"><span for="" style="margin-bottom: 0">SKU #'+value.data.item_code+'&nbsp;</span></div></div><div class="col-md-6" style="margin-left: 30px;"><img src='+image+'></div><div class="col-md-6" style="margin-left: 30px"><span for="">'+value.data.standard_rate+' LE&nbsp;</span></div>';
                  
                });
                $('#print_images').html(div);
                printDiv('print_images');

            }
            // images.forEach(function(image) {
            // $.each(images,function(image){
            //   var img = document.createElement('img');
            //   img.src = image;
            //   img.height = "45";
            //   img.width = "50";
            //   document.body.appendChild(img);
            // });

      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      // console.log(JSON.stringify(jqXHR));
      // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
    });



});

function printDiv(divName){
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;

        // window.onafterprint = function(){
           location.reload();
        // }

}

// function PrintElem(elem)
// {
//     var mywindow = window.open('', 'PRINT', 'height=400,width=600');
//     console.log(mywindow);
//     return false;
//     mywindow.document.write('<html><head><title>' + document.title  + '</title>');
//     mywindow.document.write('</head><body >');
//     mywindow.document.write('<h1>' + document.title  + '</h1>');
//     mywindow.document.write(document.getElementById(elem).innerHTML);
//     mywindow.document.write('</body></html>');

//     mywindow.document.close(); // necessary for IE >= 10
//     mywindow.focus(); // necessary for IE >= 10*/

//     mywindow.print();
//     mywindow.close();

//     return true;
// }


function ImagetoPrint(images)
{
    return "<html><head><script>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" + "pt></head><body onload='step1()'>\n" +images+
            "' /></body></html>";
}




function PrintImage(images)
{
    Pagelink = "about:blank";
    var pwa = window.open(Pagelink, "_new");
    pwa.document.open();
    pwa.document.write(ImagetoPrint(images));
    pwa.document.close();
}


    $(document).ready(function(){
        var item_group = 0;
        var active= document.getElementById("active").value;  
        var inactive=document.getElementById("inactive").value;
        var ckbox = $('#active');
        var ckbox1=$('#inactive');
        var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url:'{!! route('productsList') !!}',
                type:"GET",
                data: function(d){
                    d.item_group=document.getElementById('item_group').value;
                    d.active=document.getElementById('active').value,
                    d.inactive=document.getElementById('inactive').value;
                }
            },
            columns: [
               {
                    data: 'name_en', render: function (name1, name2, type, row) {
                        if(name1.length > 40){
                            name1 = name1.substr(0,38)+'...</span>';
                        }
                        if(type.name.length >40){
                            type.name = type.name.substr( 0, 38 )+'...</span>';
                        }
                        return name1 + '   <b>EN</b>' + ' <br> ' + type.name + '   <b>AR</b>'
                    }
                },
                {data: 'item_code', searchable: false, render: function (data,data2, row) {
                    var itemcodes = [];
                     $('#sortable tr').each(function() {
                           itemcodes.push(this.id)
                      });

                     if($.inArray(row.item_code,itemcodes) == -1)
                     {
                         return '<button  type="button" id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToSelected(\''+ row.item_code+'\',\''+ row.name_en+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                     }
                     else
                     {
                       return '<button type="button"  disabled=disabled id="Btn'+data+'" class="btn btn-primary addBtn" title="Add To Collection" onClick="addToSelected(\''+ row.item_code+'\',\''+ row.name_en+'\',\'Btn'+data+'\')"><i class="zmdi zmdi-plus"    ></i></a>';
                     }

                    }
                }
            ]
        });
    });
    function addToSelected(item_code,product_name,btnId){
        $('#sortable').append('<tr  style="border: 1px solid #dddddd;" id="'+item_code+'"><td>'+product_name+'</td><td><button value="'+btnId+'" class="btn deleteBtn btn-sm btn-danger"><i class="zmdi zmdi-delete"></i></button></td></tr>');
    }


    $(document).on('click','.addBtn',function(){
        $(this).attr('disabled','disabled');
    });


 $(document).on('click','.deleteBtn',function(e){
     var addBtnId = $(this).val();
      var itemId = $(this).closest('tr').attr('id');
      $("#"+addBtnId).removeAttr('disabled');
      $(this).closest('tr').remove();
      e.preventDefault();
});


 $('input').change(function () {
    if (ckbox.is(':checked')) {
       $('#active').val(1);
    } else {
       $('#active').val(0);
    }
    table.ajax.reload();
 });
 $('input').change(function () {
       if (ckbox1.is(':checked')) {
           $('#inactive').val(1);
           } else {
           $('#inactive').val(0);
       }
    table.ajax.reload();

   });

 $(document).ready(function () {
     $("[data-toggle='tooltip']").tooltip();
 });




 <?php $editurl = url('/admin/products/');?>
 <?php $changestatus = url('/admin/product/status'); ?>
 <?php $order = url('/admin/products');?>
 <?php $manageUrl = url('/admin/products/'); ?>
 <?php $details = url('/admin/product-details/');?>


    $('select').change(function () {
        item_group= document.getElementById("item_group").value;
        if(item_group == -1 || item_group == 0){
            $('#order2').addClass('disabled');  
        }else{
            $("#order2").attr('href', '{{$order}}'+'/'+item_group+'/order');
            $('#order2').removeClass('disabled');      
        }
          // if (ckbox.is(':selected')) {
          //    item_group= document.getElementById("item_group").value;
          // } 
       table.ajax.reload();

      });


    $(document).ready(function () {
        if(item_group == 0){
            $('#order2').addClass('disabled');
        }
        $('#reloadTableButton').click(function(){
          table.ajax.reload();
        });
    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>