<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

<!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />



       <!-- Latest compiled and minified bootstrap-select CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    
  
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
                        Bundles
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Bundles
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/product_bundles', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' required name="name" value=" {{old('name')}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Description
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                            <textarea class="form-control"  id="" name="description" rows="3">{{old('description_en')}}</textarea>                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-sm-12">
                                            <label style="margin-bottom: 0;"  class="form-group" for="from">Item Group
                                            </label>
                                        </div>
                                        <div class="col-sm-12" >
                                            <div class='input-group date' id='' style="display: inline;">
                                                <select required name="item_group"  id="item_group" class="form-control" >
                                                    <option value="0" disabled selected>Item Group</option>
                                                    <?php foreach ($item_groups as $group) { ?>
                                                        <option value="{{$group->id}}" >{{$group->name_en}}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                          <input type="text" id="inc" hidden="hidden" name="inc" value="1">
                              <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text1" style="display: block">
                                  <div class="row" id="products" class="ItemRow">
                                      <div class="row ItemRow" id="field1" style="margin-left: 0px;margin-top: 10px">
                              
                                          <div class="col-sm-4" >
                                            <label for="answers">Items : <span class="required-red">*</span></label>
                                            <select required name="items[]" id="items1" class="form-control selectpicker"  data-live-search="true"  data-show-subtext="true">
                                              <option value="-1" disabled selected>Select Item</option>
                                              <?php foreach ($products as $product) { ?>
                                              <option value="{{$product->id}}"  data-subtext="{{$product->name_en}}">{{$product->name}}</option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                          <div class="col-sm-4">
                                            <label for="answers">Quantity : <span class="required-red">*</span></label>
                                            <input name="qty[]" id="qty" type="number" class="form-control" />
                                          </div>
                                          <div class="col-sm-1">
                                              <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeItem"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                          </div>
                                      </div>
                                  </div>
                                  <button style="margin-left: 20px;margin-bottom: 10px;" class="btn btn-primary" type="button" id="newItem"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute 
                              </div>
                      </div>

                      <input type="hidden" value="{{$_GET['product']}}" name="product">


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
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>


    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
  
  var response_array=[];
  var items_array = [];
  var selected =[];
    $(function() {
      $('#items'+iv).selectpicker();
    });
    
    var iv = document.getElementById('inc').value;


    $("#item_group").change(function(e){
          var item_group= e.target.value;
              $.ajax({
                  method: 'GET', 
                  url: '{!! route('getItems') !!}', 
                  data: {'item_group' : item_group}, 
                  success: function(response){ 
                   // response_array.pushValues(response);
                      $('#items'+iv).empty().selectpicker("refresh");
                      // $("#items").val('default');
                      $('#items'+iv).append('<option value="-1" disabled selected>Select Item</option>' );
                      $.each(response, function( index, value ) {
                        response_array.push(value);
                        items_array.push(value);
                           $('#items'+iv).append('<option data-subtext="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>' ).selectpicker("refresh");
                      });
                  },
                  error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                      console.log(JSON.stringify(jqXHR));
                      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                  }
                });
    });

   $(document).ready(function () {
       function getLength() {
           var numItems = $('.ItemRow').length;
           if(numItems > 1){
               $('#removeItem').prop('disabled',false);
           }else{
               $('#removeItem').prop('disabled',true);
           }
       }
       // getLength();
       $(document).on('click','#removeItem', function(){
           var parent_id = $(this).parent().parent().attr('id');
        
           var numItems = $('.ItemRow').length;
           if(numItems > 1){
              // here we get the id of the child select then getting it's value by id
                var removed_item_id = $('#'+ parent_id +' .selectpicker').attr('id');
                var removed_item_value = document.getElementById(removed_item_id).value;
                // getting the object using the removed item value
                var obj = findObjectByKey(items_array, 'id', parseInt(removed_item_value));
                response_array.push(obj);

                $(this).parent().parent().remove();
                // RETURNS THE IDS OF ELEMENTS WITH THE SAME CLASS
                // var ids = $('.selectpicker').map(function() {
                //   return $(this).attr('id');
                // });
                // console.log(ids);

                //her 
                var i = 0;
                var ids = [];  
                var not_selected_options=[];
                    $(".selectpicker").each(function(){
                        ids[i++] =  $(this).attr("id"); //this.id
                    });
                    ids.clean(undefined);

                $.each(ids,function(index,value){
                  $('#'+value+" option").each(function()
                  {
                      not_selected_options.push($(this).val());
                  });
                    if(not_selected_options.includes(removed_item_value) === false){
                      // console.log(1);
                        $("#"+value).append('<option data-subtext="' +obj.name_en +'" value="' + obj.id + '">' + obj.name + '</option>' ).selectpicker("refresh");
                    }
                });
           }
       });
       $('#newItem').click(function () {
          var iv = document.getElementById('inc').value;
         $.each($( "#items"+iv +" option:selected" ),function(index,value){
          // console.log(value.value);
            selected.push(value.value);
         });
         // $('#items'+iv+'option:not(:selected)');      
         $.each(selected,function(index,value){
          // console.log(value);
          // console.log(typeof value);
          var index_of_selected = response_array.map(function(el) {
            return el.id;
          }).indexOf(parseInt(value));
          // console.log(index_of_selected);
            response_array.splice(index_of_selected, 1);
            // response_array.splice(index_of_selected, 1);
            selected.remove(value);
         });
           // getLength();
           // console.log(iv);
           var id = $('.ItemRow:last').attr('id');
           if($('#' + id + ' #items'+iv).val() == null || $('#'+id+' #qty').val() == null){
            // console.log('yes');
           }else{
            // console.log('no');
               var newClone = $('.ItemRow:last-of-type').clone();
               newClone.appendTo('#products');
               var contentTypeInput = $('.ItemRow:last');
               var cTypeIncrementNum = parseInt($('.ItemRow:last').prop('id').match(/\d+/g), 10) + 1;
               $('.ItemRow:last').attr('id', 'field' + cTypeIncrementNum);
               $('.ItemRow:last-of-type #items'+iv).attr('id','items' + cTypeIncrementNum);
               $('#items'+cTypeIncrementNum).selectpicker();
               $('#inc').val(cTypeIncrementNum);
               $('#inc').attr('value', cTypeIncrementNum);
               $('#items' + cTypeIncrementNum).empty().selectpicker("refresh");
               // $("#items").val('default');
               $('#items'+cTypeIncrementNum).append('<option value="-1" disabled selected>Select Item</option>' );

               $.each(response_array, function( index, value ) {
                   $('#items' + cTypeIncrementNum).append('<option data-subtext="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>' ).selectpicker("refresh");
               });

               var numItemss = $('.ItemRow:last .btn-group').length;
               
               if(numItemss > 1){
                $('.btn-group:last').remove();
               }
               // $('.ItemRow:last-of-type #items').hide();
               // $('.ItemRow:last-of-type #items').val('default').selectpicker('refresh');
               $('.ItemRow:last-of-type #qty').val('');

           }
       });
   });

    // remove div
     Array.prototype.remove = function() {
         var what, a = arguments, L = a.length, ax;
         while (L && this.length) {
             what = a[--L];
             while ((ax = this.indexOf(what)) !== -1) {
                 this.splice(ax, 1);
             }
         }
         return this;
     };
    // get the object by it's id
     function findObjectByKey(array, key, value) {
         for (var i = 0; i < array.length; i++) {
             if (array[i][key] === value) {
                 return array[i];
             }
         }
         return null;
     }

      // in_array
     function checkValue(value,arr){
        var status = 'Not exist';
       
        for(var i=0; i<arr.length; i++){
         var name = arr[i];
         if(name == value){
          status = 'Exist';
          break;
         }
        }

        return status;
     }

    // array_filter 
    Array.prototype.clean = function(deleteValue) {
      for (var i = 0; i < this.length; i++) {
        if (this[i] == deleteValue) {         
          this.splice(i, 1);
          i--;
        }
      }
      return this;
   };



</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>