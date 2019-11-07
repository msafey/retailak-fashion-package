  <div class="modal-dialog modal-lg email_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Returns</h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <button  type="button"  style="float: right;margin-bottom: 10px;" id="return_all" class="btn btn-danger return">Return All</button>
              <input type="text" hidden="" name="order_id" value="{{ $order_id }}" id="order_idss">

                <div class="col-md-12">
                  <table class="table">
                    <thead>
                      <tr>
                        <td class="col-md-2">Select Product</td>
                        <td class="col-md-3">Product Name</td>
                        <td class="col-md-2">Qty</td>
                        <td class="col-md-2">Rate</td>
                        <td class="col-md-3">Amount</td>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($order_items as $item)
                      <tr>
                        <td class="col-md-2">
                          <input type="checkbox" class="select_product" id="product{{$item->item_id}}" name="product_ids[]" value="{{$item->item_id}}">
                        </td>
                        <td class="col-md-3">
                          {{$item->item_name}}
                        </td>
                        <td class="col-md-2">
                          {{$item->qty}}
                        </td>
                        <td class="col-md-2">
                          {{$item->rate}}
                        </td>
                        <td class="col-md-3">
                          {{$item->rate * $item->qty}}
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
          <br>
          <br>      
                     <div class="row">
                          <div class="col-sm-32" id="saved"><button type="button" id="cancel" style="margin-left: 12px" class="btn btn-warning cancel" data-dismiss="modal" >Cancel</button>
                          <button  type="button"  style="margin-left: 12px" id="return_selected" class="btn btn-primary return">Return Selected</button>
                          </div>
                    </div>
          </div>
    </div>
  </div>

<script type="text/javascript">

$('.return').on('click',function(e){
    var attr_id = $(this).attr('id');
    var order_id = $('#order_idss').val();
    var product_ids = [];
    if(attr_id =='return_all'){
        var target = 'return_all';
    }else if(attr_id == 'return_selected'){
        var target = 'return_selected';
        $('.select_product:checkbox:checked').each(function(value) {
           product_ids.push(this.value);
        });
    }
    $.ajax({          
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '{!! route('returnOrderProducts') !!}',
      type: "POST",
      data: {'order_id': order_id,'product_ids':product_ids,'target':target},
      success: function (response) {
          if(response == 'already_cancelled'){
            alert('Already Cancelled');
          }
          if(response == 'success'){
              alert('selected product returned successfully');
          }
          if(response == 'no_product_selected'){
             alert('select products you want to return');
             return false;
          }
          if(response == 'false'){
            alert('something went wrong try again later');
            location.reload();
          }
          $('#myModalReturn').modal('hide');
          table.ajax.reload();
      },
      error: function (jqXHR, textStatus, errorThrown) {
      }
    });
});
// var url = window.location.href.split('?')[0];
//      
//       $(document).on('click','.save',function(event){
//         var user_id = $('#user_id').val();
//         var body = $('#editor').val();
//         if (user_id == "") {
//                 alert("Email must be filled out");
//                 return false;           
//         }else{
//             if($('.saved').length <= 0){
//                 $('#saved').append('<p class="saved" style="margin-left:10px;"><span style="color:red;">     loading....</span><p>');
//             }

//         $.ajax({          
//             headers: {
//               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             url: "{{URL::to('admin/runsheet/orders/email-invoice')}}",
//             type: "POST",
//             data: {'user_id': user_id,'products':products,'final_total_price':final_total_price,'order_id':order_id,'final_price_after_discount':final_price_after_discount,'promocode_msg':promocode_msg,'shipping_rate':shipping_rate,'delivery_order_id':delivery_order_id
//             },
//             success: function (data) {
//               if(data == 'true'){
//                 $('#myModal4').modal('hide');
//                 location.reload(); // then reload the page.(3)
//               }

//               if(data == 'true++'){
//                 $('#myModal4').modal('hide');
//                     // window.history.pushState("object or string", "Title", "/"+window.location.href.substring(window.location.href.lastIndexOf('/') + 1).split("?")[0]);
//                   history.pushState(null, null, url);

//                   window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;

//                 // location.reload(); // then reload the page.(3)
//               }
          
//             },
//             error: function (jqXHR, textStatus, errorThrown) {
//             }
//         }); 
//         }        
//       });

//       $(document).on('click','.cancel',function(event){
      
//           $('#myModal4').modal('hide');
//         if(runsheet_check_page != 0){
//           var cour= document.getElementById("courier_id").value;
//           var ware= document.getElementById("warehouse_id").value;
//           var sheet= document.getElementById("run_sheet_id").value;     
          
//           // function getUrlParam(name) {
//           //     var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
//           //     return (results && results[1]) || undefined;
//           // }

//           // var courier_id_check = getUrlParam('courier_id');
//           // var warehouse_id_check = getUrlParam('warehouse_id');
//           // var runsheet_id_check = getUrlParam('run_sheet_id');
//           // if (runsheet_id_check > "" || warehouse_id_check > "" || courier_id_check > "" || runsheet_id_check) {
//             // this changing the url without making the page refresh 
//               history.pushState(null, null, url);
//               window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;  

//           // } else {
//                // window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;  
//           // }
//         }else{
//           location.reload();
//         }
                   


// //              var url = window.location.href.split('?')[0];
// //              url.searchParams.append('courier_id', cour);
// //              url.searchParams.append('courier_id', cour);
// //              url.searchParams.append('courier_id', cour);
// //              // If your expected result is "http://foo.bar/?x=42&y=2"
// //              url.searchParams.set('warehouse_id', ware);
// //              url.searchParams.set('run_sheet_id', sheet);

// // console.log(url);
//       });
</script>