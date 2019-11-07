  <div class="modal-dialog modal-lg email_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Send (SI) Email</h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div class="form-group">
             <label style="margin-bottom: 0;"  class="form-group" for="from">Customer Email: </label>    
              <input required id="user_id" name="user_id" @if(isset($user)) value="{{$user}}" @endif class="form-control" >
              <input  hidden="hidden" name="user_id"  value="{{$promocode_msg}}"  class="form-control" >

                  
          </div>
          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Posting Date: </label>    
              <div class='input-group date' id='datetimepicker1'>

                <input type='text' id="posting_date" disabled="disabled" name="posting_date" value="{{$date}}" class="form-control"/>
                <span class="input-group-addon">
                  <span class="zmdi zmdi-calendar"></span>
                </span>
              </div>

          </div>
          <div class="form-group">
          <label for="">Body</label>
           <textarea name="body"  id="editor" class="form-control col-sm-6" cols="7"></textarea>
          </div>
          <br>
          <br>      
                     <div class="row">
                          <div class="col-sm-32" id="saved"><button type="button" id="cancel" style="margin-left: 12px" class="btn btn-warning cancel">Cancel</button>
                          <button  type="button"  style="margin-left: 12px" id="save" class="btn btn-primary save">Send Email</button>
                          </div>
                    </div>
          </div>
    </div>
  </div>

<script type="text/javascript">

var url = window.location.href.split('?')[0];
      var products= <?php echo json_encode($products); ?>;
      var final_total_price=<?php echo $final_total_price; ?>;
      var order_id = <?php echo $id ;?>;
      var final_price_after_discount  = <?php echo $final_total_price ; ?>;
      var shipping_rate = <?php echo $shipping_rate ?>;
      var promocode_msg = $('#promocode_msg').val();
      var delivery_order_id = <?php echo $delivery_order_id; ?>;
      var runsheet_check_page = <?php echo $runsheet; ?>; 
      $(document).on('click','.save',function(event){
        var user_id = $('#user_id').val();
        var body = $('#editor').val();
        if (user_id == "") {
                alert("Email must be filled out");
                return false;           
        }else{
            if($('.saved').length <= 0){
                $('#saved').append('<p class="saved" style="margin-left:10px;"><span style="color:red;">     loading....</span><p>');
            }

        $.ajax({          
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{URL::to('admin/runsheet/orders/email-invoice')}}",
            type: "POST",
            data: {'user_id': user_id,'products':products,'final_total_price':final_total_price,'order_id':order_id,'final_price_after_discount':final_price_after_discount,'promocode_msg':promocode_msg,'shipping_rate':shipping_rate,'delivery_order_id':delivery_order_id
            },
            success: function (data) {
              if(data == 'true'){
                $('#myModal4').modal('hide');
                location.reload(); // then reload the page.(3)
              }

              if(data == 'true++'){
                $('#myModal4').modal('hide');
                    // window.history.pushState("object or string", "Title", "/"+window.location.href.substring(window.location.href.lastIndexOf('/') + 1).split("?")[0]);
                  history.pushState(null, null, url);

                  window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;

                // location.reload(); // then reload the page.(3)
              }
          
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        }); 
        }        
      });

      $(document).on('click','.cancel',function(event){
      
          $('#myModal4').modal('hide');
        if(runsheet_check_page != 0){
          var cour= document.getElementById("courier_id").value;
          var ware= document.getElementById("warehouse_id").value;
          var sheet= document.getElementById("run_sheet_id").value;     
          
          // function getUrlParam(name) {
          //     var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
          //     return (results && results[1]) || undefined;
          // }

          // var courier_id_check = getUrlParam('courier_id');
          // var warehouse_id_check = getUrlParam('warehouse_id');
          // var runsheet_id_check = getUrlParam('run_sheet_id');
          // if (runsheet_id_check > "" || warehouse_id_check > "" || courier_id_check > "" || runsheet_id_check) {
            // this changing the url without making the page refresh 
              history.pushState(null, null, url);
              window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;  

          // } else {
               // window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;  
          // }
        }else{
          location.reload();
        }
                   


//              var url = window.location.href.split('?')[0];
//              url.searchParams.append('courier_id', cour);
//              url.searchParams.append('courier_id', cour);
//              url.searchParams.append('courier_id', cour);
//              // If your expected result is "http://foo.bar/?x=42&y=2"
//              url.searchParams.set('warehouse_id', ware);
//              url.searchParams.set('run_sheet_id', sheet);

// console.log(url);
      });
</script>