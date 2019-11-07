  <div class="modal-dialog modal-lg email_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
            
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
              <!-- <span aria-hidden="true">&times;</span> -->
            <!-- </button> -->
          </div>
          <div class="modal-body">
          <div class="form-group">
             <label style="margin-bottom: 0;"  class="form-group" for="from">Email: </label>    
              <input required id="email" name="email"  class="form-control" >
                  
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
          <label for="">Body Of Email</label>
           <textarea name="body"  id="editor" class="form-control col-sm-6" cols="7"></textarea>
          </div>
          <br>
          <br>      
                     <div class="row">
                          <div class="col-sm-32" id="saved">
                          <button  type="submit"  style="margin-left: 12px" id="save" class="btn btn-primary save">Save</button>
                          <button  type="cancel"  style="margin-left: 12px" id="thanks" class="btn btn-warning cancel">No Thanks</button>
                          </div>
                    </div>
          </div>
    </div>
  </div>

<script type="text/javascript">
   
      var company_name = '<?php echo $company_name;?>';

      var purchase_receipt_id = <?php echo $purchase_receipt_id ;?>;
    $(document).on('click','.cancel',function(event){
          $('#myModal4').modal('hide');
                <?php $purchase_invoice = url('/admin/purchase-invoices');?>

      window.location.href='<?php echo $purchase_invoice ?>';
    });
      
      
      $(document).on('click','.save',function(event){
    

           var email = $('#email').val();
        var body = $('#editor').val();
        if (email == "") {
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
            url: "{{URL::to('admin/purchase-invoices/sendEmail')}}",
            type: "POST",
            data: {'email': email,'body':body,'supplier_name':supplier_name,'purchase_receipt_id':purchase_receipt_id            },
            success: function (data) {
              // $('#saved').append('<p>loading....<p>');
              if(data == 'true'){
                $('#myModal4').modal('hide');
                <?php $purchase_order = url('/admin/purchase-invoices');?>

            window.location.href='<?php echo $purchase_order ?>';

              }
          
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });    
        }     
      });
</script>